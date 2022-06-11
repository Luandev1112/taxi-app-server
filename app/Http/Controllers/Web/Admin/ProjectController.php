<?php

namespace App\Http\Controllers\Web\Admin;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Country;
use App\Models\TimeZone;
use Illuminate\Http\Request;
use App\Models\Master\Project;
use App\Http\Controllers\Controller;
use App\Models\Admin\ServiceLocation;
use App\Http\Controllers\Web\BaseController;
use App\Base\Constants\Auth\Role as RoleSlug;
use App\Base\Filters\Master\CommonMasterFilter;
use App\Base\Libraries\QueryFilter\QueryFilterContract;
use App\Http\Requests\Admin\Project\CreateProjectRequest;
use App\Http\Requests\Admin\Project\UpdateProjectRequest;
use App\Base\Services\ImageUploader\ImageUploaderContract;
use App\Http\Requests\Master\PocClient\ClientStoreRequest;
use App\Http\Requests\Master\PocClient\ClientUpdateRequest;
use App\Http\Requests\Admin\Flavour\FlavourStoreRequest;
use App\Http\Requests\Admin\Flavour\FlavourUpdateRequest;
use App\Models\Master\ProjectFlavour;
use App\Models\Master\PocClient;
use App\Jobs\Notifications\Auth\Registration\UserRegistrationNotification;

class ProjectController extends BaseController
{
    /**
     * Class constructor.
     */
    protected $imageUploader;

    protected $serviceLocation;

    public function __construct(Project $project, User $user, ProjectFlavour $projectflavour)
    {
        $this->project = $project;
        $this->user = $user;
        $this->projectflavour = $projectflavour;
    }

    public function index()
    {
        $page = trans('pages_names.service_location');
        $main_menu = 'project';
        $sub_menu = '';

        return view('admin.project.index', compact('page', 'main_menu', 'sub_menu'));
    }

    public function getAllProject(QueryFilterContract $queryFilter)
    {
        $url = request()->fullUrl(); //get full url

        $query = Project::query();

        if (access()->hasRole(RoleSlug::CLIENT)) {
            $project_ids = PocClient::where('user_id', auth()->user()->id)->toArray();
            $query = Project::whereIn('id', $project_ids);
        }


        $results = $queryFilter->builder($query)->customFilter(new CommonMasterFilter)->paginate();

        return view('admin.project._project', compact('results'));
    }

    public function create()
    {
        $page = trans('pages_names.add_service_location');
        $main_menu = 'project';
        $sub_menu = '';
        return view('admin.project.create', compact('page', 'main_menu', 'sub_menu'));
    }

    public function store(CreateProjectRequest $request)
    {
        $created_params = $request->only(['project_name','poc_name','poc_email']);

        Project::create($created_params);

        $message = trans('succes_messages.project_added_succesfully');
        return redirect('project')->with('success', $message);
    }

    public function getById(Project $project)
    {
        $item = $project;
        $timezones = TimeZone::all();
        $page = trans('pages_names.edit_project');
        $main_menu = 'project';
        $sub_menu = '';
        return view('admin.project.update', compact('timezones', 'item', 'page', 'main_menu', 'sub_menu'));
    }

    public function update(UpdateProjectRequest $request, Project $project)
    {
        $updated_params = $request->all();
        $project->update($updated_params);
        $message = trans('succes_messages.project_updated_succesfully');
        return redirect('project')->with('success', $message);
    }

    public function toggleStatus(Project $project)
    {
        $status = $project->isActive() ? false: true;
        $project->update(['active' => $status]);

        $message = trans('succes_messages.project_status_changed_succesfully');
        return redirect('project')->with('success', $message);
    }

    public function delete(Project $project)
    {
        $project->delete();

        $message = trans('succes_messages.project_deleted_succesfully');
        return redirect('project')->with('success', $message);
    }

