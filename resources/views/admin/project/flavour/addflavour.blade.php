@extends('admin.layouts.app')
@section('title', 'Main page')

@section('content')
    	
    
<!-- Start Page content -->
<div class="content">
<div class="container-fluid">

<div class="row">
<div class="col-sm-12">
    <div class="box">

        <div class="box-header with-border">
            <a href="{{ url('project/added/flavour',$project_id) }}">
                <button class="btn btn-danger btn-sm pull-right" type="submit">
                    <i class="mdi mdi-keyboard-backspace mr-2"></i>
                    @lang('view_pages.back')
                </button>
            </a>
        </div>

<div class="col-sm-12">

<form  method="post" class="form-horizontal" action="{{url('project/store/flavour',$project_id)}}" enctype="multipart/form-data">
@csrf

    <div class="row">
        <div class="col-sm-6">
            <div class="form-group">
            <label for="flavour_name">@lang('view_pages.flavour_name')</label>
            <input class="form-control" type="text" id="flavour_name" name="flavour_name" value="{{old('flavour_name')}}" required="" placeholder="@lang('view_pages.enter_flavour_name')">
            <span class="text-danger">{{ $errors->first('flavour_name') }}</span>

        </div>
    </div>
    
    <div class="col-sm-6">
            <div class="form-group">
            <label for="app_name">@lang('view_pages.app_name') [For IOS]</label>
            <input class="form-control" type="text" id="app_name" name="app_name" value="{{old('app_name')}}" placeholder="@lang('view_pages.enter_app_name')">
            <span class="text-danger">{{ $errors->first('app_name') }}</span>

        </div>
    </div>

    <div class="col-sm-6">
            <div class="form-group">
            <label for="bundle_identifier">@lang('view_pages.bundle_identifier') [For IOS]</label>
            <input class="form-control" type="text" id="bundle_identifier" name="bundle_identifier" value="{{old('bundle_identifier')}}"  placeholder="@lang('view_pages.enter_bundle_identifer')">
            <span class="text-danger">{{ $errors->first('bundle_identifier') }}</span>

        </div>
    </div>

</div>


    <div class="form-group">
        <div class="col-12">
            <button class="btn btn-primary pull-right m-5" type="submit">
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
