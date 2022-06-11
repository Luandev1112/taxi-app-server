<?php

namespace App\Transformers\Requests;

use App\Transformers\Transformer;
use App\Models\Request\RequestBill;

class RequestBillTransformer extends Transformer
{
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
     * @param RequestBill $request
     * @return array
     */
    public function transform(RequestBill $request)
    {
        return [
            'id' => $request->id,
            'base_price' => $request->base_price,
            'base_distance' => $request->base_distance,
            'price_per_distance' => $request->price_per_distance,
            'distance_price' => $request->distance_price,
            'price_per_time' => $request->price_per_time,
            'time_price' => $request->time_price,
            'waiting_charge' => $request->waiting_charge,
            'cancellation_fee' => $request->cancellation_fee,
            'airport_surge_fee'=>$request->airport_surge_fee,
            'service_tax' => $request->service_tax,
            'service_tax_percentage' => $request->service_tax_percentage,
            'promo_discount' => $request->promo_discount,
            'admin_commision' => $request->admin_commision,
            'driver_commision' => $request->driver_commision,
            'total_amount' => $request->total_amount,
            'requested_currency_code' => $request->requested_currency_code,
            'requested_currency_symbol' => $request->requested_currency_symbol,
            'admin_commision_with_tax' => $request->admin_commision_with_tax,
        ];
    }
}
