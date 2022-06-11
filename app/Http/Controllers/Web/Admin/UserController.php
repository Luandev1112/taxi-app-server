<?php

namespace App\Http\Controllers\Web\Admin;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Country;
use App\Models\Admin\Driver;
use App\Models\Admin\Company;
use App\Base\Constants\Auth\Role;
use App\Models\Admin\UserDetails;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\Web\BaseController;
use App\Base\Constants\Auth\Role as RoleSlug;
use App\Base\Filters\Master\CommonMasterFilter;
use App\Http\Requests\Admin\User\CreateUserRequest;
use App\Http\Requests\Admin\User\UpdateUserRequest;
use App\Base\Libraries\QueryFilter\QueryFilterContract;
use App\Base\Services\ImageUploader\ImageUploaderContract;
use App\Models\Request\Request as RequestRequest;
use App\Base\Filters\Admin\RequestFilter;

class UserController extends BaseController
{
    /**
     * The User Details model instance.
     *
     * @var \App\Models\Admin\UserDetails
     */
    protected $user_details ;

    /**
     * The User model instance.
     *
     * @var \App\Models\User
     */
    protected $user;

    /**
     * The
     *
     * @var App\Base\Services\ImageUploader\ImageUploaderContract
     */
    protected $imageUploader;


    /**
     * User Details Controller constructor.
     *
     * @param \App\Models\Admin\UserDetails $user_details
     */
    public function __construct(UserDetails $user_details, ImageUploaderContract $imageUploader, User $user)
    {
        $this->user_details = $user_details;
        $this->imageUploader = $imageUploader;
        $this->user = $user;
    }

    /**
    * Get all users
    * @return \Illuminate\Http\JsonResponse
    */
    public function index()
    {
        $page = trans('pages_names.users');

        $main_menu = 'users';
        $sub_menu = 'user_details';

        return view('admin.users.index', compact('page', 'main_menu', 'sub_menu'));
    }

    public function getAllUser(QueryFilterContract $queryFilter)
    {
        $url = request()->fullUrl(); //get full url

        $query = User::companyKey()->belongsToRole(RoleSlug::USER);
        $results = $queryFilter->builder($query)->customFilter(new CommonMasterFilter)->paginate();

        return view('admin.users._user', compact('results'));
    }

    /**
    * Create User View
    *
    */
    public function create()
    {
        $page = trans('pages_names.add_user');

        $countries = Country::active()->get();

        $main_menu = 'users';
        $sub_menu = 'user_details';

        return view('admin.users.create', compact('page', 'countries', 'main_menu', 'sub_menu'));
    }

    /**
     * Create User.
     *
     * @param \App\Http\Requests\Admin\User\CreateUserRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CreateUserRequest $request)
    {
        $created_params = $request->only(['name','mobile','email','country']);
        $created_params['mobile_confirmed'] = true;
        $created_params['password'] = bcrypt($request->input('password'));

        $validate_exists_email = $this->user->belongsTorole(Role::USER)->where('email', $request->email)->exists();

        $validate_exists_mobile = $this->user->belongsTorole(Role::USER)->where('mobile', $request->mobile)->exists();

        if ($validate_exists_email) {
            return redirect()->back()->withErrors(['email'=>'Provided email hs already been taken'])->withInput();
        }
        if ($validate_exists_mobile) {
            return redirect()->back()->withErrors(['mobile'=>'Provided mobile hs already been taken'])->withInput();
        }

        if ($uploadedFile = $this->getValidatedUpload('profile_picture', $request)) {
            $created_params['profile_picture'] = $this->imageUploader->file($uploadedFile)
                ->saveProfilePicture();
        }

        $created_params['company_key'] = auth()->user()->company_key;

        $created_params['refferal_code']= str_random(6);

        $user = $this->user->create($created_params);

        $user->attachRole(RoleSlug::USER);


        $message = trans('succes_messages.user_added_succesfully');

        return redirect('users')->with('success', $message);
    }

    public function getById(User $user)
    {
        $page = trans('pages_names.edit_user');


        $countries = Country::all();
        $results = $user->userDetails ?? $user;
        $main_menu = 'users';
        $sub_menu = 'user_details';

        return view('admin.users.update', compact('page', 'countries', 'main_menu', 'results', 'sub_menu'));
    }


    public function update(User $user, UpdateUserRequest $request)
    {
        $updated_params = $request->only(['name','mobile','email','country']);

        if ($uploadedFile = $this->getValidatedUpload('profile_picture', $request)) {
            $updated_params['profile_picture'] = $this->imageUploader->file($uploadedFile)
                ->saveProfilePicture();
        }

        $validate_exists_email = $this->user->belongsTorole(Role::USER)->where('email', $request->email)->where('id', '!=', $user->id)->exists();

        $validate_exists_mobile = $this->user->belongsTorole(Role::USER)->where('mobile', $request->mobile)->where('id', '!=', $user->id)->exists();

        if ($validate_exists_email) {
            return redirect()->back()->withErrors(['email'=>'Provided email hs already been taken'])->withInput();
        }
        if ($validate_exists_mobile) {
            return redirect()->back()->withErrors(['mobile'=>'Provided mobile hs already been taken'])->withInput();
        }

        $user->update($updated_params);

        $message = trans('succes_messages.user_updated_succesfully');

        return redirect('users')->with('success', $message);
    }

    public function toggleStatus(User $user)
    {
        $status = $user->active == 1 ? 0 : 1;
        $user->update([
            'active' => $status
        ]);

        $message = trans('succes_messages.user_status_changed_succesfully');
        return redirect('users')->with('success', $message);
    }
    public function delete(User $user)
    {
        if(env('APP_FOR')=='demo'){

        $message = 'you cannot delete the user. this is demo version';

        return $message;

        }
        $user->delete();

        $message = trans('succes_messages.user_deleted_succesfully');

        return $message;
    }

    public function UserTripRequest(QueryFilterContract $queryFilter, User $user)
    {
       
        $completedTrips = RequestRequest::where('user_id',$user->id)->companyKey()->whereIsCompleted(true)->count();
        $cancelledTrips = RequestRequest::where('user_id',$user->id)->companyKey()->whereIsCancelled(true)->count();
        $upcomingTrips = RequestRequest::where('user_id',$user->id)->companyKey()->whereIsLater(true)->whereIsCompleted(false)->whereIsCancelled(false)->whereIsDriverStarted(false)->count();

        $card = [];
        $card['completed_trip'] = ['name' => 'trips_completed', 'display_name' => 'Completed Rides', 'count' => $completedTrips, 'icon' => 'fa fa-flag-checkered text-green'];
        $card['cancelled_trip'] = ['name' => 'trips_cancelled', 'display_name' => 'Cancelled Rides', 'count' => $cancelledTrips, 'icon' => 'fa fa-ban text-red'];
        $card['upcoiming_trip'] = ['name' => 'trips_cancelled', 'display_name' => 'Upcoming Rides', 'count' => $upcomingTrips, 'icon' => 'fa fa-calendar'];

        $main_menu = 'users';
        $sub_menu = 'user_details';



         $query = RequestRequest::where('user_id',$user->id);
        $results = $queryFilter->builder($query)->customFilter(new RequestFilter)->defaultSort('-created_at')->paginate();


        return view('admin.users.user-request-list', compact('results','card','main_menu','sub_menu'));
    }
}
