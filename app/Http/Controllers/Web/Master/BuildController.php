<?php

namespace App\Http\Controllers\Web\Master;

use DB;
use App\Models\Setting;
use Illuminate\Http\Request;
use App\Models\Master\Project;
use App\Models\Master\MobileBuild;
use Illuminate\Support\Facades\Log;
use App\Models\Master\ProjectFlavour;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;
use App\Base\Constants\Auth\Role as RoleSlug;
use App\Base\Filters\Master\MobileBuildFilter;
use App\Base\Filters\Master\CommonMasterFilter;
use App\Http\Controllers\Api\V1\BaseController;
use App\Http\Requests\Master\MobileBuildRequest;
use App\Http\Controllers\Web\Master\ipaDistrubution;
use App\Base\Libraries\QueryFilter\QueryFilterContract;
use App\Jobs\Notifications\Build\BuildUploadNotification;
use App\Base\Services\ImageUploader\ImageUploaderContract;

/**
 * @resource Settings
 *
 * vechicle types Apis
 */
class BuildController extends BaseController
{
    /**
     * The Setting model instance.
     *
     * @var \App\Models\Setting
     */
    protected $settings;


    /**
     * VehicleTypeController constructor.
     *
     * @param \App\Models\Setting $settings
     */
    public function __construct(Setting $settings)
    {
        $this->settings = $settings;
    }

    /**
    * Get all vehicle types
    * @return \Illuminate\Http\JsonResponse
    */
    public function index()
    {
        $page = 'Projects';

        $main_menu = 'builds';
        $sub_menu = 'list_builds';

        return view('admin.master.builds.index', compact('page', 'main_menu', 'sub_menu'));
    }



    /**
    * List Build Environments
    */
    public function buildEnvironments($project_id, $flavour_id)
    {
        $page = 'Environments';
        $main_menu = 'builds';
        $sub_menu = 'list_builds';
        $project_name = Project::where('id', $project_id)->pluck('project_name')->first();

        $flavour_name = ProjectFlavour::where('id', $flavour_id)->where('project_id', $project_id)->pluck('flavour_name')->first();

        $results = MobileBuild::where('project_id', $project_id)->active()->distinct()->select(['environment'])->paginate();

        return view('admin.master.builds.environments.index', compact('results', 'page', 'main_menu', 'sub_menu', 'project_name', 'flavour_name'));
    }

    /**
    * List Build Flavours
    */
    public function buildFlavours($project_id)
    {
        $page = 'Flavours';
        $main_menu = 'builds';
        $sub_menu = 'list_builds';
        $project_name = Project::where('id', $project_id)->pluck('project_name')->first();
        $results = ProjectFlavour::where('id', $project_id)->paginate();

        return view('admin.master.builds.flavours.index', compact('results', 'page', 'main_menu', 'sub_menu', 'project_name'));
    }

    /**
    * List Builds by environment and project id
    *
    */
    public function listBuildsByEnvironment($project_id, $flavour_id, $environment, QueryFilterContract $queryFilter)
    {
        $page = 'builds';
        $main_menu = 'builds';
        $sub_menu = 'list_builds';
        $project_name = Project::where('id', $project_id)->pluck('project_name')->first();

        $flavour_name = ProjectFlavour::where('id', $flavour_id)->where('project_id', $project_id)->pluck('flavour_name')->first();

        $query = MobileBuild::where('project_id', $project_id)->where('environment', $environment)->active()->orderBy('updated_at', 'desc');

        $results = $queryFilter->builder($query)->customFilter(new MobileBuildFilter)->paginate();
        if (request()->input('team')=='ios') {
            $android_active = '';
            $ios_active = 'active';
        } else {
            $android_active = 'active';
            $ios_active = '';
        }

        return view('admin.master.builds.list', compact('results', 'page', 'main_menu', 'sub_menu', 'android_active', 'ios_active', 'environment', 'project_name', 'flavour_name'));
    }

    /**
    * Fetch all builds
    */
    public function fetchProject(QueryFilterContract $queryFilter)
    {
        if (access()->hasRole(RoleSlug::SUPER_ADMIN)) {
            $query = Project::query();
        } elseif (access()->hasRole(RoleSlug::CLIENT)) {
            $auth_user = auth()->user();
            $query = Project::whereHas('pocClient', function ($query) use ($auth_user) {
                $query->where('user_id', $auth_user->id);
            });
        } else {
            $query = Project::query();
        }

        $results = $queryFilter->builder($query)->customFilter(new CommonMasterFilter)->paginate();
        return view('admin.master.builds._projects', compact('results'));
    }

    /**
    * Create Build ui page
    */
    public function create()
    {
        $page = 'Upload Builds';
        $main_menu = 'builds';
        $sub_menu = 'upload_builds';
        $projects = Project::active()->get();

        return view('admin.master.builds.post', compact('projects', 'page', 'main_menu', 'sub_menu'));
    }

