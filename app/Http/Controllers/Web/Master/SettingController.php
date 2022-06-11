<?php

namespace App\Http\Controllers\Web\Master;

use App\Http\Controllers\Api\V1\BaseController;
use App\Base\Constants\Auth\Role as RoleSlug;
use App\Models\Setting;
use App\Base\Services\ImageUploader\ImageUploaderContract;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;

/**
 * @resource Settings
 *
 * vechicle types Apis
 */
class SettingController extends BaseController
{
    /**
     * The Setting model instance.
     *
     * @var \App\Models\Setting
     */
    protected $settings;

    protected $imageUploader;

    /**
     * VehicleTypeController constructor.
     *
     * @param \App\Models\Setting $settings
     */
    public function __construct(Setting $settings, ImageUploaderContract $imageUploader)
    {
        $this->settings = $settings;
        $this->imageUploader = $imageUploader;
    }

    /**
    * Get all vehicle types
    * @return \Illuminate\Http\JsonResponse
    */
    public function index()
    {
        $settings = Setting::select('*')->get()->groupBy('category');

        $page = trans('pages_names.system_settings');

        $main_menu = 'settings';
        $sub_menu = 'system_settings';

        return view('admin.master.settings', compact('settings', 'page', 'main_menu', 'sub_menu'));
    }

    /**
    * Store Settings
    *
    */
    public function store(Request $request)
    {
        if (env('APP_FOR')=='demo') {
            $message = trans('succes_messages.you_are_not_authorised');

            return redirect('system/settings')->with('warning', $message);
        }
        DB::beginTransaction();

        $settings_to_redis = $request->except(['logo','_token']);

        try {
            $settingTable = Setting::get();

            foreach ($settingTable as $key => $value) {
                $key_name = $value->name;

                if (isset($request->$key_name)) {
                    $settingTable[$key]->value = $request->$key_name;
                    if ($request->hasFile($value->name)) {
                        //print_r($value->name);die();
                        if ($uploadedFile = $this->getValidatedUpload($value->name, $request)) {
                            $settingTable[$key]->value = $this->imageUploader->file($uploadedFile)
                                ->saveSystemAdminLogo();
                        }
                    }
                    $settingTable[$key]->save();
                }
            }

            Cache::forget('setting_cache_set');
            // Redis::set('settings', json_encode($settings_to_redis));
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e . 'Error while Create Admin. Input params : ' . json_encode($request->all()));
            return $this->respondBadRequest('Unknown error occurred. Please try again later or contact us if it continues.');
        }
        DB::commit();

        $message = trans('succes_messages.system_settings_updated');

        return redirect('system/settings')->with('success', $message);
    }
}
