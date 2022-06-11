<?php

namespace App\Base\Constants\Auth;

class Permission
{
    const GET_ALL_ROLES = 'get-all-roles';
    const CREATE_ROLES = 'create-roles';
    const ASSIGN_PERMISSIONS = 'assign-permissions';
    const GET_ALL_PERMISSIONS = 'get-all-permissions';
    const SETTINGS = 'view-settings';
    const ACCESS_DASHBOARD = 'access-dashboard';
    // Company permissions
    const VIEW_COMPANIES ='view-companies';
    const ADD_COMPANY = 'add-company';
    const UPDATE_COMPANY = 'update-company';
    const DELETE_COMPANY = 'delete-company';
    const DRIVERS_MENU = 'drivers-menu';
    const VIEW_DRIVERS = 'view-drivers';
    const ADD_DRIVERS = 'add-drivers';
    const UPDATE_DRIVERS = 'update-drivers';
    const DELETE_DRIVERS = 'delete-drivers';
    const VIEW_TYPES = 'view-types';
    const ADD_TYPES = 'add-types';
    const UPDATE_TYPES = 'update-types';
    const DELETE_TYPES = 'delete-types';
    const MAP_MENU = 'map-menu';
    const VIEW_ZONE = 'view-zone';
    const ADD_ZONE = 'add-zone';
    const VIEW_SYSTEM_SETINGS = 'view-system-settings';
    const USER_MENU = 'user-menu';
    const VIEW_USERS = 'view-users';
    const USER_SOS = 'view-sos';
    const SERVICE_LOCATION = 'service_location';
    const ADMIN = 'admin';
    const DISPATCH_REQUEST = 'dispatch-request';
    const UPLOAD_BUILDS = 'upload-builds';
    const VIEW_BUILDS = 'view-builds';
    const VIEW_REQUEST = 'view-requests';
    const MANAGE_FAQ = 'manage-faq';
    const CANCELLATION_REASON = 'cancellation-reason';
    const COMPLAINTS = 'complaints';
    const COMPLAINT_TITLE = 'complaint-title';
    const REPORTS = 'reports';
    const USER_REPORT = 'user-report';
    const DRIVER_REPORT = 'driver-report';
    const TRAVEL_REPORT = 'travel-report';
    const MANAGE_MAP = 'manage-map';
    const HEAT_MAP = 'heat-map';
    const MANAGE_PROMO = 'manage-promo';
    const USER_COMPLAINT = 'user-complaint';
    const DRIVER_COMPLAINT = 'driver-complaint';
    const MASTER = 'master-data';
    const MANAGE_CARMAKE = 'manage-carmake';
    const MANAGE_CARMODEL = 'manage-carmodel';
    const MANAGE_NEEDED_DOC = 'manage-needed-document';
    const MANAGE_OWNER_NEEDED_DOC = 'manage-owner-needed-document';

    // Manage Owner
    const MANAGE_OWNER = 'manage-owner';
    const CREATE_OWNER = 'add-owner';
    const EDIT_OWNER = 'edit-owner';
    const DELETE_OWNER = 'delete-owner';
    const TOGGLE_OWNER_STATUS = 'toggle-owner-status';

    // Manage Fleets
    const MANAGE_FLEET = 'manage-fleet';
    const CREATE_FLEET = 'add-fleet';
    const EDIT_FLEET = 'edit-fleet';
    const DELETE_FLEET = 'delete-fleet';
    const FLEET_TOGGLE_STATUS = 'toggle-fleet-status';
    const FLEET_APPROVE_STATUS = 'toggle-fleet-approval';

    // Ariport Slugs
    const LIST_AIRPORTS = 'list-airports';
    const ADD_EDIT_AIRPORTS = 'add-and-edit-airport';

}
