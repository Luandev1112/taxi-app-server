<?php

namespace App\Http\Controllers\Web\Admin;

use App\Models\User;
use App\Exports\UsersExport;
use App\Models\Admin\Driver;
use Illuminate\Http\Request;
use App\Exports\DriverExport;
use App\Exports\TravelExport;
use App\Base\Constants\Auth\Role;
use App\Models\Admin\VehicleType;
use Illuminate\Support\Facades\DB;
use App\Exports\DriverDutiesExport;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Base\Filters\Admin\UserFilter;
use App\Base\Filters\Admin\DriverFilter;
use App\Base\Filters\Admin\RequestFilter;
use App\Base\Constants\Masters\DateOptions;
use App\Base\Filters\Master\CommonMasterFilter;
use App\Models\Request\Request as RequestRequest;
use App\Base\Libraries\QueryFilter\QueryFilterContract;
use Carbon\Carbon;

class ReportController extends Controller
{
    protected $format = ['xlsx','xls','csv','pdf'];

    public function userReport()
    {
        $page = trans('pages_names.user_report');

        $main_menu = 'reports';
        $sub_menu = 'user_report';
        $formats = $this->format;

        return view('admin.reports.user_report', compact('page', 'main_menu', 'sub_menu', 'formats'));
    }

    public function driverReport()
    {
        $page = trans('pages_names.driver_report');

        $main_menu = 'reports';
        $sub_menu = 'driver_report';
        $formats = $this->format;
        $vehicletype = VehicleType::active()->get();

        return view('admin.reports.driver_report', compact('page', 'main_menu', 'sub_menu', 'formats', 'vehicletype'));
    }
    public function driverDutiesReport()
    {
        $page = trans('pages_names.driver_duties_report');
        $main_menu = 'reports';
        $sub_menu = 'driver_duties_report';
        $formats = $this->format;
        $drivers = Driver::get();

        return view('admin.reports.driver_duties', compact('page', 'main_menu', 'sub_menu', 'formats', 'drivers'));
    }

    public function travelReport()
    {
        $page = trans('pages_names.travel_report');

        $main_menu = 'reports';
        $sub_menu = 'travel_report';
        $formats = $this->format;
        $vehicletype = VehicleType::active()->get();

        return view('admin.reports.travel_report', compact('page', 'main_menu', 'sub_menu', 'formats', 'vehicletype'));
    }

    public function downloadReport(Request $request, QueryFilterContract $queryFilter)
    {
        $method = "download".$request->model."Report";

        $filename = $this->$method($request, $queryFilter);

        $file = url('storage/'.$filename);

        return $file;
    }

    public function downloadUserReport(Request $request, QueryFilterContract $queryFilter)
    {
        $format = $request->format;

        $query = User::companyKey()->belongsToRole(Role::USER);

        $data = $queryFilter->builder($query)->customFilter(new UserFilter)->defaultSort('-date')->get();

        $filename = "$request->model Report-".date('ymdis').'.'.$format;

        Excel::store(new UsersExport($data), $filename, 'local');

        return $filename;
    }

    public function downloadDriverReport(Request $request, QueryFilterContract $queryFilter)
    {
        $format = $request->format;

        $query = Driver::query();
        if (env('APP_FOR')=='demo') {
            $query = Driver::whereHas('user', function ($query) {
                $query->where('company_key', auth()->user()->company_key);
            });
        }

        $data = $queryFilter->builder($query)->customFilter(new DriverFilter)->defaultSort('-date')->get();

        $filename = "$request->model Report-".date('ymdis').'.'.$format;

        Excel::store(new DriverExport($data), $filename, 'local');

        return $filename;
    }

    /**
    * Download Driver Duties Report
    *
    */
    public function downloadDriverDutiesReport(Request $request)
    {
        $format = $request->format;
        $date_option = $request->date_option;
        $current_date = Carbon::now();
        $driver = $request->driver;

        if ($date_option == DateOptions::TODAY) {
            $date_array = [$current_date->format("Y-m-d"),$current_date->format("Y-m-d"),$driver];
        } elseif ($date_option == DateOptions::YESTERDAY) {
            $yesterday_date = Carbon::yesterday()->format('Y-m-d');
            $date_array = [$yesterday_date,$yesterday_date,$driver];
        } elseif ($date_option == DateOptions::CURRENT_WEEK) {
            $date_array = [$current_date->startOfWeek()->toDateString(),$current_date->endOfWeek()->toDateString(),$driver];
        } elseif ($date_option == DateOptions::LAST_WEEK) {
            $date_array = [$current_date->subWeek()->toDateString(), $current_date->startOfWeek()->toDateString(),$driver];
        } elseif ($date_option == DateOptions::CURRENT_MONTH) {
            $date_array = [$current_date->startOfMonth()->toDateString(), $current_date->endOfMonth()->toDateString(),$driver];
        } elseif ($date_option == DateOptions::PREVIOUS_MONTH) {
            $date_array = [$current_date->startOfMonth()->toDateString(), $current_date->endOfMonth()->toDateString(),$driver];
        } elseif ($date_option == DateOptions::CURRENT_YEAR) {
            $date_array = [$current_date->startOfYear()->toDateString(), $current_date->endOfYear()->toDateString(),$driver];
        } else {
            $date_array = [];
        }

        // $date_array =['2020-11-11','2020-11-20',6];

        $data = DB::select('CALL get_driver_duration_report(?,?,?)', $date_array);
        if (count($data)==1) {
            $data = (object) array();
        }
        $filename = "$request->model Report-".date('ymdis').'.'.$format;

        Excel::store(new DriverDutiesExport($data), $filename, 'local');

        return $filename;

        // dd($record);
    }

    public function downloadTravelReport(Request $request, QueryFilterContract $queryFilter)
    {
        $format = $request->format;

        $query = RequestRequest::companyKey();

        $data = $queryFilter->builder($query)->customFilter(new RequestFilter)->defaultSort('created_at')->get();

        $filename = "$request->model Report-".date('ymdis').'.'.$format;

        Excel::store(new TravelExport($data), $filename, 'local');

        return $filename;
    }
}
