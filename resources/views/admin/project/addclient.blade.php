@extends('admin.layouts.app')
@section('title', 'Main page')

@section('content')

<!-- Start Page content -->
<div class="content">
<div class="container-fluid">
    @if($errors)
    @foreach ($errors->all() as $error)
       <div>{{ $error }}</div>
   @endforeach
 @endif
<div class="row">
<div class="col-sm-12">
    <div class="box">
        <div class="box-header with-border">
            <a href="{{url('admins')}}">
                <button class="btn btn-danger btn-sm pull-right" type="submit">
                    <i class="mdi mdi-keyboard-backspace mr-2"></i>
                    @lang('view_pages.back')
                </button>
            </a>    
        </div>

<div class="col-sm-12">

<form  method="post" class="form-horizontal" action="{{url('project/store/client',$project_id)}}" enctype="multipart/form-data">
{{csrf_field()}}



<div class="row">
        <div class="col-sm-6">
            <div class="form-group">
            <label for="first_name">@lang('view_pages.first_name')</label>
            <input class="form-control" type="text" id="first_name" name="first_name" value="{{old('first_name')}}" required="" placeholder="@lang('view_pages.enter_first_name')">
            <span class="text-danger">{{ $errors->first('first_name') }}</span>

        </div>
    </div>
            
    <div class="col-sm-6">
            <div class="form-group">
            <label for="last_name">@lang('view_pages.last_name')</label>
            <input class="form-control" type="text" id="last_name" name="last_name" value="{{old('last_name')}}" required="" placeholder="@lang('view_pages.enter_last_name')">
            <span class="text-danger">{{ $errors->first('last_name') }}</span>

        </div>
    </div>
</div>



<div class="row">
       <div class="col-sm-6">
            <div class="form-group">
            <label for="name">@lang('view_pages.mobile')</label>
            <input class="form-control" type="text" id="mobile" name="mobile" value="{{old('mobile')}}" required="" placeholder="@lang('view_pages.enter_mobile')">
            <span class="text-danger">{{ $errors->first('mobile') }}</span>

        </div>
    </div>

    <div class="col-sm-6">
            <div class="form-group">
            <label for="email">@lang('view_pages.email')</label>
            <input class="form-control" type="email" id="email" name="email" value="{{old('email')}}" required="" placeholder="@lang('view_pages.enter_email')">
            <span class="text-danger">{{ $errors->first('email') }}</span>

        </div>
    </div>

</div>


<div class="row">
    <div class="col-sm-6">
         <div class="form-group">
         <label for="password">@lang('view_pages.password')</label>
         <input class="form-control" type="password" id="password" name="password" value="" required="" placeholder="@lang('view_pages.enter_password')">
         <span class="text-danger">{{ $errors->first('password') }}</span>

     </div>
 </div>

 <div class="col-sm-6">
         <div class="form-group">
         <label for="password_confrim">@lang('view_pages.confirm_password')</label>
         <input class="form-control" type="password" id="password_confirmation" name="password_confirmation" value="" required="" placeholder="@lang('view_pages.enter_password_confirmation')">
         <span class="text-danger">{{ $errors->first('password') }}</span>

     </div>
 </div>
</div>

  
<div class="form-group">
    <div class="col-12">
        <button class="btn btn-primary pull-right" type="submit">
            @lang('view_pages.save')
        </button>
    </div>
</div>

</form>

            </div>
        </div>


    </div>
</div>
</div>

</div>
<!-- container -->

</div>
<!-- content -->

@endsection

