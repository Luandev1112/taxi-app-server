<?php

namespace App\Http\Controllers\Api\V1\Payment\Stripe;

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
use App\Models\Payment\UserWallet;
use App\Models\Payment\DriverWallet;
use App\Base\Constants\Masters\WalletRemarks;


/**
 * @group Stripe Payment Gateway
 *
 * Payment-Related Apis
 */
class StripeController extends ApiController
{


    
     /**
     * Setup a client secret
     * @response {
    "success": true,
    "success_message": "Client_token",
    "client_token": "seti_1IudOHSBCHfacuRq1epLsfPl_secret_JXipcwKp89e20po0gexe23CyoZIiDCp"
    }
     *
     */
    public function createStripeIntent(Request $request){

        if(env('STRIPE_ENVIRONMENT')=='sandbox'){

        $test_environment = true;

        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

        }else{
        \Stripe\Stripe::setApiKey(env('STRIPE_LIVE_KEY'));

        $test_environment = false;
            
        }

        $user = auth()->user();

        $create_customer_data = [
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->mobile,
                'address' => [
                'line1' => '510 Townsend St',
                'postal_code' => '98140',
                'city' => 'San Francisco',
                'state' => 'CA',
                'country' => 'US',
                ],
                // 'payment_method' => $request->payment_nonce,
                // 'source' => "tok_visa"
            ];

        $customer = \Stripe\Customer::create($create_customer_data);


            $user_currency_code = auth()->user()->countryDetail->currency_code?:env('SYSTEM_DEFAULT_CURRENCY');

            $setup_intent = \Stripe\PaymentIntent::create([
                'amount' => $request->amount *100,
                'currency'=> strtolower($user_currency_code),
                'description' => 'Add Money To Wallet',
                'shipping' => [
      'name' => $user->name,
      'address' => [
        'line1' => '510 Townsend St',
        'postal_code' => '98140',
        'city' => 'San Francisco',
        'state' => 'CA',
        'country' => 'US',
      ],
    ],
            ]);
        
        // $setup_intent = SetupIntent::create();
        $obj = new \stdClass;
        $obj->message = "Client_token";
        $obj->token = $setup_intent->client_secret;


        return $this->respondSuccess([
                "client_token" => $obj->token,
                "customer_id"=>$customer->id,
                "test_environment"=>$test_environment,
            ],'stripe_key_listed_success');

    }
    /**
    * Add money to wallet
    * @bodyParam amount double required  amount entered by user
    * @bodyParam payment_id string required  payment_id from transaction
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
        
        $user_currency_code = auth()->user()->countryDetail->currency_code?:env('SYSTEM_DEFAULT_CURRENCY');

        // Convert the amount to USD to any currency
        $converted_amount_array =  convert_currency_to_usd($user_currency_code, $request->input('amount'));

        $converted_amount = $converted_amount_array['converted_amount'];
        $converted_type = $converted_amount_array['converted_type'];

        $conversion = $converted_type.':'.$request->amount.'-'.$converted_amount;
        $transaction_id = $request->payment_id;

            if (access()->hasRole('user')) {
            $wallet_model = new UserWallet();
            $wallet_add_history_model = new UserWalletHistory();
            $user_id = auth()->user()->id;
        } else {
            $wallet_model = new DriverWallet();
            $wallet_add_history_model = new DriverWalletHistory();
            $user_id = auth()->user()->driver->id;
        }

        $user_wallet = $wallet_model::firstOrCreate([
            'user_id'=>$user_id]);
        $user_wallet->amount_added += $request->amount;
        $user_wallet->amount_balance += $request->amount;
        $user_wallet->save();
        $user_wallet->fresh();

        $wallet_add_history_model::create([
            'user_id'=>$user_id,
            'amount'=>$request->amount,
            'transaction_id'=>$transaction_id,
            'conversion'=>$conversion,
            'remarks'=>WalletRemarks::MONEY_DEPOSITED_TO_E_WALLET,
            'is_credit'=>true]);


        if (access()->hasRole(Role::USER)) {
            $result =  fractal($user_wallet, new WalletTransformer);
        } else {
            $result =  fractal($user_wallet, new DriverWalletTransformer);
        }

        return $this->respondSuccess($result, 'money_added_successfully');
    }

    
}
