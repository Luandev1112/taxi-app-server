<?php

namespace App\Http\Controllers\Api\V1\Common;

use App\Models\Admin\Faq;
use App\Base\Constants\Auth\Role;
use App\Transformers\Common\FaqTransformer;
use Grimzy\LaravelMysqlSpatial\Types\Point;
use App\Http\Controllers\Api\V1\BaseController;

/**
 * @group FAQ
 *
 * APIs for faq lists for user & driver
 */
class FaqController extends BaseController
{
    protected $faq;

    public function __construct(Faq $faq)
    {
        $this->faq = $faq;
    }

    /**
    * List Faq
    * @urlParam lat required double  latitude provided by user
    * @urlParam lng required double  longitude provided by user
    * @responseFile responses/common/faq.json
    */
    public function index($lat, $lng)
    {
        $point = new Point($lat, $lng);

        if (access()->hasRole(Role::USER)) {
            $user_type = 'user';
        } else {
            $user_type = 'driver';
        }
        $query = $this->faq->whereHas('serviceLocation.zones', function ($query) use ($point) {
            $query->contains('coordinates', $point)->where('active', 1);
        })->where('user_type', $user_type)->orWhere('user_type', 'both');

        $result=filter($query, new FaqTransformer)->paginate();

        return $this->respondSuccess($result);
    }
}
