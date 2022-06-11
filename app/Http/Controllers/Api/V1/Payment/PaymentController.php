<?php

namespace App\Http\Controllers\Api\V1\Payment;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Payment\CardInfo;
use App\Base\Constants\Auth\Role;
use App\Http\Controllers\ApiController;
use App\Models\Payment\UserWalletHistory;
use App\Models\Payment\DriverWalletHistory;
use App\Transformers\Payment\WalletTransformer;
use App\Base\Payment\BrainTreeTasks\BraintreeTask;
use App\Transformers\Payment\DriverWalletTransformer;
use App\Http\Requests\Payment\AddMoneyToWalletRequest;
use App\Transformers\Payment\UserWalletHistoryTransformer;
use App\Transformers\Payment\DriverWalletHistoryTransformer;
use App\Transformers\Payment\WalletWithdrawalRequestsTransformer;
use App\Models\Payment\WalletWithdrawalRequest;
use App\Http\Controllers\Api\V1\BaseController;
use App\Base\Constants\Masters\WithdrawalRequestStatus;

/**
 * @group Payment
 *
 * Payment-Related Apis
 */
class PaymentController extends BaseController
{
    protected $gateway;

    protected $callable_gateway_class;


    public function __construct()
    {
        $this->gateway = env('PAYMENT_GATEWAY');
        $this->callable_gateway_class = app(config('base.payment_gateway.'.$this->gateway.'.class'));
    }

    /**
    * User-Add Card
    * @bodyParam payment_nonce string required  Payment nonce fron entered value
    * @return \Illuminate\Http\JsonResponse
    * @response {
    *"success": true,
    *"message": "card_added_succesfully",
    *"data": [
    *{
    *"id": "33f6a61d-4ddc-47dc-a601-250672dbc405",
    *"customer_id": "customer_765_6",
    *"merchant_id": "pwc2hd46g93s4zy2",
    *"card_token": "79dhmq",
    *"last_number": 521,
    *"card_type": "VISA",
    *"valid_through":"12/2021",
    *"user_id": 6,
    *"is_default": 0,
    *"user_role": "driver",
    *"created_at": "2019-05-06 13:17:40",
    *"updated_at": "2019-05-06 13:17:40",
    *"deleted_at": null
    *}
    *]
    *}
    */
    public function addCard(Request $request)
    {
        $result =  $this->callable_gateway_class->addCard($request);

        return $this->respondSuccess($result, 'card_added_succesfully');
    }

    /**
    * List cards
    * @return \Illuminate\Http\JsonResponse
    * @response {
    "success": true,
    "message": "card_listed_succesfully",
    "data": [
        {
            "id": "33f6a61d-4ddc-47dc-a601-250672dbc405",
            "customer_id": "customer_765_6",
            "merchant_id": "pwc2hd46g93s4zy2",
            "card_token": "79dhmq",
            "last_number": 521,
            "card_type": "VISA",
            "user_id": 6,
            "is_default": 0,
            "user_role": "driver",
            "valid_through":"12/2021",
            "created_at": "2019-05-06 13:17:40",
            "updated_at": "2019-05-06 13:17:40",
            "deleted_at": null
        }
    ]
}
    */
    public function listCards()
    {
        $result =  $this->callable_gateway_class->listCards();

        return $this->respondSuccess($result, 'card_listed_succesfully');
    }

    /**
    * Make card as default card
    * @bodyParam card_id uuid required card id choosen by user
    * @response {
    * "success": true,
    * "message": "card_made_default_succesfully"
    }
    */
    public function makeDefaultCard(Request $request)
    {
        $this->callable_gateway_class->makeDefaultCard($request);

        return $this->respondSuccess($data=null, 'card_made_default_succesfully');
    }


