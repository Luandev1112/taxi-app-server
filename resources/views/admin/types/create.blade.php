@extends('admin.layouts.app')


@section('title', 'Main page')

<!-- Bootstrap fileupload css -->
@section('content')



<!-- Start Page content -->
<div class="content">
<div class="container-fluid">

<div class="row">
<div class="col-sm-12">
    <div class="box">

        <div class="box-header with-border">
            <a href="{{ url('types') }}">
                <button class="btn btn-danger btn-sm pull-right" type="submit">
                    <i class="mdi mdi-keyboard-backspace mr-2"></i>
                    @lang('view_pages.back')
                </button>
            </a>
        </div>

        <div class="col-sm-12">
<form id="type-form" role="form" method="post" class="form-horizontal" action="{{url('types/store')}}" enctype="multipart/form-data">
{{csrf_field()}}

<div class="row">
{{-- <div class="col-6">
<div class="form-group">
<label for="service_location_id">@lang('view_pages.select_area')
    <span class="text-danger">*</span>
</label>
<select name="service_location_id" id="service_location_id" class="form-control" required>
    <option value="" >@lang('view_pages.select_area')</option>
    @foreach($services as $key=>$service)
    <option value="{{$service->id}}" {{ old('service_location_id') == $service->id ? 'selected' : '' }}>{{$service->name}}</option>
    @endforeach
</select>
</div>
</div> --}}

    <div class="col-6">
        <div class="form-group m-b-25">
            <label for="name">@lang('view_pages.name') <span class="text-danger">*</span></label>
            <input class="form-control" type="text" id="name" name="name" value="{{old('name')}}" required="" placeholder="@lang('view_pages.enter_name')">
            <span class="text-danger">{{ $errors->first('name') }}</span>

        </div>
    </div>

        <div class="col-6">
            <div class="form-group m-b-25">
            <label for="name">@lang('view_pages.capacity') <span class="text-danger">*</span></label>
            <input class="form-control" type="number" id="capacity" name="capacity" value="{{old('capacity')}}" required="" placeholder="@lang('view_pages.enter_capacity')" min="1">
            <span class="text-danger">{{ $errors->first('capacity') }}</span>
        </div>
    </div>
      <div class="col-6">
        <div class="form-group m-b-25">
            <label for="short_description">@lang('view_pages.short_description') <span class="text-danger">*</span></label>
            <input class="form-control" type="text" id="name" name="short_description" value="{{old('short_description')}}" required="" placeholder="@lang('view_pages.enter_short_description')">
            <span class="text-danger">{{ $errors->first('short_description') }}</span>

        </div>
    </div>

     <div class="col-6">
        <div class="form-group m-b-25">
            <label for="description">@lang('view_pages.description') <span class="text-danger">*</span></label>
            <textarea name="description" id="description" class="form-control" placeholder="@lang('view_pages.enter_description')"></textarea>
           
            <span class="text-danger">{{ $errors->first('description') }}</span>

        </div>
    </div> 

    <div class="col-6">
        <div class="form-group m-b-25">
            <label for="supported_vehicles">@lang('view_pages.supported_vehicles') <span class="text-danger">*</span></label>
            <textarea name="supported_vehicles" id="supported_vehicles" class="form-control" placeholder="Example: Toyato,Audi,Acura"></textarea>
           
            <span class="text-danger">{{ $errors->first('supported_vehicles') }}</span>

        </div>
    </div>
</div>


    <div class="form-group">
        <div class="col-6">
            <label for="icon">@lang('view_pages.icon')</label><br>
            <img id="blah" src="#" alt=""><br>
            <input type="file" id="icon" onchange="readURL(this)" name="icon" style="display:none">
            <button class="btn btn-primary btn-sm" type="button" onclick="$('#icon').click()" id="upload">Browse</button>
            <button class="btn btn-danger btn-sm" type="button" id="remove_img" style="display: none;">Remove</button><br>
            <span class="text-danger">{{ $errors->first('icon') }}</span>
    </div>
</div>
<div class="form-group">
        <div class="col-12">
            <button class="btn btn-primary btn-sm m-5 pull-right" type="submit">
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

<!-- Laravel Javascript Validation -->
<script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>

{!! JsValidator::formRequest('App\Http\Requests\Admin\VehicleTypes\CreateVehicleTypeRequest','#type-form') !!}

<!-- Bootstrap fileupload js -->

@endsection

