<?php

namespace App\Models\Admin;

use Carbon\Carbon;
use App\Base\Uuid\UuidModel;
use App\Models\Traits\HasActive;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ZoneType extends Model
{
    use HasActive, UuidModel,SoftDeletes;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'zone_types';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'zone_id', 'type_id','payment_type','active','bill_status'
    ];

    /**
     * The relationships that can be loaded with query string filtering includes.
     *
     * @var array
     */
    public $includes = [
        'zone','vehicleType'
    ];

    /**
    * The accessors to append to the model's array form.
    *
    * @var array
    */
    protected $appends = [
        'vehicle_type_name','icon'
    ];
    /**
     * The zone type that belongs to.
     * @tested
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function zone()
    {
        return $this->belongsTo(Zone::class, 'zone_id', 'id');
    }

   

    /**
    * Get vehicle type's name.
    *
    * @return string
    */
    public function getVehicleTypeNameAttribute()
    {
        if (!$this->vehicleType()->exists()) {
            return null;
        }
        return $this->vehicleType->name;
    }
    /**
    * Get vehicle type's icon.
    *
    * @return string
    */
    public function getIconAttribute()
    {
        if (!$this->vehicleType()->exists()) {
            return null;
        }
        return $this->vehicleType->icon;
    }

    /**
     * The zone type that belongs to.
     * @tested
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function vehicleType()
    {
        return $this->belongsTo(VehicleType::class, 'type_id', 'id');
    }


    public function zoneTypePrice()
    {
        return $this->hasMany(ZoneTypePrice::class, 'zone_type_id', 'id');
    }

     public function zoneTypePackage()
    {
        return $this->hasMany(ZoneTypePackagePrice::class, 'zone_type_id', 'id');
    }

    public function PackageName()
    {
        return $this->hasOne(PackageType::class, 'id','package_type_id');
    }
}