    /**
    * Store Builds
    *
    */
    public function store(Request $request)
    {
        if (isset($_FILES['build'])) {
            $file_name = $_FILES['build']['name'];
            $temp_file_location = $_FILES['build']['tmp_name'];
            $temp_file_size = $_FILES['build']['size'];
            $file_size = '';

            $file = $request->file('build');


            if ($temp_file_size > 0) {
                $kb = $temp_file_size/1024;
                $file_size = $kb ;
                $file_size = number_format((float)$kb, 2, '.', '');
                $file_size = $file_size . " KB";
                if ($kb > 0) {
                    $mb = $kb/1024;
                    $file_size = $mb;
                    $file_size = number_format((float)$mb, 2, '.', '');
                    $file_size = $file_size . " MB";
                }
            }

            $path_info = pathinfo($file_name);
            $file_extension = $path_info['extension'];

            $project_name = Project::where('id', $request->input('project_id'))->pluck('project_name')->first();

            $flavour = ProjectFlavour::where('id', $request->input('flavour_id'))->where('project_id', $request->input('project_id'))->first();

            $flavour_id = $flavour->id;

            if (!$project_name) {
                $this->throwCustomException('selected project is invalid', 'project_id');
            }

            $version = $request->input('version');
            $project_id = $request->input('project_id');
            $additional_comments = $request->input('additional_comments')?:null;
            $environment = $request->input('environment');
            $uploaded_by = auth()->user()->name;


            // Get last build's build_number
            $build_number = MobileBuild::orderBy('id', 'DESC')->pluck('build_number')->first();
            if ($build_number) {
                $build_number = explode('_', $build_number);
                $build_number = $build_number[1]?:000000;
            } else {
                $build_number = 000000;
            }
            // Generate build number
            $build_number = 'BUILD_'.sprintf("%06d", $build_number+1);

            if ($request->input('team')=='ios') {
                // throw an exception if a file extension is mismatching
                if ($file_extension!='ipa') {
                    $this->throwCustomException('file format should be .ipa file', 'build');
                }

                $ipa_file_name_path = preg_replace("/[^a-zA-Z]+/", "", $project_name) . "_" .  date("Y_m_d_H_i_s");


                $plist_file_name_path = preg_replace("/[^a-zA-Z]+/", "", $project_name) . "_" .  date("Y_m_d_H_i_s");

                $plist_extension = 'plist';
                $plist_file_name = $plist_file_name_path .".".$plist_extension;

                $ipa_path = $this->config('app-build.upload.ios-builds.ipa.path');
                $plist_path = $this->config('app-build.upload.ios-builds.plist.path');

                $ipa_file_name = $ipa_file_name_path .".".$file_extension;
                $file_destintion_path = public_path('storage/'.$ipa_path);
                // dd($file_destintion_path);
                $file->move($file_destintion_path, $ipa_file_name);


                $build_path= Storage::disk(env('FILESYSTEM_DRIVER'))->url(file_path($ipa_path, $ipa_file_name));

                $app_name = $flavour->app_name;
                $bundleId = $flavour->bundle_identifier;

                $iosManifestFileContents = view('ios-build.ios-manifest')
            ->with('buildUrlRoute', $build_path)
            ->with('bundleId', $bundleId)
            ->with('bundleVersionNumber', $version)
            ->with('projectName', $app_name)
            ->render();

                $manifest_plist_path = $plist_path.'/'.$plist_file_name;

                Storage::put($manifest_plist_path, $iosManifestFileContents);

                $download_link= 'itms-services://?action=download-manifest&url='.Storage::disk(env('FILESYSTEM_DRIVER'))->url(file_path($plist_path, $plist_file_name));
            } else {
                if ($file_extension!='apk') {
                    $this->throwCustomException('file format should be .apk file', 'build');
                }
                $apk_path = $this->config('app-build.upload.android-builds.apk.path');

                $apk_file_name_path = preg_replace("/[^a-zA-Z]+/", "", $project_name) . "_" .  date("Y_m_d_H_i_s");

                $apk_file_name = $apk_file_name_path .".".$file_extension;
                $manifest_apk_path = $apk_path.'/'.$apk_file_name;
                $file_destintion_path = public_path('storage/'.$apk_path);
                // dd($file_destintion_path);
                $file->move($file_destintion_path, $apk_file_name);

                $download_link = Storage::disk(env('FILESYSTEM_DRIVER'))->url(file_path($apk_path, $apk_file_name));
            }

            $created_params = [
                'project_name'=>$project_name,
                'project_id'=>$project_id,
                'team'=>$request->input('team'),
                'version'=>$request->input('version'),
                'file_size'=>$file_size,
                'download_link'=>$download_link,
                'additional_comments'=>$additional_comments,
                'environment'=>$environment,
                'build_number'=>$build_number,
                'flavour_id'=>$flavour_id,
                'uploaded_by'=>$uploaded_by
                ];

            $mobile_build = MobileBuild::create($created_params);
        } else {
            $this->throwCustomException('build file required', 'build');
        }
        $message = 'Build Uploaded succesfully';

        $this->dispatch(new BuildUploadNotification($mobile_build));

        return redirect('builds/projects')->with('success', $message);
    }


    /**
    * Get the config value.
    *
    * @param string $key
    * @param mixed|null $default
    * @return mixed
    */
    protected function config($key, $default = null)
    {
        return data_get(config('base'), $key, $default);
    }

    /**
    * Delete Builds
    */
    public function deleteBuild(MobileBuild $mobile_build)
    {
        $mobile_build->delete();

        $message = 'Build Deleted Succesfully';

        return redirect('builds/'.$mobile_build->project_id.'/'.$mobile_build->environment)->with('success', $message);
    }
}
