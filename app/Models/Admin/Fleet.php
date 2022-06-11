<?php

namespace App\Models\Admin;

use App\Base\Uuid\UuidModel;
use App\Models\Master\CarMake;
use App\Models\Master\CarModel;
use App\Models\Traits\HasActive;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Fleet extends Model
{
    use UuidModel,SoftDeletes,HasActive;

    protected $fillable = [
        'owner_id','brand','model','license_number','permission_number','vehicle_type','active','fleet_id','qr_image','approve'
    ];

    public function vehicleType(){
        return $this->belongsTo(VehicleType::class,'vehicle_type','id');
    }

    public function carBrand(){
        return $this->belongsTo(CarMake::class,'brand','id');
    }

    public function carModel(){
        return $this->belongsTo(CarModel::class,'model','id');
    }

    public function fleetDocument(){
        return $this->hasMany(FleetDocument::class,'fleet_id','id');
    }

    public function getQrCodeImageAttribute(){
        return asset('storage/uploads/qr-codes/'.$this->qr_image);
    }

    public function user(){
        return $this->belongsTo(User::class,'owner_id','id');
    }

    public function getFleetNameAttribute(){
        return  $this->carBrand->name .' - '. $this->carModel->name .' ('.$this->vehicleType->name.')';
    }

    public function driver(){
        return $this->hasOne(Driver::class,'fleet_id','id');
    }
}
