<?php

namespace App\Models\Admin;

use App\Base\Uuid\UuidModel;
use App\Models\Traits\HasActive;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class FleetDocument extends Model
{
    use UuidModel,SoftDeletes,HasActive;

    protected $fillable = [
        'fleet_id','name','image','expiry_date'
    ];

    public function fleet(){
        return $this->belongsTo(Fleet::class,'fleet_id','id');
    }

    public function getRegistrationCertificateImageAttribute(){
        $image = $this->whereName('registration_certificate')->pluck('image')->first();
        if (empty($image)) {
            return null;
        }

        return Storage::disk(env('FILESYSTEM_DRIVER'))->url(file_path($this->uploadPath(), $image));
    }

    public function getVehicleBackSideImageAttribute(){
        $image = $this->whereName('vehicle_back_side')->pluck('image')->first();
        if (empty($image)) {
            return null;
        }

        return Storage::disk(env('FILESYSTEM_DRIVER'))->url(file_path($this->uploadPath(), $image));
    }
    
    public function uploadPath()
    {
        return config('base.fleets.upload.images.path');
    }
}
