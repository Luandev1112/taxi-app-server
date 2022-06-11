<?php

namespace App\Transformers;

use App\Models\Country;

class CountryTransformer extends Transformer
{
    /**
     * A Fractal transformer.
     *
     * @param Country $country
     * @return array
     */
    public function transform(Country $country)
    {
        return [
            'id' => $country->id,
            'dial_code' => $country->dial_code,
            'name' => $country->name,
            'code' => $country->code,
            'flag'=>$country->flag,
            'dial_min_length'=>$country->dial_min_length,
            'dial_max_length'=>$country->dial_max_length,
            'active' => (bool)$country->active,
        ];
    }
}
