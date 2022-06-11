<?php

namespace App\Http\Controllers\Web\User;

use Socialite;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Admin\Driver;
use App\Models\Admin\Zone;
use App\Models\Admin\DriverAvailability;
use App\SocialAccountService;
use App\Charts\TodayTripChart;
use App\Models\Request\Request;
use App\Charts\OverallTripChart;
use App\Base\Constants\Auth\Role;
use App\Models\Request\RequestBill;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Web\BaseController;
use App\Models\Payment\DriverWallet;
use Kreait\Firebase\Database;

class WebUserControllerController extends BaseController
{

     public function __construct(Database $database)
    {
        $this->database = $database;
    }

    /**
     * Redirect to admin login
     */
    public function viewLogin()
    {
        
        return view('web-user.login');
    }

    
}
