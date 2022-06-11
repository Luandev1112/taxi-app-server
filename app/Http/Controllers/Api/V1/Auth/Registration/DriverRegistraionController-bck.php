<?php

namespace App\Http\Controllers\Api\V1\Auth\Registration;

use App\Models\User;
use App\Models\Country;
use App\Models\Admin\Driver;
use Illuminate\Http\Request;
use Kreait\Firebase\Database;
use App\Base\Constants\Auth\Role;
use Illuminate\Support\Facades\DB;
use App\Events\Auth\UserRegistered;
use Illuminate\Support\Facades\Log;
use App\Models\Admin\ServiceLocation;
use App\Base\Constants\Masters\WalletRemarks;
use App\Http\Controllers\Api\V1\BaseController;
use App\Jobs\Notifications\AndroidPushNotification;
use App\Base\Services\OTP\Handler\OTPHandlerContract;
use App\Http\Controllers\Api\V1\Auth\LoginController;
use App\Http\Requests\Auth\Registration\DriverRegistrationRequest;
use App\Jobs\Notifications\Auth\Registration\UserRegistrationNotification;

/**
 * @group SignUp-And-Otp-Validation
 *
 * APIs for Driver Register
 */
class DriverRegistrationControllerBCK extends LoginController
{
    protected $user;
    protected $driver;
    protected $otpHandler;
    protected $country;
    protected $database;



    public function __construct(User $user, Driver $driver, Country $country, OTPHandlerContract $otpHandler, Database $database)
    {
        $this->user = $user;
        $this->driver = $driver;
        $this->otpHandler = $otpHandler;
        $this->country = $country;
        $this->database = $database;
    }

    /**
    * Register the driver and send welcome email.
    * @bodyParam name string required name of the driver
    * @bodyParam company_key string optional company key of demo
    * @bodyParam mobile integer required mobile of driver
    * @bodyParam email email required email of the driver
    * @bodyParam password password required password provided by driver
    * @bodyParam password_confirmation password required  confirmed password provided driver
    * @bodyParam uuid string required from validate otp response
    * @bodyParam device_token string required device_token of the driver
    * @bodyParam terms_condition boolean required it should be 0 or 1
    * @bodyParam service_location_id uuid required service location of the driver. it can be listed from service location list apis
     * @bodyParam refferal_code string optional refferal_code of the another driver
    * @bodyParam login_by tinyInt required from which device the driver registered
    * @bodyParam is_company_driver tinyInt required value can be 0 or 1.
    * @bodyParam vehicle_type uuid required vehicle types. listed by types api
    * @bodyParam car_make string required car make of the driver
    * @bodyParam car_model string required car model of the driver
    * @bodyParam car_number string required car number of the driver
    *
    * @param \App\Http\Requests\Auth\Registration\UserRegistrationRequest $request
    * @return \Illuminate\Http\JsonResponse
    * @responseFile responses/auth/register.json
    */

