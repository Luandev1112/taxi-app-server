@extends('admin.layouts.app')


@section('title', 'Main page')

@section('content')

<!-- Start Page content -->
<section class="content">
{{-- <div class="container-fluid"> --}}

<div class="row">
<div class="col-12">
<div class="box">

        <div class="box-header with-border">
            <div class="row text-right">
                <div class="col-2">
                    <div class="form-group">
                    <input type="text" name="search" class="form-control" placeholder="@lang('view_pages.enter_keyword')">
                    </div>
                </div>

                <div class="col-1">
                    <button class="btn btn-success btn-outline btn-sm mt-5" type="submit">
                    @lang('view_pages.search')
                    </button>
                </div>
                <div class="col-9 text-right">
                    <a href="{{url('project/add/client',$project_id)}}" class="btn btn-primary btn-sm">
                    <i class="mdi mdi-plus-circle mr-2"></i>@lang('view_pages.add_client')</a>
                    <a href="{{url('project')}}" class="btn btn-danger btn-sm">
                    <i class="mdi mdi-keyboard-backspace mr-2"></i>@lang('view_pages.back')</a>
                </div>
            </div>
        </div>
<div class="box-body no-padding">
    <div class="table-responsive">
        <table class="table table-hover">
        <thead>
        <tr>

        <th> @lang('view_pages.s_no')
        <span style="float: right;">

        </span>
        </th>

        <th> @lang('view_pages.poc_name')
        <span style="float: right;">
        </span>
        </th>
        <th> @lang('view_pages.mobile')
        <span style="float: right;">
        </span>
        </th>
        <th> @lang('view_pages.email')
        <span style="float: right;">
        </span>
        </th>
         <th> @lang('view_pages.status')
        <span style="float: right;">
        </span>
        </th>
        <th> @lang('view_pages.action')
        <span style="float: right;">
        </span>
        </th>

        </tr>
        </thead>
        <tbody>
@if(count($results)<1)
    <tr>
        <td colspan="11">
        <p id="no_data" class="lead no-data text-center">
        <img src="{{asset('assets/img/dark-data.svg')}}" style="width:150px;margin-top:25px;margin-bottom:25px;" alt="">
     <h4 class="text-center" style="color:#333;font-size:25px;">@lang('view_pages.no_data_found')</h4>
 </p>
    </tr>
    @else

@php  $i= $results->firstItem(); @endphp

@foreach($results as $key => $result)

<tr>
<td>{{ $i++ }} </td>
<td> {{$result->name}}</td>
<td> {{$result->mobile}}</td>
<td> {{$result->email	}}</td>
 @if($result->active)
<td><button class="btn btn-success btn-sm">Active</button></td>
@else
<td><button class="btn btn-danger btn-sm">InActive</button></td>
@endif
<td>

<button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">@lang('view_pages.action')
</button>

    <div class="dropdown-menu" x-placement="bottom-start">

            <a class="dropdown-item" href="{{url('project/edit',$result->id)}}">
            <i class="fa fa-pencil"></i>@lang('view_pages.edit')</a>

            <!-- @if($result->active)
            <a class="dropdown-item" href="{{url('project/client/toggle_status',$result->id)}}">
            <i class="fa fa-dot-circle-o"></i>@lang('view_pages.inactive')</a>
            @else
            <a class="dropdown-item" href="{{url('project/client/toggle_status',$result->id)}}">
            <i class="fa fa-dot-circle-o"></i>@lang('view_pages.active')</a>
            @endif -->
            <a class="dropdown-item sweet-delete" href="{{url('project/client/delete',$result->id)}}">
            <i class="fa fa-trash-o"></i>@lang('view_pages.delete')</a>
    </div>

</td>
</tr>
@endforeach
@endif
</tbody>
</table>


        <div class="text-right">
        <span  style="float:right">
        {{$results->links()}}
        </span>
        </div>
</div>
</div>
</div>

{{--</div>--}}
<!-- container -->

<!--table content -->
</div></div>

@endsection

