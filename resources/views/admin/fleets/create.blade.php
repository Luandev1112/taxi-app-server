@extends('admin.layouts.app')

@section('content')

<div class="content">
    <div class="container-fluid">

        <div class="row">
            <div class="col-sm-12">
                <div class="box">

                    <div class="box-header with-border">
                        <a href="{{ url('fleets') }}">
                            <button class="btn btn-danger btn-sm pull-right" type="submit">
                                <i class="mdi mdi-keyboard-backspace mr-2"></i>
                                @lang('view_pages.back')
                            </button>
                        </a>
                    </div>
                
                <div class="col-12">
                <form  method="post" action="{{url('fleets/store')}}" enctype="multipart/form-data">
                @csrf
                    <div class="row">
                        <div class="col-sm-4 float-left mb-md-3">
                            <div class="form-group">
                                <label for="type">@lang('view_pages.select_type')<span class="text-danger">*</span></label>
                                <select name="type" id="type" class="form-control" required>
                                    <option value="" selected disabled>@lang('view_pages.select_type')</option>
                                    @foreach ($types as $key => $type)
                                        <option value="{{ $type->id }}" {{ old('type') == $type->id ? 'selected' : '' }} 
                                            data-seat="{{ $type->capacity }}"
                                            data-luggage="{{ $type->luggage_capacity }}">
                                            {{ $type->name }}</option>
                                    @endforeach
                                </select>
                                 <span class="text-danger">{{ $errors->first('type') }}</span>

                            </div>
                        </div>

                       <div class="col-sm-4 float-left mb-md-3">
                            <div class="form-group">
                                <label for="brand">@lang('view_pages.car_brand')<span class="text-danger">*</span></label>
                                <select name="brand" id="brand" class="form-control select2" required>
                                    <option value="" selected disabled>@lang('view_pages.select')</option>
                                    @foreach ($carmake as $key => $make)
                                        <option value="{{ $make->id }}">{{ $make->name }}</option>
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
                                 <span class="text-danger">{{ $errors->first('model') }}</span>
                            </div>
                        </div>

                      {{--   <div class="col-sm-4 float-left mb-md-3">
                            <div class="form-group">
                                <label for="model">@lang('view_pages.car_model')<span class="text-danger">*</span></label>
                                <select name="model" id="model"  class="form-control select2" required>
                                    <option value="" selected disabled>@lang('view_pages.select')</option>
                                     @foreach ($carmodel as $key => $model)
                                        <option value="{{ $model->id }}" {{ old('model') == $model->id ? 'selected' : '' }}>
                                            {{ $model->name }}</option>
                                    @endforeach
                                </select>
                                 <span class="text-danger">{{ $errors->first('model') }}</span>

                                
                            </div>
                        </div>
 --}}
                        <div class="col-sm-6 float-left mb-md-3">
                            <div class="form-group">
                                <label for="license_number">{{ trans('view_pages.license_number')}}<span class="text-danger">*</span></label>
                                <input id="license_number" name="license_number" placeholder="{{ trans('view_pages.license_number')}}" type="text" class="form-control" value="{{ old('license_number') }}" required>
                                <span class="text-danger">{{ $errors->first('license_number') }}</span>
                            </div>
                        </div>

                        <div class="col-sm-6 float-left mb-md-3">
                            <div class="form-group">
                                <label for="permission_number">{{ trans('view_pages.permission_number')}}<span class="text-danger">*</span></label>
                                <input id="permission_number" name="permission_number" placeholder="{{ trans('view_pages.permission_number')}}" type="text" class="form-control" value="{{ old('permission_number') }}" required>
                                <span class="text-danger">{{ $errors->first('permission_number') }}</span>
                            </div>
                        </div>

                        {{-- <div class="col-sm-6 float-left mb-md-3">
                            <div class="form-group">
                                <label for="">@lang('view_pages.chassis') <span class="text-danger">*</span></label>
                                <select name="chassis" id="chassis" class="form-control">
                                    <option value="" selected disabled>@lang('view_pages.select')</option>
                                    <option value="low" {{ old('chassis') == 'low' ? 'selected' : '' }}>@lang('view_pages.low')</option>
                                    <option value="high" {{ old('chassis') == 'high' ? 'selected' : '' }}>@lang('view_pages.high')</option>
                                </select>
                                <span class="text-danger">{{ $errors->first('chassis') }}</span>
                            </div>
                        </div>

                        <div class="col-sm-6 float-left mb-md-3">
                            <div class="form-group">
                                <label for="">@lang('view_pages.seats') <span class="text-danger">*</span></label>
                                <select name="seats" id="seats" class="form-control">
                                    <option value="" selected disabled>@lang('view_pages.select')</option>
                                </select>
                                <span class="text-danger">{{ $errors->first('seats') }}</span>
                            </div>
                        </div>

                        <div class="col-sm-6 float-left mb-md-3">
                            <div class="form-group">
                                <label for="">@lang('view_pages.luggage') <span class="text-danger">*</span></label>
                                <select name="luggage" id="luggage" class="form-control">
                                    <option value="" selected disabled>@lang('view_pages.select')</option>
                                </select>
                                <span class="text-danger">{{ $errors->first('luggage') }}</span>
                            </div>
                        </div> --}}

                       {{--  <div class="col-sm-6 float-left mb-md-3">
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="class_one" name="class_one">
                                <label class="form-check-label" for="class_one">
                                    @lang('view_pages.class_one')
                                </label>
                            </div>
                        </div>
 --}}
                       {{--  <div class="col-sm-6 float-left mb-md-3">
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="class_two" name="class_two">
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
                                <label>{{ trans('view_pages.vehicle_registration_cert')}}</label>
                                <div class="col-12" style="display: inline;">
                                    <div class="col-md-12 float-left p-0">
                                        <img class='img-upload' width="100px" class="rounded avatar-lg" />
                                    </div>
                                    <div class="col-md-12 float-left input-group p-0">
                                        <span class="input-group-btn">
                                            <span class="btn btn-default btn-file">
                                                Browse… <input type="file" class="imgInp" name="registration_certificate" id="registration_certificate" required>
                                            </span>
                                        </span>
                                        <input type="text" class="form-control" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 float-left">
                            <div class="form-group profile-img">
                                <label for="vehicle_back_side">{{ trans('view_pages.vehicle_back_side')}} </label>
                                <div class="col-12" style="display: inline;">
                                    <div class="col-md-12 float-left p-0">
                                        <img class='img-upload' width="100px" class="rounded avatar-lg" />
                                    </div>
                                    <div class="col-md-12 float-left input-group p-0">
                                        <span class="input-group-btn">
                                    <span class="btn btn-default btn-file">
                                        Browse… <input type="file" class="imgInp" name="vehicle_back_side" id="vehicle_back_side" required>
                                    </span>
                                        </span>
                                        <input type="text" class="form-control" readonly>
                                    </div>
                                </div>
                            </div>
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
