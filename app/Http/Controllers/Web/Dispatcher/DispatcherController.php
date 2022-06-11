<?php

namespace App\Http\Controllers\Web\Dispatcher;

use App\Base\Filters\Admin\RequestFilter;
use App\Base\Libraries\QueryFilter\QueryFilterContract;
use App\Http\Controllers\Web\BaseController;
use App\Models\Request\Request as RequestRequest;
use Illuminate\Http\Request;

class DispatcherController extends BaseController
{
    public function index()
    {
        $main_menu = 'dispatch_request';

        $sub_menu = null;

        $page = 'Dispatch Requests';

        return view('admin.dispatcher.requests', compact(['main_menu','sub_menu','page']));
    }

    public function dispatchView(){
        $main_menu = 'dispatch_request';

        $sub_menu = null;

        $page = 'Dispatch Requests';

        $default_lat = env('DEFAULT_LAT');
        $default_lng = env('DEFAULT_LNG');
        return view('admin.dispatcher.dispatch', compact(['main_menu','sub_menu','page', 'default_lat', 'default_lng']));
    }

    public function bookNow()
    {
         $main_menu = 'dispatch_request';

        $sub_menu = null;
         $default_lat = env('DEFAULT_LAT');
        $default_lng = env('DEFAULT_LNG');

        return view('dispatch.new-ui.book-now')->with(compact('main_menu','sub_menu','default_lat', 'default_lng'));
    }

    /**
    *
    * create new request
    */
    public function createRequest(Request $request)
    {
        dd($request->all());
    }

    public function loginView(){
        return view('admin.dispatch-login');
    }

    public function dashboard(){
        return view('dispatch.home');
    }

    public function fetchBookingScreen($modal){
        return view("dispatch.$modal");
    }

    public function fetchRequestLists(QueryFilterContract $queryFilter){

        $query = RequestRequest::query();

        $results = $queryFilter->builder($query)->customFilter(new RequestFilter)->paginate();

        return view('dispatch.request-list', compact('results'));
    }

    public function profile(){
        return view('dispatch.profile');
    }

     public function fetchSingleRequest(RequestRequest $requestmodel){
        return $requestmodel;
    }

     public function requestDetailedView(RequestRequest $requestmodel){
        $item = $requestmodel;
        
        return view('dispatch.request_detail',compact('item'));
    }
}
