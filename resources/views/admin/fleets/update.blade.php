@extends('admin.layouts.app')

@section('content')

<div class="row p-0 m-0">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0 font-size-18">Edit Fleet</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{url('fleets') }}">Manage Fleet</a></li>
                    <li class="breadcrumb-item active">Edit Fleet</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row p-0 m-0">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form  method="post" action="{{url('fleets/update',$item->id)}}" enctype="multipart/form-data">
                @csrf
                    <div class="row">
                        <div class="col-sm-4 float-left mb-md-3">
                            <div class="form-group">
                                <label for="type">@lang('view_pages.select_type')<span class="text-danger">*</span></label>
                                <select name="type" id="type" class="form-control" required>
                                    <option value="" selected disabled>@lang('view_pages.select_type')</option>
                                    @foreach ($types as $key => $type)
                                        <option value="{{ $type->id }}" {{ old('type',$item->vehicle_type) == $type->id ? 'selected' : '' }} >{{ $type->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-sm-4 float-left mb-md-3">
                            <div class="form-group">
                                <label for="brand">@lang('view_pages.car_brand')<span class="text-danger">*</span></label>
                                <select name="brand" id="brand" class="form-control select2" required>
                                    <option value="" selected disabled>@lang('view_pages.select')</option>
                                    @foreach ($carmake as $key => $make)
                                        <option value="{{ $make->id }}" {{ old('brand',$item->brand) == $make->id ? 'selected' : '' }}>{{ $make->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-sm-4 float-left mb-md-3">
                            <div class="form-group">
                                <label for="car_model">@lang('view_pages.car_model')<span class="text-danger">*</span></label>
                                <select name="model" id="car_model" class="form-control select2" required>
                                    <option value="" selected disabled>@lang('view_pages.select')</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-sm-6 float-left mb-md-3">
                            <div class="form-group">
                                <label for="license_number">{{ trans('view_pages.license_number')}}<span class="mandatory">*</span></label>
                                <input id="license_number" name="license_number" placeholder="{{ trans('view_pages.license_number')}}" type="text" class="form-control" value="{{ old('license_number',$item->license_number) }}" required>
                                <span class="text-danger">{{ $errors->first('license_number') }}</span>
                            </div>
                        </div>

                        <div class="col-sm-6 float-left mb-md-3">
                            <div class="form-group">
                                <label for="permission_number">{{ trans('view_pages.permission_number')}}<span class="mandatory">*</span></label>
                                <input id="permission_number" name="permission_number" placeholder="{{ trans('view_pages.permission_number')}}" type="text" class="form-control" value="{{ old('permission_number',$item->permission_number) }}" required>
                                <span class="text-danger">{{ $errors->first('permission_number') }}</span>
                            </div>
                        </div>

                       {{--  <div class="col-sm-6 float-left mb-md-3">
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="class_one" name="class_one" {{ $item->class_one == 1 ? 'checked' : '' }}>
                                <label class="form-check-label" for="class_one">
                                    @lang('view_pages.class_one')
                                </label>
                            </div>
                        </div> --}}

                       {{--  <div class="col-sm-6 float-left mb-md-3">
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="class_two" name="class_two" {{ $item->class_two == 1 ? 'checked' : '' }}>
                                <label class="form-check-label" for="class_two">
                                    @lang('view_pages.class_two')
                                </label>
                            </div>
                        </div> --}}

                        <div class="col-sm-12">
                            <h4 class="card-title mb-4">@lang('view_pages.vehicle_documnents')</h4>
                        </div><hr>

                        <div class="col-md-6 float-left">
                            <div class="form-group profile-img">
                                <label>{{ trans('view_pages.vehicle_registration_cert')}} <span class="text-danger">*</span></label>
                                <div class="col-12" style="display: inline;">
                                    <div class="col-md-12 float-left p-0">
                                        <img class='img-upload' width="100px" class="rounded avatar-lg" src="{{ $item->fleetDocument[0]->registration_certificate_image }}"/>
                                    </div>
                                    <div class="col-md-12 float-left input-group p-0">
                                        <span class="input-group-btn">
                                            <span class="btn btn-default btn-file">
                                                Browse… <input type="file" class="imgInp" name="registration_certificate">
                                            </span>
                                        </span>
                                        <input type="text" class="form-control" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 float-left">
                            <div class="form-group profile-img">
                                <label>{{ trans('view_pages.vehicle_back_side')}} <span class="text-danger">*</span></label>
                                <div class="col-12" style="display: inline;">
                                    <div class="col-md-12 float-left p-0">
                                        <img class='img-upload' width="100px" class="rounded avatar-lg" src="{{ $item->fleetDocument[1]->vehicle_back_side_image }}"/>
                                    </div>
                                    <div class="col-md-12 float-left input-group p-0">
                                        <span class="input-group-btn">
                                    <span class="btn btn-default btn-file">
                                        Browse… <input type="file" class="imgInp" name="vehicle_back_side">
                                    </span>
                                        </span>
                                        <input type="text" class="form-control" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 btn-group mt-3">
                            <ul class="admin-add-btn">
                                <li>
                                    <button type="submit" class="btn btn-primary mr-1 waves-effect waves-light">{{ trans('view_pages.update')}}</button>
                                </li>
                            </ul>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<script src="{{ asset('styles/js/jquery.js') }}"></script>
<script>
    $(document).ready(function() {
        $(document).on('change', '.btn-file :file', function() {
            var input = $(this),
                label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
            input.trigger('fileselect', [label]);
        });

        $('.btn-file :file').on('fileselect', function(event, label) {

            var input = $(this).parents('.input-group').find(':text'),
                log = label;

            if (input.length) {
                input.val(log);
            } else {
                if (log) alert(log);
            }

        });

        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    $(input).closest('div').parent().find('.img-upload').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        $(".imgInp").change(function() {
            readURL(this);
        });

        getCarModel("{{ $item->brand }}","{{ $item->model }}");
    });

    let oldCarMake = "{{ old('brand') }}";
    let oldCarModel = "{{ old('model') }}";

    if(oldCarMake){
        getCarModel(oldCarMake,oldCarModel);
    }

    function getCarModel(value,model=''){
        var selected = '';
        $.ajax({
            url: "{{ route('getCarModel') }}",
            type:  'GET',
            data: {
                'car_make': value,
            },
            success: function(result)
            {
                $('#car_model').empty();
                $("#car_model").append('<option value="" selected disabled>Select</option>');
                result.forEach(element => {

                    if(model == element.id){
                        selected = 'selected';
                    }else{
                        selected='';
                    }

                    $("#car_model").append('<option value='+element.id+' '+selected+'>'+element.name+'</option>')
                });
                $('#car_model').select();
            }
        });
    }

    $(document).on('change','#brand',function(){
        getCarModel($(this).val());
    });
</script>
@endsection
