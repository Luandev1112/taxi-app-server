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
            <a href="{{ url('project') }}">
                <button class="btn btn-danger btn-sm pull-right" type="submit">
                    <i class="mdi mdi-keyboard-backspace mr-2"></i>
                    @lang('view_pages.back')
                </button>
            </a>
        </div>

<div class="col-sm-12">

<form  method="post" class="form-horizontal" action="{{url('project/update/'.$item->id)}}" enctype="multipart/form-data">
@csrf

    <div class="row">
        <div class="col-sm-6">
            <div class="form-group">
            <label for="project_name">@lang('view_pages.project_name')</label>
            <input class="form-control" type="text" id="project_name" name="project_name" value="{{old('project_name',$item->project_name)}}" required="" >
            <span class="text-danger">{{ $errors->first('project_name') }}</span>

        </div>
    </div>
    
    <div class="col-sm-6">
            <div class="form-group">
            <label for="poc_name">@lang('view_pages.poc_name')</label>
            <input class="form-control" type="text" id="poc_name" name="poc_name" value="{{old('poc_name',$item->poc_name)}}" required="" >
            <span class="text-danger">{{ $errors->first('poc_name') }}</span>

        </div>
    </div>

    <div class="col-sm-6">
            <div class="form-group">
            <label for="poc_email">@lang('view_pages.poc_email')</label>
            <input class="form-control" type="text" id="poc_email" name="poc_email" value="{{old('poc_email',$item->poc_email)}}" required="" >
            <span class="text-danger">{{ $errors->first('poc_email') }}</span>

        </div>
    </div>

</div>


    <div class="form-group">
        <div class="col-12">
            <button class="btn btn-primary pull-right m-5" type="submit">
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
