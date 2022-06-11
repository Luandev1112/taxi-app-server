<?php

use App\Base\Constants\Auth\Permission as PermissionSlug;
use App\Base\Constants\Auth\Role as RoleSlug;
use App\Models\Access\Permission;
use App\Models\Access\Role;
use Illuminate\Database\Seeder;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
      * List of all the permissions to be created.
      *
      * @var array
      */

    protected $permissions = [

        PermissionSlug::ACCESS_DASHBOARD => [
            'name' => 'Access-Dashboard',
            'description' => 'Access Dashboard',
            'main_menu' => 'dashboard',
            'sub_menu' => null,
            'sort' => 1,
            'main_link' => 'dashboard',
            'icon' => 'fa fa-tachometer'
        ],
        PermissionSlug::GET_ALL_ROLES => [
            'name' => 'Get-All-Roles',
            'description' => 'Get all roles',
            'main_menu'=>'settings',
            'sub_menu'=>'roles',
            'sub_link'=>'roles',
            ],

        PermissionSlug::GET_ALL_PERMISSIONS => [
            'name' => 'Get-All-Permissions',
            'description' => 'Get all permissions',
            'main_menu'=>'settings',
            'sub_menu'=>'roles',
        ],

        PermissionSlug::SETTINGS => [
            'name' => 'view-all-settings',
            'description' => 'View All Settings',
            'main_menu' => 'settings',
            'sub_menu' => null,
            'sort' => 12,
            'icon' => 'fa fa-cogs'
        ],
        PermissionSlug::VIEW_COMPANIES => [
            'name' => 'View-Companies',
            'description' => 'View Companies',
            'main_menu' => 'company',
            'sub_menu' => null,
            'sort' => 7,
            'main_link' => 'company',
            'icon' => 'fa fa-building'
        ],
            PermissionSlug::DRIVERS_MENU => [
            'name' => 'drivers',
            'description' => 'View all driver related menus',
            'main_menu' => 'drivers',
            'sub_menu' => null,
            'sort' => 4,
            'icon' => 'fa fa-users'
        ],

        PermissionSlug::VIEW_DRIVERS => [
            'name' => 'Get-All-Drivers',
            'description' => 'Get all drivers',
            'main_menu'=>'drivers',
            'sub_menu'=>'driver_details',
            'sub_link'=>'drivers',
        ],
        PermissionSlug::VIEW_TYPES => [
            'name' => 'Get-All-Types',
            'description' => 'Get all types',
            'main_menu'=>'drivers',
            'sub_menu'=>'types',
            'sub_link'=>'types',
        ],
        PermissionSlug::MAP_MENU => [
            'name' => 'map',
            'description' => 'View all map related menus',
            'main_menu' => 'map',
            'sub_menu' => null,
            'sort' => 8,
            'icon' => 'fa fa-map'
        ],

        PermissionSlug::VIEW_ZONE => [
            'name' => 'Get-All-zones',
            'description' => 'Get all zones',
            'main_menu'=>'map',
            'sub_menu'=>'zone',
            'sub_link'=>'zone',
        ],
         PermissionSlug::LIST_AIRPORTS => [
            'name' => 'Get-All-Airports',
            'description' => 'Get all airports',
            'main_menu'=>'map',
            'sub_menu'=>'airport',
            'sub_link'=>'aiports',
        ],

        PermissionSlug::ADD_EDIT_AIRPORTS => [
            'name' => 'Add-Edit-Airports',
            'description' => 'Add/edit airports',
            'main_menu'=>'map',
            'sub_menu'=>'airport',
            'sub_link'=>'aiports',
        ],

        PermissionSlug::VIEW_SYSTEM_SETINGS => [
            'name' => 'Get-All-System-Settings',
            'description' => 'Get all system settings',
            'main_menu'=>'settings',
            'sub_menu'=>'system_settings',
            'sub_link'=>'system/settings',
        ],
        PermissionSlug::USER_MENU => [
            'name' => 'users',
            'description' => 'View all user related menus',
            'main_menu' => 'users',
            'sub_menu' => null,
            'sort' => 5,
            'icon' => 'fa fa-user'
        ],
        PermissionSlug::VIEW_USERS => [
            'name' => 'Get-All-Users',
            'description' => 'Get all Users',
            'main_menu'=>'users',
            'sub_menu'=>'user_details',
            'sub_link'=>'users',
        ],
        PermissionSlug::USER_SOS => [
            'name' => 'User-Sos',
            'description' => 'Emergency Numbers',
            'main_menu'=>'emergency_number',
            'sub_menu'=> null,
            'main_link'=>'sos',
            'sort' => 6,
            'icon' => 'fa fa-heartbeat'
        ],
        PermissionSlug::SERVICE_LOCATION => [
            'name' => 'Service-Location',
            'description' => 'Available location for app',
            'main_menu'=>'service_location',
            'sub_menu'=> null,
            'main_link'=>'service_location',
            'sort' => 2,
            'icon' => 'fa fa-map-marker'
        ],
        PermissionSlug::ADMIN => [
            'name' => 'Admin',
            'description' => 'Admin User List',
            'main_menu'=>'admin',
            'sub_menu'=> null,
            'main_link'=>'admins',
            'sort' => 3,
            'icon' => 'fa fa-user-circle-o'
        ],
        PermissionSlug::DISPATCH_REQUEST => [
            'name' => 'Dispatch-Request',
            'description' => 'Dispatch manual requests from admin panel',
            'main_menu'=>'dispatch_request',
            'sub_menu'=> null,
            'main_link'=>'dispatch',
            'sort' => 3,
            'icon' => 'fa fa-tripadvisor'
        ],
        PermissionSlug::UPLOAD_BUILDS => [
            'name' => 'Upload-Mobile-Builds',
            'description' => 'Upload mobile builds',
            'main_menu'=>'builds',
            'sub_menu'=> 'upload_builds',
            'main_link'=>'/builds/create',
            'sort' => 3,
            'icon' => 'fa fa-tripadvisor'
        ],
        PermissionSlug::VIEW_BUILDS => [
            'name' => 'View-Mobile-Builds',
            'description' => 'View mobile builds',
            'main_menu'=>'builds',
            'sub_menu'=> 'list_builds',
            'main_link'=>'/builds/projects',
            'sort' => 3,
            'icon' => 'fa fa-tripadvisor'
        ],
        PermissionSlug::VIEW_REQUEST => [
            'name' => 'View-Request-Details',
            'description' => 'View Request Details',
            'main_menu'=>'request',
            'sub_menu'=> 'request',
            'main_link'=>'requests',
            'sort' => 5,
            'icon' => 'fa fa-list'
        ],
        PermissionSlug::MANAGE_FAQ => [
            'name' => 'manage-faq',
            'description' => 'View Faq',
            'main_menu'=>'faq',
            'sub_menu'=> null,
            'main_link'=>'faq',
            'sort' => 13,
            'icon' => 'fa fa-question-circle'
        ],
        PermissionSlug::CANCELLATION_REASON => [
            'name' => 'cancellation-title',
            'description' => 'View Cancellation Title',
            'main_menu'=>'cancellation',
            'sub_menu'=> null,
            'main_link'=>'cancellation',
            'sort' => 14,
            'icon' => 'fa fa-ban'
        ],
        PermissionSlug::COMPLAINTS => [
            'name' => 'complaints',
            'description' => 'View Complaints',
            'main_menu'=>'complaints',
            'sub_menu'=> null,
            'main_link'=>'complaint',
            'sort' => 15,
            'icon' => 'fa fa-list-alt'
        ],
        PermissionSlug::COMPLAINT_TITLE => [
            'name' => 'complaint-title',
            'description' => 'View Complaint Title',
            'main_menu'=>'complaints',
            'sub_menu'=> 'complaint-title',
            'sub_link'=>'complaint/title',
            'sort' => 15.1,
            'icon' => 'fa fa-circle-thin'
        ],
        PermissionSlug::USER_COMPLAINT => [
            'name' => 'user-complaint',
            'description' => 'Manange User Complaint',
            'main_menu'=>'complaints',
            'sub_menu'=> 'user-complaint',
            'sub_link'=>'complaint/users',
            'sort' => 15.2,
            'icon' => 'fa fa-circle-thin'
        ],
        PermissionSlug::DRIVER_COMPLAINT => [
            'name' => 'driver-complaint',
            'description' => 'Manage Driver Complaint',
            'main_menu'=>'complaints',
            'sub_menu'=> 'driver-complaint',
            'sub_link'=>'complaint/drivers',
            'sort' => 15.3,
            'icon' => 'fa fa-circle-thin'
        ],
        PermissionSlug::REPORTS => [
            'name' => 'reports',
            'description' => 'View reports',
            'main_menu'=>'reports',
            'sub_menu'=> null,
            'main_link'=>'reports',
            'sort' => 16,
            'icon' => 'fa fa-file-pdf-o'
        ],
        PermissionSlug::USER_REPORT => [
            'name' => 'user-report',
            'description' => 'Download User Report',
            'main_menu'=>'reports',
            'sub_menu'=> 'user_report',
            'sub_link'=>'reports/user',
            'sort' => 16.1,
            'icon' => 'fa fa-circle-thin'
        ],
        PermissionSlug::DRIVER_REPORT => [
            'name' => 'driver-report',
            'description' => 'Download Driver Report',
            'main_menu'=>'reports',
            'sub_menu'=> 'driver_report',
            'sub_link'=>'reports/driver',
            'sort' => 16.2,
            'icon' => 'fa fa-circle-thin'
        ],
        PermissionSlug::TRAVEL_REPORT => [
            'name' => 'travel-report',
            'description' => 'Download Travel Report',
            'main_menu'=>'reports',
            'sub_menu'=> 'travel_report',
            'sub_link'=>'reports/travel',
            'sort' => 16.3,
            'icon' => 'fa fa-circle-thin'
        ],
        PermissionSlug::MANAGE_MAP => [
            'name' => 'manage-map',
            'description' => 'Manage Map',
            'main_menu'=>'map',
            'sub_menu'=> null,
            'main_link'=>'map',
            'sort' => 17,
            'icon' => 'fa fa-globe'
        ],
        PermissionSlug::HEAT_MAP => [
            'name' => 'heat-map',
            'description' => 'View Heat Map',
            'main_menu'=>'map',
            'sub_menu'=> 'heat_map',
            'sub_link'=>'map/heatmap',
            'sort' => 16.1,
            'icon' => 'fa fa-circle-thin'
        ],
        PermissionSlug::MANAGE_PROMO => [
            'name' => 'manage-promo',
            'description' => 'View Promo code',
            'main_menu'=>'manage-promo',
            'sub_menu'=> null,
            'main_link'=>'promo',
            'sort' => 10,
            'icon' => 'fa fa-gift'
        ],
        PermissionSlug::MASTER => [
            'name' => 'view-all-master data',
            'description' => 'View All Master Data',
            'main_menu' => 'master',
            'sub_menu' => null,
            'sort' => 17,
            'icon' => 'fa fa-code-fork'
        ],
        PermissionSlug::MANAGE_CARMAKE => [
            'name' => 'manage-carmake',
            'description' => 'List carmake',
            'main_menu'=>'master',
            'sub_menu'=>'carmake',
            'sub_link'=>'carmake',
        ],
        PermissionSlug::MANAGE_CARMODEL => [
            'name' => 'manage-carmodel',
            'description' => 'List carmodel',
            'main_menu'=>'master',
            'sub_menu'=>'carmodel',
            'sub_link'=>'carmodel',
        ],
        PermissionSlug::MANAGE_NEEDED_DOC => [
            'name' => 'manage-needed-document',
            'description' => 'List driver needed documents',
            'main_menu'=>'master',
            'sub_menu'=>'needed_doc',
            'sub_link'=>'needed_doc',
        ], 
        PermissionSlug::MANAGE_OWNER_NEEDED_DOC => [
            'name' => 'manage-owner_needed-document',
            'description' => 'List owner needed documents',
            'main_menu'=>'master',
            'sub_menu'=>'owner_needed_doc',
            'sub_link'=>'owner_needed_doc',
        ],
         PermissionSlug::MANAGE_OWNER => [
            'name' => 'manage_owners',
            'description' => 'Owner list and create',
            'main_menu' => 'manage_owners',
            'sub_menu' => 'owner',
            'sort' => 3,
            'icon' => 'fa fa-briefcase',
            'main_link' => 'owners',
        ],
        PermissionSlug::CREATE_OWNER => [
            'name' => 'add-owner',
            'description' => 'Add Owner',
            'main_menu' => 'manage_owners',
            'sub_menu' => 'owner'
        ],
        PermissionSlug::EDIT_OWNER => [
            'name' => 'edit-owner',
            'description' => 'Edit Owner',
            'main_menu' => 'manage_owners',
            'sub_menu' => 'owner'
        ],
        PermissionSlug::DELETE_OWNER => [
            'name' => 'delete-owner',
            'description' => 'Delete Owner',
            'main_menu' => 'manage_owners',
            'sub_menu' => 'owner'
        ],
        PermissionSlug::TOGGLE_OWNER_STATUS => [
            'name' => 'toggle-owner-status',
            'description' => 'Toggle Owner Status',
            'main_menu' => 'manage_owners',
            'sub_menu' => 'owner'
        ],
         PermissionSlug::MANAGE_FLEET => [
            'name' => 'manage-fleet',
            'description' => 'Manage fleets add edit delete',
            'main_menu' => 'manage_fleet',
            'sub_menu' => null,
            'sort' => 18,
            'icon' => 'fa fa-taxi',
            'main_link' => 'fleets',
        ],
        PermissionSlug::CREATE_FLEET => [
            'name' => 'create-fleet',
            'description' => 'Create new fleet',
            'main_menu' => 'manage_fleet',
            'sub_menu' => 'manage_fleet'
        ],
        PermissionSlug::EDIT_FLEET => [
            'name' => 'edit-fleet',
            'description' => 'Edit fleet',
            'main_menu' => 'manage_fleet',
            'sub_menu' => 'manage_fleet'
        ],
        PermissionSlug::DELETE_FLEET => [
            'name' => 'delete-fleet',
            'description' => 'Delete fleet',
            'main_menu' => 'manage_fleet',
            'sub_menu' => 'manage_fleet'
        ],
        PermissionSlug::FLEET_TOGGLE_STATUS => [
            'name' => 'toggle-fleet-status',
            'description' => 'Change status of fleet',
            'main_menu' => 'manage_fleet',
            'sub_menu' => 'manage_fleet'
        ],
        PermissionSlug::FLEET_APPROVE_STATUS => [
            'name' => 'toggle-fleet-approval',
            'description' => 'Change Approve status of fleet',
            'main_menu' => 'manage_fleet',
            'sub_menu' => 'manage_fleet'
        ],
        // PermissionSlug::GET_ALL_PERMISSIONS => [
        //     'name' => 'Get-All-Permissions',
        //     'description' => 'Get all permissions',
        //     'main_menu'=>'settings',
        //     'sub_menu'=>'roles',
        // ],
];

    /**
     * List of all the roles to be created along with their permissions.
     *
     * @var array
     */
    protected $roles = [
        RoleSlug::SUPER_ADMIN => [
            'name' => 'Super Admin',
            'description' => 'Admin group with unrestricted access',
            'all' => true,
        ],
        RoleSlug::USER => [
            'name' => 'Normal User',
            'description' => 'Normal user with standard access',
            'permissions' => []
        ],
         RoleSlug::DEVELOPER => [
            'name' => 'Developer User',
            'description' => 'Normal user with standard access',
            'permissions' => [PermissionSlug::UPLOAD_BUILDS,PermissionSlug::VIEW_BUILDS]
        ],
        RoleSlug::CLIENT => [
            'name' => 'Client User',
            'description' => 'Normal user with standard access',
            'permissions' => [PermissionSlug::VIEW_BUILDS]
        ],
        RoleSlug::ADMIN => [
            'name' => 'Admin',
            'description' => 'Admin group with restricted access',
            'permissions' => [PermissionSlug::GET_ALL_ROLES, PermissionSlug::GET_ALL_PERMISSIONS,PermissionSlug::ACCESS_DASHBOARD,PermissionSlug::SETTINGS,PermissionSlug::VIEW_COMPANIES,PermissionSlug::DRIVERS_MENU,PermissionSlug::VIEW_DRIVERS,PermissionSlug::VIEW_TYPES,PermissionSlug::VIEW_ZONE,PermissionSlug::MAP_MENU,PermissionSlug::VIEW_SYSTEM_SETINGS,PermissionSlug::USER_MENU,PermissionSlug::VIEW_USERS,PermissionSlug::USER_SOS,PermissionSlug::SERVICE_LOCATION,PermissionSlug::ADMIN,PermissionSlug::DISPATCH_REQUEST,PermissionSlug::LIST_AIRPORTS,PermissionSlug::ADD_EDIT_AIRPORTS],
        ],
        RoleSlug::AREA_MANAGER => [
            'name' => 'Area Manager',
            'description' => 'Admin group with restricted access',
            'permissions' => [
                PermissionSlug::ACCESS_DASHBOARD
            ],
        ],
         RoleSlug::OWNER=>[
            'name' => 'Owner',
            'description' => 'Owner for company management',
            'permissions' => [
                 PermissionSlug::ACCESS_DASHBOARD,PermissionSlug::MANAGE_FLEET,PermissionSlug::CREATE_FLEET,PermissionSlug::EDIT_FLEET,PermissionSlug::DELETE_FLEET,PermissionSlug::FLEET_TOGGLE_STATUS,PermissionSlug::MANAGE_CARMODEL,
                     PermissionSlug::DRIVERS_MENU,PermissionSlug::VIEW_DRIVERS
            ],
        ],
        RoleSlug::DRIVER=>[
            'name' => 'Driver',
            'description' => 'Driver user with standard access',
            'permissions' => [],
        ],
        RoleSlug::DISPATCHER=>[
            'name' => 'Dispatcher',
            'description' => 'Dispatcher user with standard access',
            'permissions' => [],
        ],


    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::transaction(function () {
            foreach ($this->permissions as $permissionSlug => $attributes) {
                // Create permission if it doesn't exist
                Permission::firstOrCreate(['slug' => $permissionSlug], $attributes);
            }

            foreach ($this->roles as $roleSlug => $role) {
                // Create role if it doesn't exist
                $createdRole = Role::firstOrCreate(
                    ['slug' => $roleSlug],
                    array_merge(array_except($role, ['permissions']), ['locked' => true])
                );

                // Sync permissions
                if (isset($role['permissions'])) {
                    $rolePermissions = $role['permissions'];
                    $rolePermissionIds = Permission::whereIn('slug', $rolePermissions)->pluck('id');
                    $createdRole->permissions()->sync($rolePermissionIds);
                }
            }

            /**
             * Delete all unused permissions
             */
            $existingPermissions = Permission::pluck('slug')->toArray();

            $newPermissions = array_keys($this->permissions);

            $permissionsToDelete = array_diff($existingPermissions, $newPermissions);

            Permission::whereIn('slug', $permissionsToDelete)->delete();
        });
    }
}