    /**
    * Delete Card
    * @response {
    * "success": true,
    * "message": "card_deleted_succesfully"
    * }
    */
    public function deleteCard(CardInfo $card)
    {
        $this->callable_gateway_class->deleteCard($card);

        return $this->respondSuccess($data=null, 'card_deleted_succesfully');
    }

    /**
    * Get Client token for brain tree
    * @response {
    "success": true,
    "message": "success",
    "data": {
        "client_token": "eyJ2ZXJzaW9uIjoyLCJhdXRob3JpemF0aW9uRmluZ2VycHJpbnQiOiJleUowZVhBaU9pSktWMVFpTENKaGJHY2lPaUpGVXpJMU5pSXNJbXRwWkNJNklqSXdNVGd3TkRJMk1UWXRjMkZ1WkdKdmVDSXNJbWx6Y3lJNklrRjFkR2g1SW4wLmV5SmxlSEFpT2pFMU9URTBOalE1TnpZc0ltcDBhU0k2SWpGbFpUZG1aREExTFRJNU1qRXRORGt4TWkxaFlXSmpMVEJtTTJVMVpUVXlPVEkzWVNJc0luTjFZaUk2SW5CM1l6Sm9aRFEyWnpremN6UjZlVElpTENKcGMzTWlPaUpCZFhSb2VTSXNJb"
    }
}
    */
    public function getClientToken()
    {
        $braintree_object = new BraintreeTask();
        $gateway = $braintree_object->run();
        $client_token = $gateway->clientToken()->generate();

        return $this->respondSuccess(['client_token'=>$client_token]);
    }

    /**
    * Add money to wallet
    * @bodyParam amount double required  amount entered by user
    * @bodyParam card_id uuid required  card_id choosed by user
    * @response {
    "success": true,
    "message": "money_added_successfully",
    "data": {
        "id": "1195a787-ba13-4a74-b56c-c48ba4ca0ca0",
        "user_id": 15,
        "amount_added": 2500,
        "amount_balance": 2500,
        "amount_spent": 0,
        "currency_code": "INR",
        "created_at": "1st Sep 10:45 PM",
        "updated_at": "1st Sep 10:51 PM"
    }
}
    */
    public function addMoneyToWallet(AddMoneyToWalletRequest $request)
    {
        $user_wallet = $this->callable_gateway_class->addMoneyToWallet($request);

        if (access()->hasRole(Role::USER)) {
            $result =  fractal($user_wallet, new WalletTransformer);
        } else {
            $result =  fractal($user_wallet, new DriverWalletTransformer);
        }

        return $this->respondSuccess($result, 'money_added_successfully');
    }

    /**
    * Wallet history
    * @responseFile responses/payment/wallet_added_history.json
    */
    public function walletHistory()
    {
        if (access()->hasRole(Role::USER)) {
            $query = UserWalletHistory::where('user_id', auth()->user()->id);
            // $result = fractal($query, new UserWalletHistoryTransformer);
            $result = filter($query, new UserWalletHistoryTransformer)->defaultSort('-created_at')->paginate();

            $user_wallet = auth()->user()->userWallet;

            $wallet_balance= $user_wallet->amount_balance;
            $currency_code = auth()->user()->countryDetail->currency_code;
            $currency_symbol = auth()->user()->countryDetail->currency_symbol;
            $default_card = CardInfo::where('user_id', auth()->user()->id)->where('is_default', true)->first();
            $default_card_id = null;
            if ($default_card) {
                $default_card_id = $default_card->id;
            }

        } else {
            $query = DriverWalletHistory::where('user_id', auth()->user()->driver->id)->orderBy('created_at', 'desc');
            $result = filter($query, new DriverWalletHistoryTransformer)->defaultSort('-created_at')->paginate();

            $driver_wallet = auth()->user()->driver->driverWallet;

            $wallet_balance= $driver_wallet->amount_balance;
            $currency_code = auth()->user()->countryDetail->currency_code;
            $currency_symbol = auth()->user()->countryDetail->currency_symbol;

            $default_card = CardInfo::where('user_id', auth()->user()->id)->where('is_default', true)->first();
            $default_card_id = null;
            if ($default_card) {
                $default_card_id = $default_card->id;
            }
        }

        $bank_info_exists = false;

        if(auth()->user()->bankInfo()->exists()){

            $bank_info_exists = true;
        }

        return response()->json(['success'=>true,'message'=>'wallet_history_listed','wallet_balance'=>$wallet_balance,'default_card_id'=>$default_card_id,'currency_code'=>$currency_code,'currency_symbol'=>$currency_symbol,'wallet_history'=>$result,'braintree_tree'=>true,'stripe'=>true,'razer_pay'=>false,'paystack'=>false,'cash_free'=>false,'bank_info_exists'=>$bank_info_exists]);

        // return $this->respondSuccess($result, 'wallet_history_listed');
    }

