<?php

namespace App\Http\Controllers\Web\Admin;

use App\Base\Filters\Admin\RequestFilter;
use App\Base\Filters\Admin\RequestCancellationFilter;
use App\Base\Filters\Master\CommonMasterFilter;
use App\Base\Libraries\QueryFilter\QueryFilterContract;
use App\Http\Controllers\Controller;
use App\Models\Request\Request as RequestRequest;
use App\Models\Request\RequestCancellationFee;
use App\Models\Admin\CancellationReason;
use Illuminate\Http\Request;

class CancellationRideController extends Controller
{
    public function index()
    {
        $page = trans('pages_names.cancellation_rides');
        $main_menu = 'trip-request';
        $sub_menu = 'cancellation-rides';

        return view('admin.cancellation-rides.index', compact('page', 'main_menu', 'sub_menu'));
    }

    public function getAllRides(QueryFilterContract $queryFilter)
    {
        $query = RequestCancellationFee::query();
        $results = $queryFilter->builder($query)->customFilter(new RequestCancellationFilter)->defaultSort('-created_at')->paginate();

        if (auth()->user()->countryDetail) {
            $currency = auth()->user()->countryDetail->currency_symbol;
        } else {
            $currency = env('SYSTEM_DEFAULT_CURRENCY');
        }

        return view('admin.cancellation-rides._rides', compact('results','currency'));
    }

   
     
}
