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
            <a href="{{url('project')}}">
                <button class="btn btn-danger btn-sm pull-right" type="submit">
                    <i class="mdi mdi-keyboard-backspace mr-2"></i>
                    @lang('view_pages.back')
                </button>
            </a>    
        </div>

<div class="col-sm-12">

<form  method="post" class="form-horizontal" action="{{url('project/update/client',$item->id)}}" enctype="multipart/form-data">
{{csrf_field()}}



<div class="row">
        <div class="col-sm-6">
            <div class="form-group">
            <label for="first_name">@lang('view_pages.first_name')</label>
            <input class="form-control" type="text" id="first_name" name="first_name" value="{{old('first_name',$item->name)}}" required="" placeholder="@lang('view_pages.enter_first_name')">
            <span class="text-danger">{{ $errors->first('first_name') }}</span>

        </div>
    </div>
            
    <div class="col-sm-6">
            <div class="form-group">
            <label for="last_name">@lang('view_pages.last_name')</label>
            <input class="form-control" type="text" id="last_name" name="last_name" value="{{old('last_name')}}" placeholder="@lang('view_pages.enter_last_name')">
            <span class="text-danger">{{ $errors->first('last_name') }}</span>

        </div>
    </div>
</div>



<div class="row">
       <div class="col-sm-6">
            <div class="form-group">
            <label for="name">@lang('view_pages.mobile')</label>
            <input class="form-control" type="text" id="mobile" name="mobile" value="{{old('mobile',$item->mobile)}}" required="" placeholder="@lang('view_pages.enter_mobile')">
            <span class="text-danger">{{ $errors->first('mobile') }}</span>

        </div>
    </div>

    <div class="col-sm-6">
            <div class="form-group">
            <label for="email">@lang('view_pages.email')</label>
            <input class="form-control" type="email" id="email" name="email" value="{{old('email',$item->email)}}" required="" placeholder="@lang('view_pages.enter_email')">
            <span class="text-danger">{{ $errors->first('email') }}</span>

        </div>
    </div>

</div>



<!-- <div class="form-group">
    <div class="col-6">
        <label for="profile_picture">@lang('view_pages.profile')</label><br>
        <img id="blah" src="#" alt=""><br>
        <input type="file" id="profile_picture" onchange="readURL(this)" name="profile_picture" style="display:none">
        <button class="btn btn-primary btn-sm" type="button" onclick="$('#profile_picture').click()" id="upload">Browse</button>
        <button class="btn btn-danger btn-sm" type="button" id="remove_img" style="display: none;">Remove</button><br>
        <span class="text-danger">{{ $errors->first('profile_picture') }}</span>
</div>
</div>
 -->    
<div class="form-group">
    <div class="col-12">
        <button class="btn btn-primary pull-right" type="submit">
            @lang('view_pages.update')
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

<script>
function getServiceLocation(details){
    var role = details.value;

    if(role == 'super-admin'){
        $('#serviceLocation').css('display','none');
        $('#service_location_id').prop('required',false);
    }
    else{
        $('#serviceLocation').css('display','block');
        $('#service_location_id').prop('required',true);
    }
}
</script>