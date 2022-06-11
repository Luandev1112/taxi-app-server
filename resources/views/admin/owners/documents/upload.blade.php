@extends('taxi.layout.app')

@section('content')
<style>
.main-content-section {
    height: 100vh;
}
</style>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker.css" rel="stylesheet" type="text/css" />

<div class="row p-0 m-0">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0 font-size-18">Manage Document</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('ownerByArea',$owner->service_location_id) }}">Manage Owner</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('ownerDocumentView',$owner->id) }}">Manage Document</a></li>
                    <li class="breadcrumb-item active">Upload Document</li>
                </ol>
            </div>

        </div>
    </div>
</div>
<div class="row p-0 m-0">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form action="{{route('updateOwnerDocument',[$owner->id,$needed_document->id])}}" method="post" enctype="multipart/form-data" >
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">

                    <div class="row">
                        <div class="col-sm-6  float-left mb-md-3">
                            <div class="form-group">
                                <label for="name">@lang('view_pages.name') <span class="text-danger">*</span></label>
                                <input class="form-control" type="text" id="name" name="name" value="{{$needed_document->name}}" placeholder="@lang('view_pages.document_name')" readonly>
                                <span class="text-danger">{{ $errors->first('name') }}</span>
                            </div>
                        </div>

                        {{-- @if ($needed_document->has_identify_number)
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="identify_number">@lang('view_pages.identify_number')</label>
                                    <input class="form-control" type="text" id="identify_number" name="identify_number" value="{{ old('identify_number',$ownerDoc ? $ownerDoc->identify_number : '') }}" required="" placeholder="@lang('view_pages.identify_number')">
                                    <span class="text-danger">{{ $errors->first('identify_number') }}</span>
                                </div>
                            </div>
                        @endif --}}


                        @if ($needed_document->has_expiry_date)
                            <div class="col-sm-6 float-left mb-md-3">
                                <div class="form-group">
                                    <label for="expiry_date">@lang('view_pages.expiry_date') <span class="text-danger">*</span></label>
                                    <div id="datepicker" class="date-pick input-group date date-custom" data-date-format="yyyy-mm-dd">
                                        <input id="expiry_date" alt="" name="expiry_date" placeholder="yyyy-mm-dd" type="text" class="form-control" value="{{old('expiry_date',$ownerDoc ? $ownerDoc->expiry_date : '')}}" autocomplete="off">
                                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                    </div>
                                    <span class="text-danger">{{ $errors->first('expiry_date') }}</span>
                                </div>
                            </div>
                        @endif


                        <div class="col-md-6 float-left mb-md-3">
                            <div class="form-group profile-img">
                                <label>{{ trans('view_pages.document')}} <span class="text-danger">*</span></label>
                                <div class="col-12" style="display: inline;">
                                    <div class="col-md-12 float-left p-0">
                                        @if ($ownerDoc)
                                            <embed id='img-upload' src="{{  $ownerDoc->image }}" width="100px" class="rounded avatar-lg" />
                                        @else
                                            <img id='img-upload' width="100px" class="rounded avatar-lg" />
                                        @endif
                                    </div>
                                    <div class="col-md-8 float-left input-group p-0">
                                        <span class="input-group-btn">
                                            <span class="btn btn-default btn-file">
                                                Browse… <input type="file" name="document" id="imgInp" {{ $ownerDoc ? '' : 'required' }} accept="image/*,.pdf">
                                            </span>
                                        </span>
                                        <input type="text" class="form-control" readonly>
                                    </div>

                                    @if ($ownerDoc)
                                        <div class="col-md-4 float-left">
                                            <ul class="mang-viw-btn">
                                                <li>
                                                    <a href="{{ $ownerDoc->image }}" class="download-view-doc" download><i class="fa fa-download" aria-hidden="true"></i>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-12 btn-group mt-2">
                            <ul class="mang-viw-btn">
                                <li>
                                    <button class="btn btn-primary">{{ trans('view_pages.update_document')}}</button>
                                </li>
                            </ul>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.js"></script>
<script>

$(".date-pick").datepicker({
    autoclose: true,
    todayHighlight: true,
    format:'yyyy-mm-dd',
    startDate:'0'
});

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
                $('#img-upload').attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }

    $("#imgInp").change(function() {
        readURL(this);
    });
});
</script>
@endsection