    /**
     * Wallet Withdrawal Requests LIst
     * 
     * */
    public function withDrawalRequests()
    {   
        if(access()->hasRole(Role::USER)){
            
        $user = auth()->user();

        $query = WalletWithdrawalRequest::where('user_id',$user->id);

        $result = filter($query, new WalletWithdrawalRequestsTransformer)->defaultSort('-created_at')->paginate();

        // $result = fractal($query, new WalletWithdrawalRequestsTransformer);

        $user_wallet = auth()->user()->userWallet;
        $wallet_balance= $user_wallet->amount_balance;


        }else{
            $user = auth()->user()->driver;

            $query = WalletWithdrawalRequest::where('driver_id',$user->id);

            $result = filter($query, new WalletWithdrawalRequestsTransformer)->defaultSort('-created_at')->paginate();


            // $result = fractal($query, new WalletWithdrawalRequestsTransformer);

            $driver_wallet = auth()->user()->driver->driverWallet;

            $wallet_balance= $driver_wallet->amount_balance;

        }

        return response()->json(['success'=>true,'message'=>'withdrawal-requests-listed','withdrawal_history'=>$result,'wallet_balance'=>$wallet_balance]);

    }


    /**
     * Request for withdrawal
     * @bodyParam requested_amount double required  amount entered by user 
     * 
     * 
     * */
    public function requestForWithdrawal(Request $request){

        $created_params = $request->all();

        if(access()->hasRole(Role::USER)){

            $user_info = auth()->user();

            $currency_code = auth()->user()->countryDetail?auth()->user()->countryDetail->currency_symbol:env('SYSTEM_DEFAULT_CURRENCY');

            $created_params['requested_currency'] = $currency_code;
            $created_params['user_id'] = auth()->user()->id;

            $user_wallet = auth()->user()->userWallet;
            $wallet_balance= $user_wallet->amount_balance;

            if($wallet_balance < $request->requested_amount){

                $this->throwCustomException('Yout wallet balance is too low than your requested amount');

            }

        }else{
            
            $user_info = auth()->user()->driver;

            $currency_code = auth()->user()->driver->serviceLocation->currency_symbol;

            $created_params['requested_currency'] = $currency_code;
            $created_params['driver_id'] = auth()->user()->driver->id;

            $driver_wallet = auth()->user()->driver->driverWallet;

            $wallet_balance= $driver_wallet->amount_balance;

             if($wallet_balance < $request->requested_amount){

                $this->throwCustomException('Yout wallet balance is too low than your requested amount');

            }

            // $user_info->withdrawalRequestsHistory()->where('status',WithdrawalRequestStatus::REQUESTED)->exists();

            $exists_request = WalletWithdrawalRequest::where('driver_id',$user_info->id)->where('status',0)->exists();
            
            if($exists_request){
                $this->throwCustomException('You cannot make multiple request. please wait for your existing request approval');
            }

        }

        

        WalletWithdrawalRequest::create($created_params);

        return $this->respondSuccess(null, 'wallet_withdrawal_requested');


    }
}
