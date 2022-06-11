<?php

namespace App\Http\Controllers\Web\Admin;

use App\Base\Filters\Admin\RequestFilter;
use App\Base\Filters\Master\CommonMasterFilter;
use App\Base\Libraries\QueryFilter\QueryFilterContract;
use App\Http\Controllers\Controller;
use App\Models\Request\Request as RequestRequest;
use Illuminate\Http\Request;

class RequestController extends Controller
{
    public function index()
    {
        $page = trans('pages_names.request');
        $main_menu = 'trip-request';
        $sub_menu = 'request';

        return view('admin.request.index', compact('page', 'main_menu', 'sub_menu'));
    }

    public function getAllRequest(QueryFilterContract $queryFilter)
    {
        $query = RequestRequest::companyKey();

        $results = $queryFilter->builder($query)->customFilter(new RequestFilter)->defaultSort('-created_at')->paginate();

        return view('admin.request._request', compact('results'));
    }

    public function retrieveSingleRequest(RequestRequest $request){
        $item = $request;

        return view('admin.request._singlerequest', compact('item'));
    }

    public function getSingleRequest(RequestRequest $request)
    {
        $page = trans('pages_names.request');
        $main_menu = 'trip-request';
        $sub_menu = 'request';

        $item = $request;

        return view('admin.request.requestview', compact('page', 'main_menu', 'sub_menu', 'item'));
    } 

    public function fetchSingleRequest(RequestRequest $request){
        return $request;
    }

     public function requestDetailedView(RequestRequest $request){
        $item = $request;
        $page = trans('pages_names.request');
         $main_menu = 'trip-request';
        $sub_menu = 'request';
        
        return view('admin.request.trip-request',compact('item','page', 'main_menu', 'sub_menu'));
    }

     public function indexScheduled()
    {
        $page = trans('pages_names.request');
        $main_menu = 'trip-request';
        $sub_menu = 'scheduled-rides';

        return view('admin.scheduled-rides.index', compact('page', 'main_menu', 'sub_menu'));
    }

     public function getAllScheduledRequest(QueryFilterContract $queryFilter)
    {
        $query = RequestRequest::companyKey()->whereIsCompleted(false)->whereIsCancelled(false)->whereIsLater(true);
        $results = $queryFilter->builder($query)->customFilter(new RequestFilter)->defaultSort('-created_at')->paginate();

        return view('admin.scheduled-rides._scheduled', compact('results'));
    }
     
}
