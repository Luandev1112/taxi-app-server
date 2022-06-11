<?php

namespace App\Base\Serializers;

use League\Fractal\Serializer\DataArraySerializer;

class CustomDataArraySerializer extends DataArraySerializer
{
    /**
     * Serialize a collection.
     *
     * @param string $resourceKey
     * @param array  $data
     *
     * @return array
     */
    public function collection($resourceKey, array $data)
    {
        return $resourceKey === false ? $data : compact('data');
    }

    /**
     * Serialize an item.
     *
     * @param string $resourceKey
     * @param array  $data
     *
     * @return array
     */
    public function item($resourceKey, array $data)
    {
        return $resourceKey === false ? $data : compact('data');
    }

    /**
     * Serialize null resource.
     *
     * @return array
     */
    public function null()
    {
        // return ['data' => []];
        return;
    }
}
