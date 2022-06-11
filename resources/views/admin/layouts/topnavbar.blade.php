 <!-- Toastr css -->


 <!-- Top Bar Start -->


 <header class="main-header">
     <!-- Logo -->
     <a href="#" class="logo">
         <!-- mini logo -->
         <b class="logo-mini">
             <span class="light-logo" style="display: flex;align-items: end;"><img
                     src="{{ app_logo() ?? asset('images/email/logo.svg') }}" style="width: 26px;padding-right: 5px;"
                     alt="logo">{{ app_name() ?? 'Tagxi' }}</span>
             <span class="dark-logo" style="display: flex;align-items: end;"><img
                     src="{{ app_logo() ?? asset('images/email/logo.svg') }}" style="width: 26px;padding-right: 5px;"
                     alt="logo">{{ app_name() ?? 'Tagxi' }}</span>
         </b>
         <!-- logo-->
         <!--  <span class="logo-lg">
             <img src="{{ app_logo() ?? asset('assets/images/logo-light-text.png') }}" alt="logo" class="light-logo">
             <img src="{{ app_logo() ?? asset('assets/images/logo-dark-text.png') }}" alt="logo" class="dark-logo">
         </span> -->
     </a>
     <!-- Header Navbar -->
     <nav class="navbar navbar-static-top">
         <!-- Sidebar toggle button-->

         <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
             <span class="sr-only">Toggle navigation</span>
         </a>

         <div class="navbar-custom-menu">
             <ul class="nav navbar-nav">
                <li class="dropdown notifications-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <span class="sosicon">SOS Request</span>
                        {{-- <span class="badge badge-pill badge-danger">0</span> --}}
                        {{-- <i class="mdi mdi-bell-ring sosicon"></i> --}}
                    </a>
                    <ul class="dropdown-menu scale-up sosList">
                        
                    </ul>
                </li>
                 <li class="dropdown notifications-menu">
                     <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                         <i class="mdi mdi-google-translate"></i>
                     </a>
                     <ul class="dropdown-menu scale-up">
                         @foreach (config('app.app_lang') as $k => $v)
                             <a class="{{ $k == session()->get('applocale') ? 'hover-blue' : '' }} dropdown-item chooseLanguage"
                                 href="#" data-value="{{ $k }}">
                                 <li class="header">
                                     {{ ucfirst($v) }}
                                 </li>
                             </a>
                         @endforeach
                     </ul>
                 </li>
                 <!-- User Account-->
                 <li class="dropdown user user-menu">
                     <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                         <img src="{{ auth()->user()->profile_pic ?: asset('/assets/img/user-dummy.svg') }}"
                             class="user-image rounded-circle" alt="User Image">
                     </a>
                     <ul class="dropdown-menu scale-up">
                         <!-- User image -->
                         <li class="user-header d-flex">
                             <img src="{{ auth()->user()->profile_pic ?: asset('/assets/img/user-dummy.svg') }}"
                                 class="float-left rounded-circle" alt="User Image">

                             <p class="pt-1 pl-2">
                                 <span>{{ auth()->user()->name }}</span>
                                 <small class="mb-5">{{ auth()->user()->email }}</small>

                             </p>

                         </li>
                         <!-- Menu Body -->
                         <li class="user-body">
                             <div class="row no-gutters">
                                 <div class="col-12 text-left">
                                     <a href="{{ url('admins/profile', auth()->user()->id) }}"><i
                                             class="ion ion-person"></i> My Profile</a>
                                 </div>
                                 <div role="separator" class="divider col-12"></div>
                                 <div class="col-12 text-left">
                                     <a href="{{ url('api/spa/logout') }}" class="logout"><i
                                             class="fa fa-power-off"></i> Logout</a>
                                 </div>
                             </div>
                             <!-- /.row -->
                         </li>
                     </ul>
                 </li>

             </ul>
         </div>
     </nav>
 </header>
 <!-- Top Bar End -->
 <!-- Control Sidebar -->

 <!-- /.control-sidebar -->

 <!-- Add the sidebar's background. This div must be placed immediately after the control sidebar -->
 <!-- <div class="control-sidebar-bg"></div> -->
 