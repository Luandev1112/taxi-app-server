<?php

namespace App\Transformers\Requests;

use App\Models\Master\PackageType;
use App\Transformers\Transformer;
use App\Helpers\Exception\ExceptionHelpers;
use App\Models\Admin\ZoneType;
use App\Transformers\User\ZoneTypeTransformer;
use App\Transformers\Requests\ZoneTypeWithPackagePriceTransformer;
use App\Models\Admin\Promo;
use Carbon\Carbon;
use App\Models\Admin\PromoUser;

class PackagesTransformer extends Transformer
{
    use ExceptionHelpers;
     /**
     * Resources that can be included if requested.
     *
     * @var array
     */
    protected $availableIncludes = [
        
    ];
    /**
     * Resources that can be included default.
     *
     * @var array
     */
    protected $defaultIncludes = [
        
        'typesWithPrice'
    ];

    /**
     * A Fractal transformer.
     *
     * @param PackageType $package
     * @return array
     */
    public function transform(PackageType $package)
    {
        $params = [
            'id' => $package->id,
            'package_name'=>$package->name,
            'description'=>$package->description,
            'short_description'=>$package->short_description,
        ];

       
        return $params;
    }


    /**
    * Include the vehicle type along with price.
    *
    * @param User $user
    * @return \League\Fractal\Resource\Collection|\League\Fractal\Resource\NullResource
    */
    public function includeTypesWithPrice(PackageType $package)
    {   

        $zone_detail = find_zone(request()->input('pick_lat'), request()->input('pick_lng'));

        if (!$zone_detail) {
            $this->throwCustomException('service not available with this location');
        }

        $types = ZoneType::where('zone_id',$zone_detail->id)->whereHas('zoneTypePackage',function($query)use($package){
            $query->where('package_type_id',$package->id);
        })->get();

        $zone_types = [];


        foreach ($types as $key => $type) {

            $prices = $type->zoneTypePackage()->where('package_type_id',$package->id)->first();

            $zone_types[] = array(
                'zone_type_id'=>$type->id,
                'type_id'=>$type->type_id,
                'name'=>$type->vehicle_type_name,
                'icon'=>$type->icon,
                'capacity'=>$type->vehicleType->capacity,
                'currency'=> $type->zone->serviceLocation->currency_symbol,
                'unit' => $type->zone->unit,
                'unit_in_words' => $type->zone->unit ? 'Km' : 'Miles',
                'distance_price_per_km'=>$prices->distance_price_per_km,
                'time_price_per_min'=>$prices->time_price_per_min,
                'free_distance'=>$prices->free_distance,
                'free_min'=>$prices->free_min,
                'payment_type'=>$type->payment_type,
                'fare_amount'=>$prices->base_price,
                'description'=> $type->vehicleType->description,
                'short_description'=> $type->vehicleType->short_description,
                'supported_vehicles'=> $type->vehicleType->supported_vehicles,
                'is_default'=>$type->zone->default_vehicle_type==$type->type_id?true:false,
                'discounted_totel'=>0,
                'has_discount'=>false,
            );

            if (request()->has('promo_code') && request()->input('promo_code')) {
            $coupon_detail = $this->validate_promo_code($zone_detail->service_location_id);

            if ($coupon_detail) {
            if ($coupon_detail->minimum_trip_amount < $prices->base_price) {
            $discount_amount = $prices->base_price * ($coupon_detail->discount_percent/100);
            if ($coupon_detail->maximum_discount_amount>0 && $discount_amount > $coupon_detail->maximum_discount_amount) {
            $discount_amount = $coupon_detail->maximum_discount_amount;
            }
            $coupon_applied_sub_total = $prices->base_price - $discount_amount;
            $zone_types[$key]['discounted_totel'] = $coupon_applied_sub_total;

            $zone_types[$key]['has_discount'] = true;
            }else{
            $this->throwCustomException('promo cannot be used to your trip amount');

            }

            }
            }


        }



              
        return $zone_types
        ? $this->collection(collect($zone_types), new ZoneTypeWithPackagePriceTransformer)
        : $this->null();
    }

     public function validate_promo_code($service_location)
    {
        $user = auth()->user();
        if (!request()->has('promo_code')) {
            return $coupon_detail = null;
        }
        $promo_code = request()->input('promo_code');
        // Validate if the promo is expired
        $current_date = Carbon::today()->toDateTimeString();

        $expired = Promo::where('code', $promo_code)->where('from', '<=', $current_date)->where('to', '>=', $current_date)->where('service_location_id', $service_location)->first();

        if (!$expired) {
            $this->throwCustomException('provided promo code expired or invalid');
        }
        $exceed_usage = PromoUser::where('promo_code_id', $expired->id)->where('user_id', $user->id)->get()->count();
        if ($exceed_usage >= $expired->uses_per_user) {
            $this->throwCustomException('you have exceeded your limit for this promo');
        }
        if ($expired->total_uses > $expired->total_uses+1) {
            $this->throwCustomException('provided promo code expired');
        }
        return $expired;
    }
}
