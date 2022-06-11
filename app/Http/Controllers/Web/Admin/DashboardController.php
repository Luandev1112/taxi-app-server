<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Web\BaseController;
use App\Models\Admin\Driver;
use App\Models\Request\Request;
use App\Models\Request\RequestBill;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;

class DashboardController extends BaseController
{

    public function dashboard()
    {
        Session::put('applocale', 'en');
        
        $ownerId = null;
        if (auth()->user()->hasRole('owner')) {
            $ownerId = auth()->user()->owner->id;
        }

        $page = trans('pages_names.dashboard');
        $main_menu = 'dashboard';
        $sub_menu = null;

        $today = date('Y-m-d');
    
        $total_drivers = Driver::selectRaw('
                                        IFNULL(SUM(CASE WHEN approve=1 THEN 1 ELSE 0 END),0) AS approved,
                                        IFNULL((SUM(CASE WHEN approve=1 THEN 1 ELSE 0 END) / count(*)),0) * 100 AS approve_percentage,
                                        IFNULL((SUM(CASE WHEN approve=0 THEN 1 ELSE 0 END) / count(*)),0) * 100 AS decline_percentage,
                                        IFNULL(SUM(CASE WHEN approve=0 THEN 1 ELSE 0 END),0) AS declined,
                                        count(*) AS total
                                    ')
                                ->whereHas('user', function ($query) {
                                    $query->companyKey();
                                });

        if($ownerId != null){
            $total_drivers = $total_drivers->whereOwnerId($ownerId);
        }
        
        $total_drivers = $total_drivers->get();

        $total_users = User::belongsToRole('user')->companyKey()->count();

        $trips = Request::companyKey()->selectRaw('
                    IFNULL(SUM(CASE WHEN is_completed=1 THEN 1 ELSE 0 END),0) AS today_completed,
                    IFNULL(SUM(CASE WHEN is_cancelled=1 THEN 1 ELSE 0 END),0) AS today_cancelled,
                    IFNULL(SUM(CASE WHEN is_completed=0 AND is_cancelled=0 THEN 1 ELSE 0 END),0) AS today_scheduled,
                    IFNULL(SUM(CASE WHEN is_cancelled=1 AND cancel_method=0 THEN 1 ELSE 0 END),0) AS auto_cancelled,
                    IFNULL(SUM(CASE WHEN is_cancelled=1 AND cancel_method=1 THEN 1 ELSE 0 END),0) AS user_cancelled,
                    IFNULL(SUM(CASE WHEN is_cancelled=1 AND cancel_method=2 THEN 1 ELSE 0 END),0) AS driver_cancelled,
                    (IFNULL(SUM(CASE WHEN is_cancelled=1 AND cancel_method=0 THEN 1 ELSE 0 END),0) +
                    IFNULL(SUM(CASE WHEN is_cancelled=1 AND cancel_method=1 THEN 1 ELSE 0 END),0) +
                    IFNULL(SUM(CASE WHEN is_cancelled=1 AND cancel_method=2 THEN 1 ELSE 0 END),0)) AS total_cancelled
                ')
                ->whereDate('trip_start_time',$today)
                ->get();

        $cardEarningsQuery = "IFNULL(SUM(IF(requests.payment_opt=0,request_bills.total_amount,0)),0)";
        $cashEarningsQuery = "IFNULL(SUM(IF(requests.payment_opt=1,request_bills.total_amount,0)),0)";
        $walletEarningsQuery = "IFNULL(SUM(IF(requests.payment_opt=2,request_bills.total_amount,0)),0)";
        $adminCommissionQuery = "IFNULL(SUM(request_bills.admin_commision_with_tax),0)";
        $driverCommissionQuery = "IFNULL(SUM(request_bills.driver_commision),0)";
        $totalEarningsQuery = "$cardEarningsQuery + $cashEarningsQuery + $walletEarningsQuery";
        
        // Today earnings
        $todayEarnings = Request::leftJoin('request_bills','requests.id','request_bills.request_id')
                                        ->selectRaw("
                                        {$cardEarningsQuery} AS card,
                                        {$cashEarningsQuery} AS cash,
                                        {$walletEarningsQuery} AS wallet,
                                        {$totalEarningsQuery} AS total,
                                        {$adminCommissionQuery} as admin_commision,
                                        {$driverCommissionQuery} as driver_commision,
                                        IFNULL(({$cardEarningsQuery} / {$totalEarningsQuery}),0) * 100 AS card_percentage,
                                        IFNULL(({$cashEarningsQuery} / {$totalEarningsQuery}),0) * 100 AS cash_percentage,
                                        IFNULL(({$walletEarningsQuery} / {$totalEarningsQuery}),0) * 100 AS wallet_percentage
                                    ")
                                    ->companyKey()
                                    ->where('requests.is_completed',true)
                                    ->whereDate('requests.trip_start_time',date('Y-m-d'))
                                    ->get();

        //Overall Earnings
        $overallEarnings = Request::leftJoin('request_bills','requests.id','request_bills.request_id')
                                    ->selectRaw("
                                    {$cardEarningsQuery} AS card,
                                    {$cashEarningsQuery} AS cash,
                                    {$walletEarningsQuery} AS wallet,
                                    {$totalEarningsQuery} AS total,
                                    {$adminCommissionQuery} as admin_commision,
                                    {$driverCommissionQuery} as driver_commision,
                                    IFNULL(({$cardEarningsQuery} / {$totalEarningsQuery}),0) * 100 AS card_percentage,
                                    IFNULL(({$cashEarningsQuery} / {$totalEarningsQuery}),0) * 100 AS cash_percentage,
                                    IFNULL(({$walletEarningsQuery} / {$totalEarningsQuery}),0) * 100 AS wallet_percentage
                                ")
                                ->companyKey()
                                ->where('requests.is_completed',true)
                                ->get();

        //cancellation chart
        $to = Carbon::now()->month; //Get current month
        $from = Carbon::now()->subMonths(5)->month;
        $data=[];
        foreach (range($from, $to) as $month) {
            $shortName = Carbon::now()->month($month)->shortEnglishMonth;
            $monthName = Carbon::now()->month($month)->monthName;
            $data['cancel'][$month]['y'] = $shortName;
            $data['cancel'][$month]['a'] = Request::companyKey()->whereMonth('created_at', $month)->where('cancel_method','0')->whereIsCancelled(true)->count();
            $data['cancel'][$month]['u'] = Request::companyKey()->whereMonth('created_at', $month)->where('cancel_method','1')->whereIsCancelled(true)->count();
            $data['cancel'][$month]['d'] = Request::companyKey()->whereMonth('created_at', $month)->where('cancel_method','2')->whereIsCancelled(true)->count();

            $data['earnings']['months'][] = $monthName;
            $data['earnings']['values'][] = RequestBill::whereHas('requestDetail', function ($query) use ($month) {
                                                        $query->companyKey()->whereMonth('trip_start_time', $month)->whereIsCompleted(true);
                                                    })->sum('total_amount');
        }

        // $currency = auth()->user()->countryDetail->currency_code ?: env('SYSTEM_DEFAULT_CURRENCY');
        if (auth()->user()->countryDetail) {
            $currency = auth()->user()->countryDetail->currency_symbol;
        } else {
            $currency = env('SYSTEM_DEFAULT_CURRENCY');
        }

        return view('admin.dashboard', compact('page', 'main_menu','currency', 'sub_menu','total_drivers','total_users','trips','todayEarnings','overallEarnings','data'));
    }
}