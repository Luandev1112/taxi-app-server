<?php

namespace App\Http\Controllers\Web\Master;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Country;
use App\Models\Access\Role;
use App\Models\Admin\Company;
use App\Models\Master\Developer;
use App\Models\Admin\AdminDetail;
use App\Models\Admin\ServiceLocation;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\Web\BaseController;
use App\Base\Constants\Auth\Role as RoleSlug;
use App\Base\Filters\Master\CommonMasterFilter;
use App\Base\Libraries\QueryFilter\QueryFilterContract;
use App\Base\Services\ImageUploader\ImageUploaderContract;
use App\Http\Requests\Master\DeveLoper\UpdateDeveloperRequest;
use App\Http\Requests\Master\Developer\CreateDeveLoperRequest;

/**
 * @resource Developer
 *
 *
 */
class DeveloperController extends BaseController
{
    /**
     * The Driver model instance.
     *
     * @var App\Models\Master\Developer;
     */
    protected $developer;

    /**
     * DriverController constructor.
     *
     * @param \App\Models\Admin\AdminDetail $admin_detail
     */
    public function __construct(Developer $developer, User $user)
    {
        $this->developer = $developer;
        $this->user = $user;
    }

    /**
    * Get all Developers
    * @return \Illuminate\Http\JsonResponse
    */
    public function index()
    {
        $page = trans('pages_names.admins');

        $main_menu = 'developer';
        $sub_menu = null;

        return view('admin.developer.index', compact('page', 'main_menu', 'sub_menu'));
    }

    public function getAllDeveloper(QueryFilterContract $queryFilter)
    {
        $url = request()->fullUrl(); //get full url

        $query = Developer::query();

        $results = $queryFilter->builder($query)->customFilter(new CommonMasterFilter)->paginate();


        return view('admin.developer._developer', compact('results'));
    }

    /**
    * Create Developer View
    *
    */
    public function create()
    {
        $page = trans('pages_names.add_admin');

        $countries = Country::active()->get();

        $main_menu = 'developer';
        $sub_menu = null;

        return view('admin.developer.create', compact('page', 'countries', 'main_menu', 'sub_menu'));
    }

    /**
     * Store Developer.
     *
     * @param \App\Http\Requests\Master\DeveLoper\CreateDeveLoperRequest $request
     * @return \Illuminate\Http\JsonResponse
     */


    public function store(CreateDeveLoperRequest $request)
    {
        $user = $this->user->create(['name'=>$request->input('first_name').' '.$request->input('last_name'),
            'email'=>$request->input('email'),
            'mobile'=>$request->input('mobile'),
            'mobile_confirmed'=>true,
            'password' => bcrypt($request->input('password'))
        ]);

        $user->developer()->create([
            'first_name'=>$request->input('first_name'),
            'last_name'=>$request->input('last_name'),
            'email'=>$request->input('email'),
            'mobile'=>$request->input('mobile'),
            'address'=>$request->input('address'),
            'state'=>$request->input('state'),
            'city'=>$request->input('city'),
            'country'=>$request->input('country'),
            'team'=>$request->input('team'),
            'pincode'=>$request->input('postal_code'),
        ]);
        $user->attachRole(RoleSlug::DEVELOPER);

        $message = trans('succes_messages.developer_added_succesfully');
        return redirect('developer')->with('success', $message);
    }


    public function getById(Developer $developer)
    {
        $page = trans('pages_names.edit_developer');

        $countries = Country::active()->get();
        $item = $developer->first();
        $main_menu = 'developer';
        $sub_menu = null;

        return view('admin.developer.update', compact('item', 'page', 'countries', 'main_menu', 'sub_menu'));
    }

    /**
     * Update Developer
     *
     * @param \App\Http\Requests\Master\DeveLoper\UpdateDeveloperRequest; $request
     * @param App\Models\Master\Developer $developer
     * @return \Illuminate\Http\JsonResponse
     * @hideFromAPIDocumentation
     */
    public function update(Developer $developer, UpdateDeveloperRequest $request)
    {
        $updated_params = $request->only(['first_name', 'last_name','mobile','email','address','state','city','country','team5']);
        $updated_params['pincode'] = $request->postal_code;

        $updated_user_params = ['name'=>$request->input('first_name').' '.$request->input('last_name'),
            'email'=>$request->input('email'),
            'mobile'=>$request->input('mobile')
        ];

        $developer->user->update($updated_user_params);

        $developer->update($updated_params);

        $message = trans('succes_messages.developer_updated_succesfully');
        return redirect('developer')->with('success', $message);
    }
    public function toggleStatus(user $user){
        $status = $user->active == 1 ? 0 : 1;
        $user->update([
            'active' => $status
        ]);

        $message = trans('succes_messages.developer_status_changed_succesfully');
        return redirect('developer')->with('success', $message);
    }



    public function delete(Developer $developer)
    {
        $developer->delete();

        $message = trans('succes_messages.developer_deleted_succesfully');
        return redirect('developer')->with('success', $message);
    }
}
