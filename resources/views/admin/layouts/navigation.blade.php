<aside class="main-sidebar">
  <!-- sidebar-->
  <section class="sidebar">
    <!-- sidebar menu-->
    <ul class="sidebar-menu" data-widget="tree">
      @if(auth()->user()->can('access-dashboard'))
      <li class="{{'dashboard' == $main_menu ? 'active' : '' }}">
        <a href="{{url('/dashboard')}}">
          <i class="fa fa-tachometer"></i> <span>@lang('pages_names.dashboard')</span>
        </a>
      </li>
      @endif

      @if(auth()->user()->can('access-dashboard') && env('APP_FOR')=='demo1')
      <li class="{{'admin_dashboard' == $main_menu ? 'active' : '' }}">
        <a href="{{url('/admin_dashboard')}}">
          <i class="fa fa-tachometer"></i> <span>@lang('pages_names.admin-dashboard')</span>
        </a>
      </li>
      <li class="{{'driver_profile_dashboard' == $main_menu ? 'active' : '' }}">
        <a href="{{url('/driver_profile_dashboard')}}">
          <i class="fa fa-tachometer"></i> <span>Driver profile Dashboard</span>
        </a>
      </li>
      @endif
         
       @if(auth()->user()->can('view-settings'))
      <li class="treeview {{ 'settings' == $main_menu ? 'active menu-open' : '' }}">
        <a href="javascript: void(0);">
          <i class="fa fa-cogs"></i>
          <span> @lang('pages_names.settings') </span>
          <span class="pull-right-container">
            <i class="fa fa-angle-right pull-right"></i>
          </span>
        </a>

        <ul class="treeview-menu">
          @if(auth()->user()->can('get-all-roles'))
          <li class="{{ 'roles' == $sub_menu ? 'active' : '' }}">
            <a href="{{url('/roles')}}"><i class="fa fa-circle-thin"></i>@lang('pages_names.roles')</a>
          </li>
          @endif
          @if(auth()->user()->can('view-system-settings'))
          <li class="{{ 'system_settings' == $sub_menu ? 'active' : '' }}">
            <a href="{{url('/system/settings')}}"><i class="fa fa-circle-thin"></i>@lang('pages_names.system_settings')</a>
          </li>
          @endif
          @if(auth()->user()->can('view-system-settings'))
          <li class="{{ 'translations' == $sub_menu ? 'active' : '' }}">
            <a href="{{url('/translations')}}"><i class="fa fa-circle-thin"></i>@lang('pages_names.translations')</a>
          </li>
          @endif
        </ul>
      </li>
      @endif

      @if(auth()->user()->can('master-data'))
      <li class="treeview {{ 'master' == $main_menu ? 'active menu-open' : '' }}">
        <a href="javascript: void(0);">
          <i class="fa fa-code-fork"></i>
          <span> @lang('pages_names.master') </span>
          <span class="pull-right-container">
            <i class="fa fa-angle-right pull-right"></i>
          </span>
        </a>

        <ul class="treeview-menu">
          @if(auth()->user()->can('manage-carmake'))
          <li class="{{ 'car_make' == $sub_menu ? 'active' : '' }}">
            <a href="{{url('/carmake')}}"><i class="fa fa-circle-thin"></i>@lang('pages_names.carmake')</a>
          </li>
          @endif
          @if(auth()->user()->can('manage-carmodel'))
          <li class="{{ 'car_model' == $sub_menu ? 'active' : '' }}">
            <a href="{{url('/carmodel')}}"><i class="fa fa-circle-thin"></i>@lang('pages_names.carmodel')</a>
          </li>
          @endif
          @if(auth()->user()->can('manage-needed-document'))
          <li class="{{ 'needed_document' == $sub_menu ? 'active' : '' }}">
            <a href="{{url('/needed_doc')}}"><i class="fa fa-circle-thin"></i>@lang('pages_names.needed_doc')</a>
          </li>
          @endif  
          @if(auth()->user()->can('manage-owner-needed-document'))
          <li class="{{ 'owner_needed_document' == $sub_menu ? 'active' : '' }}">
            <a href="{{url('/owner_needed_doc')}}"><i class="fa fa-circle-thin"></i>@lang('pages_names.owner_needed_doc')</a>
          </li>
          @endif 
          @if(auth()->user()->can('package-type'))
          <li class="{{ 'package_type' == $sub_menu ? 'active' : '' }}">
            <a href="{{url('/package_type')}}"><i class="fa fa-circle-thin"></i>@lang('pages_names.package_type')</a>
          </li>
          @endif
        </ul>
      </li>
      @endif

      @if(auth()->user()->can('service_location'))
      <li class="{{'service_location' == $main_menu ? 'active' : '' }}">
        <a href="{{url('/service_location')}}">
          <i class="fa fa-map-marker"></i> <span>@lang('pages_names.service_location')</span>
        </a>
      </li>
      @endif

      @php
        $areas = App\Models\Admin\ServiceLocation::companyKey()->active(true)->get();
      @endphp

        @if(auth()->user()->can('manage-owner'))
        <li class="treeview {{ 'manage_owners' == $main_menu ? 'active menu-open' : '' }}">
        <a href="javascript: void(0);">
          <i class="fa fa-code-fork"></i>
          <span> @lang('pages_names.owners') </span>
          <span class="pull-right-container">
            <i class="fa fa-angle-right pull-right"></i>
          </span>
        </a>
         <ul class="treeview-menu">
         @foreach ($areas as $item)
          <li class="{{ $sub_menu == $item->name ? 'active' : '' }}" data-id="{{ $item->id }}">
            <a href="{{url('/owners/by_area',$item->id)}}"><i class="fa fa-circle-thin"></i>{{ ucfirst($item->name) }}</a>
          </li>
           @endforeach
         </ul>
           
            </li>
        
            @endif

    
             @if(auth()->user()->can('manage-fleet'))
            <li class="{{ $main_menu == 'manage_fleet' ? 'active' : ''}}">
                <a href="{{ route('viewFleet') }}">
                    <i class="fa fa-bus"></i>
                    <span> {{ trans('pages_names.manage_fleet') }} </span>
                </a>
            </li>
            @endif

      @if(auth()->user()->can('admin'))
      <li class="{{'admin' == $main_menu ? 'active' : '' }}">
        <a href="{{url('/admins')}}">
          <i class="fa fa-user-circle-o"></i> <span>@lang('pages_names.admins')</span>
        </a>
      </li>
      @endif
     {{--  @if(auth()->user()->can('view-requests'))
      <li class="{{'request' == $main_menu ? 'active' : '' }}">
        <a href="{{url('/requests')}}">
          <i class="fa fa-tasks"></i> <span>@lang('pages_names.request')</span>
        </a>
      </li>
      @endif --}}
       @if(auth()->user()->can('view-requests'))
      <li class="treeview {{ 'trip-request' == $main_menu ? 'active menu-open' : '' }}">
        <a href="javascript: void(0);">
          <i class="fa fa-map"></i>
          <span> @lang('pages_names.request') </span>
          <span class="pull-right-container">
            <i class="fa fa-angle-right pull-right"></i>
          </span>
        </a>

        <ul class="treeview-menu">
         
          <li class="{{'request' == $sub_menu ? 'active' : '' }}">
            <a href="{{url('/requests')}}">
              <i class="fa fa-circle-thin"></i> <span>@lang('pages_names.rides')</span>
            </a>
          </li>
          <li class="{{'scheduled-rides' == $sub_menu ? 'active' : '' }}">
            <a href="{{url('/scheduled-rides')}}">
              <i class="fa fa-circle-thin"></i> <span>@lang('pages_names.scheduled_rides')</span>
            </a>
          </li>
          <li class="{{'cancellation-rides' == $sub_menu ? 'active' : '' }}">
            <a href="{{url('/cancellation-rides')}}">
              <i class="fa fa-circle-thin"></i> <span>@lang('pages_names.cancellation_rides')</span>
            </a>
          </li>
        
        </ul>
      </li>
       @endif 
      @if(auth()->user()->can('view-types'))
      <li class="{{'types' == $main_menu ? 'active' : '' }}">
        <a href="{{url('/types')}}">
          <i class="fa fa-taxi "></i> <span>@lang('pages_names.types')</span>
        </a>
      </li>
      @endif
      @if(auth()->user()->can('map-menu'))
      <li class="treeview {{ 'map' == $main_menu ? 'active menu-open' : '' }}">
        <a href="javascript: void(0);">
          <i class="fa fa-map"></i>
          <span> @lang('pages_names.map') </span>
          <span class="pull-right-container">
            <i class="fa fa-angle-right pull-right"></i>
          </span>
        </a>

        <ul class="treeview-menu">
          @if(auth()->user()->can('view-zone'))
          <li class="{{ 'zone' == $sub_menu ? 'active' : '' }}">
            <a href="{{url('/zone')}}"><i class="fa fa-circle-thin"></i>@lang('pages_names.zone')</a>
          </li>
          @endif
          @if(auth()->user()->can('list-airports'))
          <li class="{{ 'airport' == $sub_menu ? 'active' : '' }}">
            <a href="{{url('/airport')}}"><i class="fa fa-circle-thin"></i>@lang('pages_names.airport')</a>
          </li>
          @endif
        </ul>
      </li>
      @endif

       @if(auth()->user()->can('drivers-menu'))
            @if (auth()->user()->hasRole('owner'))
                @php
                    $route = 'company/drivers';
                @endphp
            @else
                @php
                    $route = 'drivers';
                @endphp
            @endif

     
      <li class="treeview {{ 'drivers' == $main_menu ? 'active menu-open' : '' }}">
        <a href="javascript: void(0);">
          <i class="fa fa-users"></i>
          <span> @lang('pages_names.drivers') </span>
          <span class="pull-right-container">
            <i class="fa fa-angle-right pull-right"></i>
          </span>
        </a>

        <ul class="treeview-menu">
          @if(auth()->user()->can('view-drivers'))
          <li class="{{ 'driver_details' == $sub_menu ? 'active' : '' }}">
            <a href="{{url($route)}}"><i class="fa fa-circle-thin"></i>@lang('pages_names.driver_details')</a>
          </li>
          @endif
          @if(auth()->user()->can('view-drivers'))
          <li class="{{ 'driver_ratings' == $sub_menu ? 'active' : '' }}">
            <a href="{{url('/driver-ratings')}}"><i class="fa fa-circle-thin"></i>@lang('pages_names.driver_ratings')</a>
          </li>
          @endif
          @if(auth()->user()->can('view-drivers'))
          <li class="{{ 'withdrawal_requests' == $sub_menu ? 'active' : '' }}">
            <a href="{{url('/withdrawal-requests-lists')}}"><i class="fa fa-circle-thin"></i>@lang('pages_names.withdrawal_requests')</a>
          </li>
          @endif          


        </ul> 
       
      </li>
      @endif

      @if(auth()->user()->can('user-menu'))
      <li class="treeview {{ 'users' == $main_menu ? 'active menu-open' : '' }}">
        <a href="javascript: void(0);">
          <i class="fa fa-user"></i>
          <span> @lang('pages_names.users') </span>
          <span class="pull-right-container">
            <i class="fa fa-angle-right pull-right"></i>
          </span>
        </a>

        <ul class="treeview-menu">
          @if(auth()->user()->can('view-users'))
          <li class="{{ 'user_details' == $sub_menu ? 'active' : '' }}">
            <a href="{{url('/users')}}"><i class="fa fa-circle-thin"></i>@lang('pages_names.user_details')</a>
          </li>
          @endif
        </ul>
      </li>
      @endif

      @if(auth()->user()->can('view-sos'))
      <li class="{{'emergency_number' == $main_menu ? 'active' : '' }}">
        <a href="{{url('/sos')}}">
          <i class="fa fa-heartbeat"></i> <span>@lang('pages_names.emergency_number')</span>
        </a>
      </li>
      @endif

      @if(auth()->user()->can('manage-promo'))
      <li class="{{'manage-promo' == $main_menu ? 'active' : '' }}">
        <a href="{{url('/promo')}}">
          <i class="fa fa-gift"></i> <span>@lang('pages_names.promo_codes')</span>
        </a>
      </li>
      @endif

      @if(auth()->user()->can('complaint-title'))
      <li class="treeview {{ 'notifications' == $main_menu ? 'active menu-open' : '' }}">
        <a href="javascript: void(0);">
          <i class="fa fa-paper-plane"></i>
          <span> @lang('pages_names.notifications') </span>
          <span class="pull-right-container">
            <i class="fa fa-angle-right pull-right"></i>
          </span>
        </a>

        <ul class="treeview-menu">
          @if(auth()->user()->can('complaint-title'))
          <li class="{{ 'push_notification' == $sub_menu ? 'active' : '' }}">
            <a href="{{url('/notifications/push')}}"><i class="fa fa-circle-thin"></i>@lang('pages_names.push_notification')</a>
          </li>
          @endif
        </ul>
      </li>
      @endif

      @if(auth()->user()->can('cancellation-title'))
      <li class="{{'cancellation-reason' == $main_menu ? 'active' : '' }}">
        <a href="{{url('/cancellation')}}">
          <i class="fa fa-ban"></i> <span>@lang('pages_names.cancellation')</span>
        </a>
      </li> 

     
      @endif

      @if(auth()->user()->can('complaint-title'))
      <li class="treeview {{ 'complaints' == $main_menu ? 'active menu-open' : '' }}">
        <a href="javascript: void(0);">
          <i class="fa fa-list-alt"></i>
          <span> @lang('pages_names.complaints') </span>
          <span class="pull-right-container">
            <i class="fa fa-angle-right pull-right"></i>
          </span>
        </a>

        <ul class="treeview-menu">
          @if(auth()->user()->can('complaint-title'))
          <li class="{{ 'complaint-title' == $sub_menu ? 'active' : '' }}">
            <a href="{{url('/complaint/title')}}"><i class="fa fa-circle-thin"></i>@lang('pages_names.complaint_title')</a>
          </li>
          @endif

          @if(auth()->user()->can('user-complaint'))
          <li class="treeview {{ 'user-complaint' == $sub_menu ? 'active' : '' }}">
             <a href="javascript: void(0);">
                <i class="fa fa-circle-thin"></i>
                <span> @lang('pages_names.user_complaints') </span>
                <span class="pull-right-container">
                  <i class="fa fa-angle-right pull-right"></i>
                </span>
              </a>

          {{--   <a href="{{url('/complaint/users')}}"><i class="fa fa-circle-thin"></i>@lang('pages_names.user_complaints')</a> --}}
             <ul class="treeview-menu">
               <li class="{{ 'user-general-complaint' == $sub_menu ? 'active' : '' }}">
            <a href="{{url('/complaint/users/general')}}">@lang('pages_names.general_complaints')</a></li>
            
               <li class="{{ 'user-request-complaint' == $sub_menu ? 'active' : '' }}">
            <a href="{{url('/complaint/users/request')}}">@lang('pages_names.request_complaints')</a></li>
             </ul>
          </li>
          @endif

          @if(auth()->user()->can('driver-complaint'))
          <li class="treeview {{ 'driver-complaint' == $sub_menu ? 'active' : '' }}">
             <a href="javascript: void(0);">
                <i class="fa fa-circle-thin"></i>
                <span> @lang('pages_names.driver_complaints') </span>
                <span class="pull-right-container">
                  <i class="fa fa-angle-right pull-right"></i>
                </span>
              </a>
           {{--  <a href="{{url('/complaint/drivers')}}"><i class="fa fa-circle-thin"></i>@lang('pages_names.driver_complaints')</a> --}}

            <ul class="treeview-menu">
               <li class="{{ 'driver-general-complaint' == $sub_menu ? 'active' : '' }}">
            <a href="{{url('/complaint/drivers/general')}}">@lang('pages_names.general_complaints')</a></li>
            
               <li class="{{ 'driver-request-complaint' == $sub_menu ? 'active' : '' }}">
            <a href="{{url('/complaint/drivers/request')}}">@lang('pages_names.request_complaints')</a></li>
             </ul>
          </li>
          @endif
        </ul>
      </li>
      @endif


      @if(auth()->user()->can('reports'))
      <li class="treeview {{ 'reports' == $main_menu ? 'active menu-open' : '' }}">
        <a href="javascript: void(0);">
          <i class="fa fa-file-pdf-o"></i>
          <span> @lang('pages_names.reports') </span>
          <span class="pull-right-container">
            <i class="fa fa-angle-right pull-right"></i>
          </span>
        </a>

        <ul class="treeview-menu">
          @if(auth()->user()->can('user-report'))
          <li class="{{ 'user_report' == $sub_menu ? 'active' : '' }}">
            <a href="{{url('/reports/user')}}"><i class="fa fa-circle-thin"></i>@lang('pages_names.user_report')</a>
          </li>
          @endif

          @if(auth()->user()->can('driver-report'))
          <li class="{{ 'driver_report' == $sub_menu ? 'active' : '' }}">
            <a href="{{url('/reports/driver')}}"><i class="fa fa-circle-thin"></i>@lang('pages_names.driver_report')</a>
          </li>
          @endif
          @if(auth()->user()->can('driver-report'))
         <!--  <li class="{{ 'driver_duties_report' == $sub_menu ? 'active' : '' }}">
            <a href="{{url('/reports/driver-duties')}}"><i class="fa fa-circle-thin"></i>@lang('pages_names.driver_duties_report')</a>
          </li> -->
          @endif

          @if(auth()->user()->can('travel-report'))
          <li class="{{ 'travel_report' == $sub_menu ? 'active' : '' }}">
            <a href="{{url('/reports/travel')}}"><i class="fa fa-circle-thin"></i>@lang('pages_names.travel_report')</a>
          </li>
          @endif
        </ul>
      </li>
      @endif

      @if(auth()->user()->can('manage-map'))
      <li class="treeview {{ 'manage-map' == $main_menu ? 'active menu-open' : '' }}">
        <a href="javascript: void(0);">
          <i class="fa fa-globe"></i>
          <span> @lang('pages_names.manage-map') </span>
          <span class="pull-right-container">
            <i class="fa fa-angle-right pull-right"></i>
          </span>
        </a>

        <ul class="treeview-menu">
          @if(auth()->user()->can('heat-map'))
          <li class="{{ 'heat_map' == $sub_menu ? 'active' : '' }}">
            <a href="{{url('map/heatmap')}}"><i class="fa fa-circle-thin"></i>@lang('pages_names.heat_map')</a>
          </li>
          @endif

          @if(auth()->user()->can('heat-map'))
          <li class="{{ 'map' == $sub_menu ? 'active' : '' }}">
            <a href="{{route('mapView')}}"><i class="fa fa-circle-thin"></i>@lang('pages_names.map_view')</a>
          </li>

         {{--  <li class="{{ 'map-mapbox' == $sub_menu ? 'active' : '' }}">
            <a href="{{route('mapViewMapbox')}}"><i class="fa fa-circle-thin"></i>@lang('pages_names.map_view_mapbox')</a>
          </li> --}}
          @endif
        </ul>
      </li>
      @endif

      @if(auth()->user()->can('manage-faq'))
      <li class="{{'faq' == $main_menu ? 'active' : '' }}">
        <a href="{{url('/faq')}}">
          <i class="fa fa-question-circle"></i> <span>@lang('pages_names.faq')</span>
        </a>
      </li>
      @endif

      <!--  @if(auth()->user()->can('view-companies'))
          <li class="{{'company' == $main_menu ? 'active' : '' }}">
            <a href="{{url('/company')}}">
              <i class="fa fa-building"></i> <span>@lang('pages_names.company')</span>
            </a>
          </li>
        @endif -->


      <!--
          @if(access()->hasRole('super-admin'))
          <li class="{{'project' == $main_menu ? 'active' : '' }}">
            <a href="{{url('/project')}}">
              <i class="fa fa-file"></i> <span>@lang('pages_names.project')</span>
            </a>
          </li>
          @endif -->
      <!--  @if(access()->hasRole('super-admin'))
          <li class="{{'developer' == $main_menu ? 'active' : '' }}">
            <a href="{{url('/developer')}}">
              <i class="fa fa-users"></i> <span>@lang('pages_names.developers')</span>
            </a>
          </li>
          @endif -->


      <!--         @if(auth()->user()->can('view-builds'))
          <li class="treeview {{ 'builds' == $main_menu ? 'active menu-open' : '' }}">
            <a href="javascript: void(0);">
              <i class="fa fa-share"></i>
              <span> Builds </span>
              <span class="pull-right-container">
                <i class="fa fa-angle-right pull-right"></i>
              </span>
            </a>

            <ul class="treeview-menu">
              @if(auth()->user()->can('view-builds'))
                <li class="{{ 'list_builds' == $sub_menu ? 'active' : '' }}">
                    <a href="{{url('builds/projects')}}"><i class="fa fa-list"></i> Build Lists</a>
                </li>
                @endif
              @if(auth()->user()->can('upload-builds'))
                <li class="{{ 'upload_builds' == $sub_menu ? 'active' : '' }}">
                    <a href="{{url('/builds/create')}}"><i class="fa fa-plus"></i>Upload Builds</a>
                </li>
                @endif

            </ul>
        </li>
        @endif -->
    </ul>
  </section>
</aside>
