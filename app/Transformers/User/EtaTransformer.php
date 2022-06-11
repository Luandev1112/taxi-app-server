<?php

namespace App\Transformers\User;

use Carbon\Carbon;
use App\Models\Admin\Zone;
use App\Models\Admin\Promo;
use App\Models\Admin\Driver;
use App\Models\Admin\ZoneType;
use App\Models\Admin\PromoUser;
use App\Transformers\Transformer;
use App\Models\Admin\ZoneSurgePrice;
use App\Models\Master\DistanceMatrix;
use Illuminate\Support\Facades\Redis;
use App\Helpers\Exception\ExceptionHelpers;
use App\Base\Constants\Masters\EtaConstants;
use App\Base\Constants\Masters\zoneRideType;
use App\Transformers\Access\RoleTransformer;

class EtaTransformer extends Transformer
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
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(ZoneType $zone_type)
    {
        $pick_lat = request()->pick_lat;
        $pick_lng = request()->pick_lng;
        $drop_lat = request()->drop_lat;
        $drop_lng = request()->drop_lng;

        $response =  [
            'zone_type_id' => $zone_type->id,
            'name' => $zone_type->vehicleType->name,
            'description'=> $zone_type->vehicleType->description,
            'short_description'=> $zone_type->vehicleType->short_description,
            'supported_vehicles'=> $zone_type->vehicleType->supported_vehicles,
            'payment_type'=>$zone_type->payment_type,
            'is_default'=>false,
        ];

        if ($zone_type->zone->default_vehicle_type==$zone_type->type_id) {
            $response['is_default'] = true;
        }
        if (!request()->has('vehicle_type')) {
            $response['icon'] = $zone_type->icon;
            $response['type_id']=$zone_type->type_id;
        }
        /**
         * get prices from zone type
         */
        if (request()->ride_type==zoneRideType::RIDENOW) {
            $ride_type = zoneRideType::RIDENOW;
        } else {
            $ride_type = zoneRideType::RIDELATER;
        }
        $coupon_detail = null;

        if (request()->has('promo_code') && request()->input('promo_code')) {
            $coupon_detail = $this->validate_promo_code($zone_type->zone->service_location_id);
        }
        $type_prices = $zone_type->zoneTypePrice()->where('price_type', $ride_type)->first();

        $distance_in_unit = 0;
        $dropoff_time_in_seconds = 0;

        if (request()->has('drop_lat') && request()->has('drop_lng') && request()->drop_lat) {
            // get previous place json or store current one
            $previous_pickup_dropoff = $this->db_query_previous_pickup_dropoff($pick_lat, $pick_lng, $drop_lat, $drop_lng);

            $place_details = json_decode($previous_pickup_dropoff->json_result);

            $dropoff_distance_in_meters = get_distance_value_from_distance_matrix($place_details);

            if ($dropoff_distance_in_meters) {
                $distance_in_unit = $dropoff_distance_in_meters / 1000;
                if ($zone_type->zone->unit=="2") {
                    $distance_in_unit = kilometer_to_miles($distance_in_unit);

                }
            }
            $dropoff_time_in_seconds = get_duration_value_from_distance_matrix($place_details);
        }


        $near_driver_status = 0; //its means there is no driver available

        $driver_lat = $pick_lat;
        $driver_lng = $pick_lng;
        $near_driver = null;
        if (request()->has('drivers')) {
            $driver_data_with_distance = [];
            $driver_distance = [];
            foreach (json_decode(request()->drivers) as $key => $driver) {
                $driver_data = new \stdClass();
                $driver_data->id = $driver->driver_id;
                $driver_data->lat = $driver->driver_lat;
                $driver_data->lng = $driver->driver_lng;
                $driver_data->distance = self::calculate_distance(request()->pick_lat, request()->pick_lng, $driver->driver_lat, $driver->driver_lng, 'K');
                $driver_data_with_distance []= $driver_data;
                $driver_distance[] = $driver_data->distance;
            }

            $min_distance_driver = min($driver_distance);

            foreach ($driver_data_with_distance as $key => $driver_data) {
                if ($min_distance_driver==$driver_data->distance) {
                    $near_driver = $driver_data;
                    break;
                }
            }

            if ($near_driver==null) {
                $driver_lat = $pick_lat;
                $driver_lng = $pick_lng;
            } else {
                $driver_lat = $near_driver->lat;
                $driver_lng = $near_driver->lng;
                $near_driver_status=1;
            }
        }



        

        // $driver_to_pickup_response = json_decode($driver_to_pickup->json_result);
        if ($zone_type->zone->unit==1) {
            $unit_in_words = 'KM';
        } else {
            $unit_in_words = 'MILES';
        }
        // $unit_in_words = EtaConstants::ENGLISH_UNITS[$zone_type->zone->unit];
        $translated_unit_in_words = $unit_in_words;

        $ride = $this->calculateRideFares($distance_in_unit, $dropoff_time_in_seconds, $zone_type, $type_prices, $coupon_detail);

        if ($near_driver_status != 0) {
            if ($ride->pickup_duration != 0) {
                $driver_arival_estimation = "{$ride->pickup_duration} min";
            } else {
                $driver_arival_estimation = "1 min";
            }
        } else {
            $driver_arival_estimation = "--";
        }
        $response['has_discount'] = false;
        if ($ride->discount_amount > 0) {
            $response['has_discount'] = true;
            $response['discounted_totel'] = $ride->discounted_total_price;
            $response['discount_total_tax_amount'] = $ride->discount_total_tax_amount;
            $response['promocode_id'] = $coupon_detail->id;
        }
        $response['discount_amount'] = $ride->discount_amount;
        $response['distance'] = $ride->distance;
        $response['time'] = $ride->duration;
        $response['base_distance'] = $ride->base_distance;
        $response['base_price'] = $ride->base_price;
        $response['price_per_distance'] = $ride->price_per_distance;
        $response['price_per_time'] = $ride->price_per_time;
        $response['distance_price'] = $ride->distance_price;
        $response['time_price'] = $ride->time_price;
        $response['ride_fare'] = $ride->subtotal_price;
        $response['tax_amount'] = $ride->tax_amount;
        $response['tax'] = $ride->tax_percent;
        $response['total'] = $ride->total_price;
        $response['approximate_value'] = 1;
        $response['min_amount'] = $ride->total_price;
        $response['max_amount'] = ($ride->total_price * 1.05);
        $response['currency'] = $zone_type->zone->serviceLocation->currency_symbol;
        $response['currency_name'] = $zone_type->zone->serviceLocation->currency_name;
        $response['type_name'] = $zone_type->vehicleType->name;
        $response['unit'] = $zone_type->unit;
        $response['unit_in_words_without_lang'] = $unit_in_words;
        $response['unit_in_words'] = $translated_unit_in_words;
        $response['driver_arival_estimation'] = $driver_arival_estimation;
        


        return $response;
    }

    public function calculate_distance($lat1, $lon1, $lat2, $lon2, $unit)
    {
        if (($lat1 == $lat2) && ($lon1 == $lon2)) {
            return 0;
        } else {
            $theta = $lon1 - $lon2;
            $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
            $dist = acos($dist);
            $dist = rad2deg($dist);
            $miles = $dist * 60 * 1.1515;
            $unit = strtoupper($unit);

            if ($unit == "K") {
                return ($miles * 1.609344);
            } elseif ($unit == "N") {
                return ($miles * 0.8684);
            } else {
                return $miles;
            }
        }
    }

    private function calculateRideFares($distance_in_unit, $dropoff_time_in_seconds, $zone_type, $type_prices, $coupon_detail)
    {
        // $pickup_time_in_seconds = get_duration_value_from_distance_matrix($driver_to_pickup_response);
        $pickup_time_in_seconds = 0;
        $wait_time_in_seconds = 180; // can be change
        /**
        * @TODO surge price calculations
        */

        $distance_in_unit = ($distance_in_unit - $type_prices->base_distance);
        
        $distance_in_unit = $distance_in_unit>0?:0;

        $distance_price = ($distance_in_unit * $type_prices->price_per_distance);

        $surgePrice = ZoneSurgePrice::whereZoneId($zone_type->zone_id)->get();
        $peakValue = 0;
        foreach ($surgePrice as $surge) {
            $startDate = now()->parse($surge->start_time);
            $endDate = now()->parse($surge->end_time);

            // if(now()->between($startDate,$endDate)){
            if (now()->gte($startDate)  && now()->lte($endDate)) {
                $peakValue = $distance_price * ($surge->value / 100);
            }
        }

        $distance_price = $distance_price + $peakValue;
        // $check_if_peak_time = $this->checkIfPeakTime($zone_type, request()->ride_type);
        $time_price = ($dropoff_time_in_seconds / 60) * $type_prices->price_per_time;
        $base_price = $type_prices->base_price;
        // additon of base and distance price
        $base_and_distance_price = ($base_price + $distance_price);
        $base_distance = $type_prices->base_distance;
        // if ($distance_in_unit < $base_distance) {
        //     $base_and_distance_price = $base_price;
        // }
        $subtotal_price = $base_and_distance_price + $time_price;
        $discount_amount = 0;
        $coupon_applied_sub_total = $base_and_distance_price + $time_price;

        if ($coupon_detail) {
            if ($coupon_detail->minimum_trip_amount < $subtotal_price) {
                $discount_amount = $subtotal_price * ($coupon_detail->discount_percent/100);
                if ($coupon_detail->maximum_discount_amount>0 && $discount_amount > $coupon_detail->maximum_discount_amount) {
                    $discount_amount = $coupon_detail->maximum_discount_amount;
                }
                $coupon_applied_sub_total = $subtotal_price - $discount_amount;
            }
        }
        // if trip distace is lessthan base distance, no need to calculate time price

        // Get Admin Commision
        $service_fee = get_settings('admin_commission');
        // Admin commision
        $without_discount_admin_commision = (($subtotal_price + $discount_amount) * ($service_fee / 100));
        $tax_percent = get_settings('service_tax');
        $with_out_discount_tax_amount = (($subtotal_price + $discount_amount) * ($tax_percent / 100));
        $total_price = $subtotal_price + $with_out_discount_tax_amount + $without_discount_admin_commision;

        $discount_admin_commision = ($coupon_applied_sub_total * ($service_fee / 100));
        $discount_tax_amount = $coupon_applied_sub_total * ($tax_percent / 100);
        $discounted_total_price = $coupon_applied_sub_total + $discount_tax_amount + $discount_admin_commision;

        
        $pickup_duration = $pickup_time_in_seconds / 60;
        $dropoff_duration = $dropoff_time_in_seconds / 60;
        $wait_duration = $wait_time_in_seconds / 60;
        $duration = $pickup_duration + $dropoff_duration + $wait_duration;

        return (object)[
                'distance' => round($distance_in_unit, 2),
                'base_distance' => $base_distance,
                'base_price' => $base_price,
                'price_per_distance' => $type_prices->price_per_distance,
                'price_per_time' => $type_prices->price_per_time,
                'distance_price' => $distance_price,
                'time_price' => $time_price,
                'subtotal_price' => $subtotal_price,
                'tax_percent' => $tax_percent,
                'tax_amount' => $with_out_discount_tax_amount,
                'discount_total_tax_amount'=>$discount_tax_amount,
                'total_price' => $total_price,
                'discounted_total_price'=>$discounted_total_price,
                'discount_amount'=>$discount_amount,
                'pickup_duration' => round($pickup_duration),
                'dropoff_duration' => round($dropoff_duration),
                'wait_duration' => round($wait_duration),
                'duration' => round($duration),
            ];
    }

    /**
    * Check if peak time
    */
    private function checkIfPeakTime($zone_type, $ride_type)
    {
    }
    //vehicle type id should be zone_type id
    private function findNearestDriver($pick_lat, $pick_lng, $vehicle_type)
    {

        $driver_search_radius = get_settings('driver_search_radius')?:30;

        $haversine = "(6371 * acos(cos(radians($pick_lat)) * cos(radians(latitude)) * cos(radians(longitude) - radians($pick_lng)) + sin(radians($pick_lat)) * sin(radians(latitude))))";

        $driver = Driver::whereHas('driverDetail', function ($query) use ($haversine,$driver_search_radius) {
            $query->select('driver_details.*')->selectRaw("{$haversine} AS distance")
                ->whereRaw("{$haversine} < ?", [$driver_search_radius]);
        })->where('active', 1)->where('approve', 1)->where('available', 1)->first();

        return $driver?:null;
    }

    private function db_query_previous_pickup_dropoff($pick_lat, $pick_lng, $drop_lat, $drop_lng)
    {
        return $this->db_query_nearest_distance_matrix(
            $pick_lat,
            $pick_lng,
            $drop_lat,
            $drop_lng,
            EtaConstants::PICKUP_RADIUS_IN_METERS,
            EtaConstants::DROPOFF_RADIUS_IN_METERS
        );
    }

    private function db_query_nearest_distance_matrix($pick_lat, $pick_lng, $drop_lat, $drop_lng, $radius1, $radius2)
    {
        $earth_radius = EtaConstants::EARTH_RADIUS_IN_METERS;
        $update_after = Carbon::now()->subMinute(EtaConstants::LOCATION_CACHE_TIME_IN_MINUTES)->toDateTimeString();

        // uses haversine formula for calculating distance
        $nearest_distance_matrix = DistanceMatrix::selectRaw("
      id,
      origin_addresses,
      ROUND($earth_radius *
        IFNULL(ACOS(
          COS( RADIANS(?) ) *
          COS( RADIANS(origin_lat) ) *
          COS( RADIANS(origin_lng) - RADIANS(?) ) +
          SIN( RADIANS(?) ) *
          SIN( RADIANS(origin_lat) )
        ), 0), 8) AS origin_distance,
      destination_addresses,
      ROUND($earth_radius *
        IFNULL(ACOS(
          COS( RADIANS(?) ) *
          COS( RADIANS(destination_lat) ) *
          COS( RADIANS(destination_lng) - RADIANS(?) ) +
          SIN( RADIANS(?) ) *
          SIN( RADIANS(destination_lat) )
        ), 0), 8) AS destination_distance,
      json_result", [
            $pick_lat,
            $pick_lng,
            $pick_lat,
            $drop_lat,
            $drop_lng,
            $drop_lat
        ])
            ->where("updated_at", ">=", $update_after)
            ->having("origin_distance", "<=", $radius1)
            ->having("destination_distance", "<=", $radius2)
            ->orderBy("origin_distance")
            ->orderBy("destination_distance")
            ->first();

        if (!$nearest_distance_matrix) {
            $nearest_distance_matrix =  $this->save_distance_matrix_from_google($pick_lat, $pick_lng, $drop_lat, $drop_lng, true);
        }
        return $nearest_distance_matrix;
    }
    public function save_distance_matrix_from_google($pick_lat, $pick_lng, $drop_lat, $drop_lng, $traffic)
    {
        $distance_matrix = get_distance_matrix($pick_lat, $pick_lng, $drop_lat, $drop_lng, $traffic);

        $carbonNow = Carbon::now()->toDateTimeString();

        if ($distance_matrix && $distance_matrix->status == 'OK') {
            $distance_matrix_params = [
                'origin_addresses'=>$distance_matrix->origin_addresses[0],
                'origin_lat'=>$pick_lat,
                'origin_lng'=>$pick_lng,
                'destination_addresses'=>$distance_matrix->destination_addresses[0],
                'destination_lat'=>$drop_lat,
                'destination_lng'=>$drop_lng,
                'distance'=> get_distance_text_from_distance_matrix($distance_matrix)==null?0:get_distance_text_from_distance_matrix($distance_matrix),
                'duration'=> get_duration_text_from_distance_matrix($distance_matrix)==null?0:get_duration_text_from_distance_matrix($distance_matrix),
                'json_result'=> \GuzzleHttp\json_encode($distance_matrix)
                ];

            return $stored_distance_matrix_details = DistanceMatrix::create($distance_matrix_params);
        } else {
            $this->throwCustomException('Unable to calculate distance between coordinates');
        }
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

        $expired = Promo::where('code', $promo_code)->where('from', '<=', $current_date)->orWhere('to', '>=', $current_date)->where('service_location_id', $service_location)->first();

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
