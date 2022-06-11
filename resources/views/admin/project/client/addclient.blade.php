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

<form  method="post" class="form-horizontal" action="{{url('project/store/client',$project_id)}}">
{{csrf_field()}}

<div class="row">
        <div class="col-sm-6">
                <div class="togglebutton">
                    <input type="checkbox" id="exisiting_client" name="exisiting_client"  >
                    <label for="exisiting_client">To Scelect Exisiting Client</label>
                </div>
                </div>
</div>
<div class="client-form" style="display: none">
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
</div>
<div class = 'client-select' style="display: none">
    <div class="row">
        <div class="col-6">
        <div class="form-group">
        <label for="client">@lang('view_pages.select_client')
            <span class="text-danger">*</span>
        </label>
        <select name="client" id="client" class="form-control" required="">
            <option value="" selected disabled>@lang('view_pages.select_client')</option>
            @foreach($clients_from_user as $key=>$client)
            <option value="{{$client->id}}" {{ old('client') == $client->id ? 'selected' : '' }}>{{$client->name}}</option>
            @endforeach
        </select>
        </div>
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
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
        <script type="text/javascript">
         $(document).on('click','#exisiting_client',function(){
                     console.info($(this).attr('id'));
                    let checked = $(this).prop('checked');

                    if(checked == true){
                        $('.client-form').css('display','none');
                        $('#first_name').attr('required',false);
                        $('#last_name').attr('required',false);
                        $('#mobile').attr('required',false);
                        $('#email').attr('required',false);
                        $('#password').attr('required',false);
                        $('#password_confirmation').attr('required',false);
                        $('#client').attr('required',true);
                        $('.client-select').css('display','block');

                    }else{
                        $('.client-form').css('display','none');
                        $('#first_name').attr('required',true);
                        $('#last_name').attr('required',true);
                        $('#mobile').attr('required',true);
                        $('#email').attr('required',true);
                        $('#password').attr('required',true);
                        $('#password_confirmation').attr('required',true);
                        $('#client').attr('required',false);
                        $('.client-select').css('display','none');
                        $('.client-form').css('display','block');
                    }
                });
                    $(document).ready(function() {

                    let checked = $('#exisiting_client').prop('checked');

                    if(checked == true){
                         $('.client-form').css('display','none');
                        $('#first_name').attr('required',false);
                        $('#last_name').attr('required',false);
                        $('#mobile').attr('required',false);
                        $('#email').attr('required',false);
                        $('#password').attr('required',false);
                        $('#password_confirmation').attr('required',false);
                        $('#client').attr('required',true);
                        $('.client-select').css('display','block');

                    }else{
                        $('.client-form').css('display','none');
                        $('#first_name').attr('required',true);
                        $('#last_name').attr('required',true);
                        $('#mobile').attr('required',true);
                        $('#email').attr('required',true);
                        $('#password').attr('required',true);
                        $('#password_confirmation').attr('required',true);
                        $('#client').attr('required',false);
                        $('.client-select').css('display','none');
                        $('.client-form').css('display','block');
                    }
                });
</script>
