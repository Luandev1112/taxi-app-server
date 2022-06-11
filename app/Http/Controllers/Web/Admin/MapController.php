<?php

namespace App\Http\Controllers\Web\Admin;

use App\Models\Admin\Zone;
use Illuminate\Http\Request;
use App\Base\Constants\Auth\Role;
use App\Http\Controllers\Controller;
use App\Models\Admin\ServiceLocation;
use App\Models\Request\Request as RequestRequest;

class MapController extends Controller
{
    public function heatMapView(Request $request)
    {
        $page = trans('pages_names.heat_map');

        $main_menu = 'manage-map';
        $sub_menu = 'heat_map';

        if ($request->has('zone_id')) {
            $results = RequestRequest::companyKey()->whereHas('zoneType.zone', function ($q) use ($request) {
                $q->where('id', $request->zone_id);
            })->get();

        } else {
            $results = RequestRequest::companyKey()->get();
        }

        $serviceLocation = ServiceLocation::companyKey()->active()->get();

        return view('admin.map.heatmap', compact('page', 'main_menu', 'sub_menu', 'results', 'serviceLocation'));
    }

    public function getZoneByServiceLocation(Request $request)
    {
        $id = $request->id;

        return Zone::active()->whereServiceLocationId($id)->get();
    }

    public function mapView()
    {
        $page = trans('pages_names.map');
        $main_menu = 'manage-map';
        $sub_menu = 'map';

        $default_lat = env('DEFAULT_LAT');
        $default_lng = env('DEFAULT_LNG');

        $zone = Zone::active()->companyKey()->first();

        if ($zone) {
            if (access()->hasRole(Role::SUPER_ADMIN)) {
            } else {
                $admin_detail = auth()->user()->admin;
                $zone = $admin_detail->serviceLocationDetail->zones()->first();
            }

            $coordinates = $zone->coordinates->toArray();

            $multi_polygon = [];

            foreach ($coordinates as $key => $coordinate) {
                $polygon = [];
                foreach ($coordinate[0] as $key => $point) {
                    $pp = new \stdClass;
                    $pp->lat = $point->getLat();
                    $pp->lng = $point->getLng();
                    $polygon [] = $pp;
                }
                $multi_polygon[] = $polygon;
            }

            $default_lat = $polygon[0]->lat;
            $default_lng = $polygon[0]->lng;
        }



        return view('admin.map.map', compact('page', 'main_menu', 'sub_menu', 'default_lat', 'default_lng'));
    }

    public function mapViewMapbox()
    {
         $page = trans('pages_names.map');
        $main_menu = 'manage-map';
        $sub_menu = 'map-mapbox';

        $default_lat = env('DEFAULT_LAT');
        $default_lng = env('DEFAULT_LNG');

        $zone = Zone::active()->companyKey()->first();

        if ($zone) {
            if (access()->hasRole(Role::SUPER_ADMIN)) {
            } else {
                $admin_detail = auth()->user()->admin;
                $zone = $admin_detail->serviceLocationDetail->zones()->first();
            }

            $coordinates = $zone->coordinates->toArray();

            $multi_polygon = [];

            foreach ($coordinates as $key => $coordinate) {
                $polygon = [];
                foreach ($coordinate[0] as $key => $point) {
                    $pp = new \stdClass;
                    $pp->lat = $point->getLat();
                    $pp->lng = $point->getLng();
                    $polygon [] = $pp;
                }
                $multi_polygon[] = $polygon;
            }

            $default_lat = $polygon[0]->lat;
            $default_lng = $polygon[0]->lng;
        }



        return view('admin.map.map-mapbox', compact('page', 'main_menu', 'sub_menu', 'default_lat', 'default_lng'));

    }
}
