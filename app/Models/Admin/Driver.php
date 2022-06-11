<?php

namespace App\Models\Admin;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Master\CarMake;
use App\Models\Master\CarModel;
use App\Models\Request\Request;
use App\Models\Traits\HasActive;
use App\Models\Payment\DriverWallet;
use Illuminate\Database\Eloquent\Model;
use App\Models\Admin\DriverAvailability;
use App\Models\Payment\DriverWalletHistory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Nicolaslopezj\Searchable\SearchableTrait;
use App\Models\Payment\WalletWithdrawalRequest;

class Driver extends Model
{
    use HasActive,SoftDeletes,SearchableTrait;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'drivers';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id','owner_id','service_location_id', 'name','mobile','email','address','state','city','country','postal_code','gender','vehicle_type','car_make','car_model','car_color','car_number','today_trip_count','total_accept','total_reject','acceptance_ratio','last_trip_date','active','approve','available','reason','uuid','fleet_id'
    ];
    /**
    * The accessors to append to the model's array form.
    *
    * @var array
    */
    protected $appends = [
        'profile_picture','vehicle_type_name','car_make_name','car_model_name','rating','no_of_ratings','timezone'
    ];


    /**
     * The relationships that can be loaded with query string filtering includes.
     *
     * @var array
     */
    public $includes = [
        'driverDetail','requestDetail'
    ];

    /**
     * Searchable rules.
     *
     * @var array
     */
    protected $searchable = [
        /**
         * Columns and their priority in search results.
         * Columns with higher values are more important.
         * Columns with equal values have equal importance.
         *
         * @var array
         */
        'columns' => [
            'drivers.name' => 20,
            'drivers.email' => 20,
            'drivers.mobile' => 20,
        ],

    ];

    /**
    * Get the Profile image full file path.
    *
    * @param string $value
    * @return string
    */
    public function getProfilePictureAttribute()
    {
        return $this->user->profile_picture;
    }

    public function getTimezoneAttribute()
    {
        return $this->user->timezone?:env('SYSTEM_DEFAULT_TIMEZONE');
    }

    public function getVehicleTypeNameAttribute()
    {
        return $this->vehicleType?$this->vehicleType->name:null;
    }
    public function getCarMakeNameAttribute()
    {
        return $this->carMake?$this->carMake->name:null;
    }
    public function getCarModelNameAttribute()
    {
        return $this->carModel?$this->carModel->name:null;
    }
    public function getRatingAttribute()
    {
        return $this->user->rating;
    }
    public function getNoOfRatingsAttribute()
    {
        return $this->user->no_of_ratings;
    }
    public function requestDetail()
    {
        return $this->hasMany(Request::class, 'driver_id', 'id');
    }
    public function driverAvailabilities()
    {
        return $this->hasMany(DriverAvailability::class, 'driver_id', 'id');
    }

    /**
     * The driver that the user_id belongs to.
     * @tested
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    } 
    public function serviceLocation()
    {
        return $this->belongsTo(ServiceLocation::class, 'service_location_id', 'id');
    } 
   public function owner()
    {
        return $this->belongsTo(Owner::class, 'owner_id', 'id');
    }

    public function carMake()
    {
        return $this->belongsTo(CarMake::class, 'car_make', 'id');
    }
    public function carModel()
    {
        return $this->belongsTo(CarModel::class, 'car_model', 'id');
    }

    /**
     * The driver associated with the user's id.
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function driverDetail()
    {
        return $this->hasOne(DriverDetail::class, 'driver_id', 'id');
    }

    /**
     * The driver document associated with the user's id.
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function driverDocument()
    {
        return $this->hasMany(DriverDocument::class, 'driver_id', 'id');
    }
    /**
    * The driver wallet history associated with the driver's id.
    *
    * @return \Illuminate\Database\Eloquent\Relations\hasOne
    */
    public function driverWalletHistory()
    {
        return $this->hasMany(DriverWalletHistory::class, 'user_id', 'id');
    }

    public function driverWallet()
    {
        return $this->hasOne(DriverWallet::class, 'user_id', 'id');
    }
    public function driverPaymentWalletHistory()
    {
        return $this->hasMany(DriverWalletHistory::class, 'driver_id', 'id');
    }

    public function withdrawalRequestsHistory()
    {
        return $this->hasMany(WalletWithdrawalRequest::class, 'driver_id', 'id');
    }

    public function driverPaymentWallet()
    {
        return $this->hasOne(DriverWallet::class, 'driver_id', 'id');
    }
    public function vehicleType()
    {
        return $this->hasOne(VehicleType::class, 'id', 'vehicle_type');
    }

    /**
    * Get formated and converted timezone of user's created at.
    *
    * @param string $value
    * @return string
    */
    public function getConvertedCreatedAtAttribute()
    {
        if ($this->created_at==null||!auth()->user()->exists()) {
            return null;
        }
        $timezone = auth()->user()->timezone?:env('SYSTEM_DEFAULT_TIMEZONE');
        return Carbon::parse($this->created_at)->setTimezone($timezone)->format('jS M h:i A');
    }
    /**
    * Get formated and converted timezone of user's created at.
    *
    * @param string $value
    * @return string
    */
    public function getConvertedUpdatedAtAttribute()
    {
        if ($this->updated_at==null||!auth()->user()->exists()) {
            return null;
        }
        $timezone = auth()->user()->timezone?:env('SYSTEM_DEFAULT_TIMEZONE');
        return Carbon::parse($this->updated_at)->setTimezone($timezone)->format('jS M h:i A');
    }

     public function privilegedVehicle()
    {
        return $this->hasMany(DriverPrivilegedVehicle::class, 'driver_id', 'id');
    }

    public function rating($user_id)
    {
        $rate=  User::where('id',$user_id)->first();
        return $rate->rating;
    }
}