    /**
    * Show Allerdy Added client
    */
    public function addedClient(Project $project)
    {
        $project_id = $project->id;

        $ids = PocClient::where('project_id', $project_id)->pluck('user_id')->toArray();

        $results = User::whereIn('id', $ids)->paginate();

        $page = trans('pages_names.add_client');

        $main_menu = 'project';
        $sub_menu = '';

        return view('admin.project.client.addedclient', compact('results', 'page', 'project_id', 'main_menu', 'sub_menu'));
    }
    /**
    * To Create Clients for Project
    */
    public function addClient(Project $project)
    {
        $project_id = $project->id;

        $page = trans('pages_names.add_client');

        $countries = Country::active()->get();

        $ids = PocClient::where('project_id', $project_id)->pluck('user_id')->toArray();

        $clients_from_user = User::belongsToRole(RoleSlug::CLIENT)->whereNotIn('id', $ids)->get();

        // dd($clients_from_user);

        $main_menu = 'project';
        $sub_menu = '';

        return view('admin.project.client.addclient', compact('page', 'project_id', 'clients_from_user', 'countries', 'main_menu', 'sub_menu'));
    }
    /**
    * Store Client  with corresponsing Project
    *
    */
    public function storeClient(ClientStoreRequest $request, Project $project)
    {
        $check = $request;

        if ($check->exisiting_client == 'on') {
            PocClient::create([
                        'user_id'=>$check->client,
                        'project_id'=>$project->id,
                    ]);
            $user = User::where('id', $check->client)->first();
        } else {
            $user = $this->user->create(['name'=>$request->input('first_name').' '.$request->input('last_name'),
                        'email'=>$request->input('email'),
                        'mobile'=>$request->input('mobile'),
                        'mobile_confirmed'=>true,
                        'password' => bcrypt($request->input('password'))
                    ]);

            PocClient::create([
                        'user_id'=>$user->id,
                        'project_id'=>$project->id,
                    ]);

            $user->attachRole(RoleSlug::CLIENT);
        }

        // $this->dispatch(new UserRegistrationNotification($user));

        $message = trans('succes_messages.project_client_added_succesfully');
        return redirect('project/added/clients/'.$project->id)->with('success', $message);
    }
    /**
    * Edit Client Details
    *
    */
    public function editClient(User $user)
    {
        $page = trans('pages_names.edit_project_client');

        $item = $user;
        $main_menu = 'project';
        $sub_menu = null;

        return view('admin.project.client.editclient', compact('item', 'page', 'main_menu', 'sub_menu'));
    }
    /**
    * Update Client Details
    *
    */
    public function updateClient(ClientUpdateRequest $request, User $user)
    {
        $updated_user_params = ['name'=>$request->input('first_name').' '.$request->input('last_name'),
            'email'=>$request->input('email'),
            'mobile'=>$request->input('mobile')
        ];

        $project = PocClient::where('user_id', $user->id)->first();

        $project_id = $project->project_id;

        $user->update($updated_user_params);

        $message = trans('succes_messages.project_client_updated_succesfully');
        return redirect('project/added/clients/'.$project_id)->with('success', $message);
    }
    public function toggleClientStatus(User $user)
    {
        $status = $user->active == 1 ? 0 : 1;
        $user->update([
            'active' => $status
        ]);

        $project = PocClient::where('user_id', $user->id)->first();

        $project_id = $project->project_id;

        $message = trans('succes_messages.client_status_changed_succesfully');
        return redirect('project/added/clients/'.$project_id)->with('success', $message);
    }

    /**
    * Delete Client    *
    */
    public function deleteClient(User $user)
    {
        $user->delete();

        $message = trans('succes_messages.project_client_deleted_succesfully');
        return redirect('project')->with('success', $message);
    }
    /**
    * Show Allerdy Added flavour
    */
    public function addedFlavour(Project $project)
    {
        $project_id = $project->id;

        $results = ProjectFlavour::where('project_id', $project_id)->paginate();

        // d/d($results);
        $page = trans('pages_names.add_client');

        $main_menu = 'project';
        $sub_menu = '';

        return view('admin.project.flavour.addedflavour', compact('results', 'page', 'project_id', 'main_menu', 'sub_menu'));
    }
    /**
    * To Create Clients for Project
    */
    public function addFlavour(Project $project)
    {
        $project_id = $project->id;

        $page = trans('pages_names.add_client');

        $main_menu = 'project';
        $sub_menu = '';

        return view('admin.project.flavour.addflavour', compact('page', 'project_id', 'main_menu', 'sub_menu'));
    }
    /**
    * Store flavour  with corresponsing Project
    *
    */
    public function storeFlavour(FlavourStoreRequest $request, Project $project)
    {
        $created_params = $request->only(['flavour_name','app_name','bundle_identifier']);

        $created_params['project_id'] = $project->id;

        ProjectFlavour::create($created_params);



        $message = trans('succes_messages.project_flavour_added_succesfully');
        return redirect('project/added/flavour/'.$project->id)->with('success', $message);
    }
    /**
    * Edit flavour Details
    *
    */
    public function editFlavour(ProjectFlavour $projectflavour)
    {
        $page = trans('pages_names.edit_project_client');

        $project_id = $projectflavour->project_id;

        $item = $projectflavour;
        $main_menu = 'project';
        $sub_menu = null;

        return view('admin.project.flavour.editflavour', compact('item', 'page', 'project_id', 'main_menu', 'sub_menu'));
    }
    /**
    * Update flavour Details
    *
    */
    public function updateFlavour(FlavourUpdateRequest $request, ProjectFlavour $projectflavour)
    {
        $project_id = $projectflavour->project_id;

        $projectflavour->update($request->all());

        $message = trans('succes_messages.project_flavour_updated_succesfully');
        return redirect('project/added/flavour/'.$project_id)->with('success', $message);
    }
    
    /**
    * Delete flavour    *
    */
    public function deleteFlavour(ProjectFlavour $projectflavour)
    {
        $project_id = $projectflavour->project_id;

        $projectflavour->delete();

        $message = trans('succes_messages.project_flavour_deleted_succesfully');
        return redirect('project/added/flavour/'.$project_id)->with('success', $message);
    }
    /**
    * Fetch Flavours by project id
    *
    */
    public function fetchFlavoursByProject(Request $request)
    {
        $projectId = $request->id;
        return ProjectFlavour::whereProjectId($projectId)->get();
    }
}