    public function register(DriverRegistrationRequest $request)
    {
        $mobileUuid = $request->input('uuid');


        $created_params = $request->only(['service_location_id', 'name','mobile','email','address','state','city','country','gender','vehicle_type','car_make','car_model','car_color','car_number']);

        $created_params['postal_code'] = $request->postal_code;

        if ($request->input('service_location_id')) {
            $timezone = ServiceLocation::where('id', $request->input('service_location_id'))->pluck('timezone')->first();
        } else {
            $timezone = env('SYSTEM_DEFAULT_TIMEZONE');
        }

        $country_code = $this->country->where('dial_code', $request->input('country'))->exists();

        $validate_exists_email = $this->user->belongsTorole(Role::DRIVER)->where('email', $request->email)->exists();

        if ($validate_exists_email) {
            $this->throwCustomException('Provided email has already been taken');
        }

        // $mobile = $this->otpHandler->getMobileFromUuid($mobileUuid);
        $mobile = $request->mobile;

        $validate_exists_mobile = $this->user->belongsTorole(Role::DRIVER)->where('mobile', $mobile)->exists();

        if ($validate_exists_mobile) {
            $this->throwCustomException('Provided mobile has already been taken');
        }
        if ($request->has('refferal_code')) {
            // Validate Referral code
            $referred_user_record = $this->user->belongsTorole(Role::DRIVER)->where('refferal_code', $request->refferal_code)->first();
            if (!$referred_user_record) {
                $this->throwCustomException('Provided Referral code is not valid', 'refferal_code');
            }
            // Add referral commission to the referred user
            $referred_user_record = $referred_user_record->driver;
            $this->addCommissionToRefferedUser($referred_user_record);
        }

        if (!$country_code) {
            $this->throwCustomException('unable to find country');
        }
        $country_id = $this->country->where('dial_code', $request->input('country'))->pluck('id')->first();

        $created_params['country'] = $country_id;

        $profile_picture = null;

        if ($uploadedFile = $this->getValidatedUpload('profile', $request)) {
            $profile_picture = $this->imageUploader->file($uploadedFile)
                ->saveDriverProfilePicture();
        }
        // DB::beginTransaction();
        // try {
        // $mobile = $this->otpHandler->getMobileFromUuid($mobileUuid);
        $data = [
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password')),
            'mobile' => $mobile,
            'mobile_confirmed' => true,
            'fcm_token'=>$request->input('device_token'),
            'login_by'=>$request->input('login_by'),
            'timezone'=>$timezone,
            'country'=>$country_id,
            'profile_picture'=>$profile_picture,
            'refferal_code'=>str_random(6),
        ];
        // DB::enableQueryLog();
        if (env('APP_FOR')=='demo' && $request->has('company_key') && $request->input('company_key')) {
            $data['company_key'] = $request->input('company_key');
        }
        $user = $this->user->create($data);
        // dd($user);
        // dd(DB::getQueryLog());
        $created_params['mobile'] = $mobile;
        $created_params['uuid'] = driver_uuid();
        $created_params['active'] = false; //@TODO need to remove in future
            $driver = $user->driver()->create($created_params); //Create drivers table data

            // $database = $this->database->getReference('drivers')->getValue();
        // // Store records to firebase
        $this->database->getReference('drivers/'.$driver->id)->set(['id'=>$driver->id,'vehicle_type'=>$request->input('vehicle_type'),'active'=>1,'updated_at'=> Database::SERVER_TIMESTAMP]);

        $driver_detail_data = $request->only(['is_company_driver','company']);
        $driver_detail = $driver->driverDetail()->create($driver_detail_data);//create driver details table data

        // Create Empty Wallet to the driver
        $driver_wallet = $driver->driverWallet()->create(['amount_added'=>0]);

        // $this->otpHandler->delete($mobileUuid);
        $user->attachRole(Role::DRIVER);
        $this->dispatch(new UserRegistrationNotification($user));
        event(new UserRegistered($user));
        // } catch (\Exception $e) {
        //     DB::rollBack();
        //     Log::error($e);
        //     Log::error('Error while Registering a driver account. Input params : ' . json_encode($request->all()));
        //     return $this->respondBadRequest('Unknown error occurred. Please try again later or contact us if it continues.');
        // }
        // DB::commit();
        return $this->authenticateAndRespond($user, $request, $needsToken=true);
    }

    /**
    * Validate Mobile-For-Driver
    * @bodyParam mobile integer required mobile of driver
     * @response {
     * "success":true,
     * "message":"mobile_validated",
     * }
    *
    */
    public function validateDriverMobile(Request $request)
    {
        $mobile = $request->mobile;

        $validate_exists_mobile = $this->user->belongsTorole(Role::DRIVER)->where('mobile', $mobile)->exists();


        if($request->has('email')){

            $validate_exists_email = $this->user->belongsTorole(Role::DRIVER)->where('email', $request->email)->exists();

        if ($validate_exists_email) {
            $this->throwCustomException('Provided email has already been taken');
        }
        
        }
        


        if ($validate_exists_mobile) {
            $this->throwCustomException('Provided mobile has already been taken');
        }

        return $this->respondSuccess(null, 'mobile_validated');
    }
   
    /**
    * Validate Mobile-For-Driver-For-Login
    * @bodyParam mobile integer required mobile of driver
     * @response {
     * "success":true,
     * "message":"mobile_exists",
     * }
     */
    public function validateDriverMobileForLogin(Request $request)
    {
        $mobile = $request->mobile;

        $validate_exists_mobile = $this->user->belongsTorole(Role::DRIVER)->where('mobile', $mobile)->exists();

        if ($validate_exists_mobile) {
            return $this->respondSuccess(null, 'mobile_exists');
        }

        return $this->respondFailed('mobile_does_not_exists');
    }


    /**
    * Add Commission to the referred driver
    *
    */
    public function addCommissionToRefferedUser($reffered_user)
    {
        $driver_wallet = $reffered_user->driverWallet;
        $referral_commision = get_settings('referral_commision_for_driver')?:0;

        $driver_wallet->amount_added += $referral_commision;
        $driver_wallet->amount_balance += $referral_commision;
        $driver_wallet->save();

        // Add the history
        $reffered_user->driverWalletHistory()->create([
            'amount'=>$referral_commision,
            'transaction_id'=>str_random(6),
            'remarks'=>WalletRemarks::REFERRAL_COMMISION,
            'refferal_code'=>$reffered_user->refferal_code,
            'is_credit'=>true]);

        // Notify user
        $title = trans('push_notifications.referral_earnings_notify_title');
        $body = trans('push_notifications.referral_earnings_notify_body');

        $reffered_user->user->notify(new AndroidPushNotification($title, $body));
    }
}
