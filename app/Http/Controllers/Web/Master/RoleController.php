<?php

namespace App\Http\Controllers\Web\Master;

use App\Base\Filters\Master\CommonMasterFilter;
use App\Base\Libraries\QueryFilter\QueryFilterContract;
use App\Http\Controllers\Web\BaseController;
use App\Http\Requests\Master\Roles\AttachAndDetachPermissionsRequest;
use App\Http\Requests\Master\Roles\CreateRoleRequest;
use App\Http\Requests\Master\Roles\UpdateRoleRequest;
use App\Models\Access\Permission;
use App\Models\Access\Role;
use App\Base\Constants\Auth\Role as RoleSlug;

/**
 * @resource Roles&Permissions
 *
 * Roles & Permissions
 */
class RoleController extends BaseController
{

    /**
     * The role model instance.
     *
     * @var \App\Models\Access\Role
     */
    protected $role;

    /**
     * The Permission model instance.
     *
     * @var \App\Models\Access\Permission
     */
    protected $permission;

    /**
     * RoleController constructor.
     *
     * @param \App\Models\Role $role
     * @param ImageUploaderContract $imageUploader
     */
    public function __construct(Role $role, Permission $permission)
    {
        $this->role = $role;
        $this->permission = $permission;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     */
    public function index(QueryFilterContract $queryFilter)
    {
        if (access()->hasRole(RoleSlug::SUPER_ADMIN)) {
            $result = Role::whereIn('slug', RoleSlug::webShowableRoles());
        } else {
            $result = Role::where('slug', '!=', 'super-admin')->whereIn('slug', RoleSlug::webShowableRoles());
        }

        $results = $queryFilter->builder($result)->customFilter(new CommonMasterFilter)->paginate();

        $page = trans('pages_names.roles');

        $main_menu = 'settings';

        $sub_menu = 'roles';

        return view('admin.master.roles.index', compact('results', 'page', 'main_menu', 'sub_menu'));
    }

    /**
     * Create Role
     * @return redirect to create role page
     */
    public function create()
    {
        $page = trans('pages_names.add_role');

        $permissions = $this->permission->get();

        $main_menu = 'settings';

        $sub_menu = 'roles';

        return view('admin.master.roles.addRole', compact('page', 'permissions', 'main_menu', 'sub_menu'));
    }


    /**
     * Get Roles By ID
     * @param id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getById($id)
    {
        $role = $this->role->where('id', $id)->first();

        $page = trans('pages_names.edit_role');

        $permissions = $this->permission->get();

        $main_menu = 'settings';

        $sub_menu = 'roles';

        return view('admin.master.roles.editRole', compact('role', 'permissions', 'page', 'main_menu', 'sub_menu'));
    }

    /**
     * create the Role.
     *
     * @param \App\Http\Requests\Auth\Registration\CreateRoleRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @response
     * {
     *"success": true,
     *"message": "success"
     *}
     */
    public function store(CreateRoleRequest $request)
    {
        $role = $this->role->create($request->all());

        $message = trans('succes_messages.role_added_succesfully');

        return redirect('roles')->with('success', $message);
    }

    /**
     * Update role
     * @param UpdateRoleRequest $role
     * @return \Illuminate\Http\JsonResponse
     * @hideFromAPIDocumentation
     * @response
     * {
     *"success": true,
     *"message": "success"
     *}
     */
    public function update(UpdateRoleRequest $request, Role $role)
    {
        $role->update($request->all());

        $message = trans('succes_messages.role_updated_succesfully');

        return redirect('roles')->with('success', $message);
    }


    /**
     * Assign Permissions
     * @param id
     * @return
     */
    public function assignPermissionView($id)
    {
        $role = $this->role->where('id', $id)->first();

        $page = trans('pages_names.assign_permissions');

        $attachable_permissions = $this->permission->get();

        $permissions = $this->getAttachablePermissions($attachable_permissions);

        $main_menu = 'settings';

        $sub_menu = 'roles';

        return view('admin.master.roles.assignPermissions', compact('role', 'permissions', 'page', 'main_menu', 'sub_menu'));
    }

    /**
     * Attach And Detach Permissions
     * @param Request $request, Role $role
     * @return Redirect to roles page with success message
     * @response
     * {
     *"success": true,
     *"message": "success"
     *}
     */
    public function attachAndDetachPermissions(AttachAndDetachPermissionsRequest $request, Role $role)
    {
        $permissions = $request->input('permissions');

        if (count($role->permissions) > 0) {
            $role->detachPermissions($role->permissions);

            $attachable_permissions = $this->getPermissions($permissions);

            $role->attachPermissions($attachable_permissions);
        } else {
            $attachable_permissions = $this->getPermissions($permissions);

            $role->attachPermissions($attachable_permissions);
        }

        $message = trans('succes_messages.permission_assigned_succesfully');

        return redirect('roles/assign/permissions/'.$role->id)->with('success', $message);
    }

    /**
     * Get all permissions by ids
     * @param $permissions
     * @return
     */
    public function getPermissions($permissions)
    {
        return $permissions = $this->permission->whereIn('id', $permissions)->get();
    }

    /**
    * Get Attachable Permissions
    */
    public function getAttachablePermissions($permissions)
    {
        $menu =[];

        foreach ($permissions as $key => $permission) {
            $menu[$permission->main_menu][]=$permission;
        }

        return $menu;
    }
}
