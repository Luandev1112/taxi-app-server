<?php

namespace App\Http\Controllers\Api\V1\Common;

use App\Http\Controllers\Api\V1\BaseController;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Models\Country;
use Illuminate\Support\Facades\Mail;

/**
 * @group Demo Request
 *
 * APIs for vehilce management apis. i.e types,car makes,models apis
 */
class DemoRequestController extends BaseController
{
    protected $toMail = ['sales@tagyourtaxi.com', 'nash@tagyourtaxi.com', 'tagyourtaxi@gmail.com'];
    

    /**
     *  Demo Request Api
     *  @bodyParam name string optional name of customer
     *  @bodyParam mobile string required mobile of customer
     *  @bodyParam email string required email of customer
     *  @bodyParam country string required country of customer
     *  @bodyParam message string optional message of customer
     * 
     * */
    public function create(Request $request){

        $requested_params = $request->only(['name','email','message']);

        $requested_params['mobile'] = $request->country . ' ' . $request->mobile;

        $country = Country::where('dial_code', $request->input('country'))->first();

        if($country){

           $requested_params['country'] = $country->name;

        }

        Customer::create($requested_params);

        if(!$request->has('message')){
            $requested_params['message'] = '--';            
        }

        $data = ['data'=>$requested_params];

         Mail::send('emails.contactUs', $data, function ($message) use ($request) {
            $message->subject('A New Enquiry From Mobile App -' . $request->name);
            $message->to($this->toMail);
        });


        return $this->respondSuccess(null, 'demo_requested_successfully');

    }

}
