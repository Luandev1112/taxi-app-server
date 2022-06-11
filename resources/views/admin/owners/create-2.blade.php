@extends('taxi.layout.app')

@section('content')
    <!-- twitter-bootstrap-wizard css -->
    <link rel="stylesheet" href="{{ asset('taxi/assets/libs/twitter-bootstrap-wizard/prettify.css') }}">
    <!-- App Css-->
    <link href="{{ asset('taxi/assets/css/app.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker.css" rel="stylesheet"
        type="text/css" />

    <div class="row p-0 m-0">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0 font-size-18">Add Owner</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ url('owners') }}">Manage Owner</a>
                        </li>
                        <li class="breadcrumb-item active">Add Owner</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row p-0 m-0">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="post" action="{{ url('owners/store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-body">
                                        {{-- <h4 class="card-title mb-4">Basic pills Wizard
                                        </h4> --}}

                                        <div id="basic-pills-wizard" class="twitter-bs-wizard">
                                            <ul class="twitter-bs-wizard-nav">
                                                <li class="nav-item">
                                                    <a href="#owner-details" class="nav-link" data-toggle="tab">
                                                        <span class="step-number mr-2">01</span>
                                                        @lang('view_pages.owner_details')
                                                    </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a href="#contact-person-details" class="nav-link" data-toggle="tab">
                                                        <span class="step-number mr-2">02</span>
                                                        @lang('view_pages.contact_person_details')
                                                    </a>
                                                </li>

                                                <li class="nav-item">
                                                    <a href="#bank-detail" class="nav-link" data-toggle="tab">
                                                        <span class="step-number mr-2">03</span>
                                                        @lang('view_pages.bank_details')
                                                    </a>
                                                </li>
                                            </ul>
                                            <div class="tab-content twitter-bs-wizard-tab-content">

                                                <div class="tab-pane" id="owner-details">
                                                    <div class="row">
                                                        <div class="col-sm-6 float-left mb-md-3">
                                                            <div class="form-group">
                                                                <label for="company_name">@lang('view_pages.company_name')
                                                                    <span class="text-danger">*</span></label>
                                                                <input class="form-control" type="text" id="company_name"
                                                                    name="company_name" value="{{ old('company_name') }}"
                                                                    required=""
                                                                    placeholder="@lang('view_pages.enter') @lang('view_pages.company_name')">
                                                                <span
                                                                    class="text-danger">{{ $errors->first('company_name') }}</span>
                                                            </div>
                                                        </div>

                                                        <div class="col-sm-6 float-left mb-md-3">
                                                            <div class="form-group">
                                                                <label for="owner_name">@lang('view_pages.owner_name') <span
                                                                        class="text-danger">*</span></label>
                                                                <input class="form-control" type="text" id="owner_name"
                                                                    name="owner_name" value="{{ old('owner_name') }}"
                                                                    required=""
                                                                    placeholder="@lang('view_pages.enter') @lang('view_pages.owner_name')">
                                                                <span
                                                                    class="text-danger">{{ $errors->first('owner_name') }}</span>
                                                            </div>
                                                        </div>

                                                        <div class="col-sm-4 float-left mb-md-3">
                                                            <div class="form-group">
                                                                <label for="email">@lang('view_pages.email') <span
                                                                        class="text-danger">*</span></label>
                                                                <input class="form-control" type="email" id="email"
                                                                    name="email" value="{{ old('email') }}" required=""
                                                                    placeholder="@lang('view_pages.enter') @lang('view_pages.email')">
                                                                <span
                                                                    class="text-danger">{{ $errors->first('email') }}</span>
                                                            </div>
                                                        </div>

                                                        <div class="col-sm-4 float-left mb-md-3">
                                                            <div class="form-group">
                                                                <label for="password">@lang('view_pages.password') <span
                                                                        class="text-danger">*</span></label>
                                                                <input class="form-control" type="password" id="password"
                                                                    name="password" value="{{ old('password') }}"
                                                                    required=""
                                                                    placeholder="@lang('view_pages.enter') @lang('view_pages.password')">
                                                                <span
                                                                    class="text-danger">{{ $errors->first('password') }}</span>
                                                            </div>
                                                        </div>

                                                        <div class="col-sm-4 float-left mb-md-3">
                                                            <div class="form-group">
                                                                <label
                                                                    for="password_confrim">@lang('view_pages.confirm_password')
                                                                    <span class="text-danger">*</span></label>
                                                                <input class="form-control" type="password"
                                                                    id="password_confirmation" name="password_confirmation"
                                                                    value="{{ old('password_confirmation') }}" required=""
                                                                    placeholder="@lang('view_pages.enter') @lang('view_pages.password_confirmation')">
                                                                <span
                                                                    class="text-danger">{{ $errors->first('password') }}</span>
                                                            </div>
                                                        </div>

                                                        <div class="col-sm-12 float-left mb-md-3">
                                                            <div class="form-group">
                                                                <label for="address">@lang('view_pages.address') <span
                                                                        class="text-danger">*</span></label>
                                                                <input class="form-control" type="text" id="address"
                                                                    name="address" value="{{ old('address') }}"
                                                                    placeholder="@lang('view_pages.enter') @lang('view_pages.address')">
                                                                <span
                                                                    class="text-danger">{{ $errors->first('address') }}</span>

                                                            </div>
                                                        </div>

                                                        <div class="col-sm-6 float-left mb-md-3">
                                                            <div class="form-group">
                                                                <label for="street">@lang('view_pages.street') <span
                                                                        class="text-danger">*</span></label>
                                                                <input class="form-control" type="text" id="street"
                                                                    name="street" value="{{ old('street') }}" required=""
                                                                    placeholder="@lang('view_pages.enter') @lang('view_pages.street')">
                                                                <span
                                                                    class="text-danger">{{ $errors->first('street') }}</span>
                                                            </div>
                                                        </div>

                                                        <div class="col-sm-6 float-left mb-md-3">
                                                            <div class="form-group">
                                                                <label for="house_number">@lang('view_pages.house_number')
                                                                    <span class="text-danger">*</span></label>
                                                                <input class="form-control" type="text" id="house_number"
                                                                    name="house_number" value="{{ old('house_number') }}"
                                                                    required=""
                                                                    placeholder="@lang('view_pages.enter') @lang('view_pages.house_number')"
                                                                    pattern="[A-Za-z0-9/ ]*">
                                                                <span
                                                                    class="text-danger">{{ $errors->first('house_number') }}</span>
                                                            </div>
                                                        </div>

                                                        <div class="col-sm-6 float-left mb-md-3">
                                                            <div class="form-group">
                                                                <label for="postal_code">@lang('view_pages.postal_code')
                                                                    <span class="text-danger">*</span></label>
                                                                <input class="form-control" type="number" id="postal_code"
                                                                    name="postal_code" value="{{ old('postal_code') }}"
                                                                    required=""
                                                                    placeholder="@lang('view_pages.enter') @lang('view_pages.postal_code')">
                                                                <span
                                                                    class="text-danger">{{ $errors->first('postal_code') }}</span>
                                                            </div>
                                                        </div>

                                                        <div class="col-sm-6 float-left mb-md-3">
                                                            <div class="form-group">
                                                                <label for="city">@lang('view_pages.city') <span
                                                                        class="text-danger">*</span></label>
                                                                <input class="form-control" type="text" id="city"
                                                                    name="city" value="{{ old('city') }}" required=""
                                                                    placeholder="@lang('view_pages.enter') @lang('view_pages.city')">
                                                                <span
                                                                    class="text-danger">{{ $errors->first('city') }}</span>
                                                            </div>
                                                        </div>

                                                        <div class="col-sm-6 float-left mb-md-3">
                                                            <div class="form-group">
                                                                <label for="admin_id">@lang('view_pages.select_area')<span
                                                                        class="text-danger">*</span></label>
                                                                <select name="service_location_id" id="service_location_id"
                                                                    class="form-control" required>
                                                                    <option value="" selected disabled>
                                                                        @lang('view_pages.select_area')</option>
                                                                    @foreach ($services as $key => $service)
                                                                        <option value="{{ $service->id }}"
                                                                            {{ old('service_location_id') == $service->id ? 'selected' : '' }}>
                                                                            {{ $service->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class="col-sm-6 float-left mb-md-3">
                                                            <div class="form-group">
                                                                <label for="expiry_date">@lang('view_pages.expiry_date')
                                                                    <span class="text-danger">*</span></label>
                                                                <div id="datepicker"
                                                                    class="date-pick input-group date date-custom"
                                                                    data-date-format="yyyy-mm-dd">
                                                                    <input id="expiry_date" alt="" name="expiry_date"
                                                                        placeholder="yyyy-mm-dd" type="text"
                                                                        class="form-control"
                                                                        value="{{ old('expiry_date') }}" autocomplete="off">
                                                                    <span class="input-group-addon"><i
                                                                            class="fa fa-calendar"></i></span>
                                                                </div>
                                                                <span
                                                                    class="text-danger">{{ $errors->first('expiry_date') }}</span>
                                                            </div>
                                                        </div>

                                                        <div class="col-sm-6 float-left mb-md-3">
                                                            <div class="form-group">
                                                                <label
                                                                    for="no_of_vehicles">@lang('view_pages.no_of_vehicles')
                                                                    <span class="text-danger">*</span></label>
                                                                <input class="form-control" type="number" min="1"
                                                                    id="no_of_vehicles" name="no_of_vehicles"
                                                                    value="{{ old('no_of_vehicles') }}" required=""
                                                                    placeholder="@lang('view_pages.enter') @lang('view_pages.no_of_vehicles')">
                                                                <span
                                                                    class="text-danger">{{ $errors->first('no_of_vehicles') }}</span>
                                                            </div>
                                                        </div>

                                                        <div class="col-sm-6 float-left mb-md-3">
                                                            <div class="form-group">
                                                                <label for="tax_number">@lang('view_pages.tax_number') <span
                                                                        class="text-danger">*</span></label>
                                                                <input class="form-control" type="text" id="tax_number"
                                                                    name="tax_number" value="{{ old('tax_number') }}"
                                                                    required=""
                                                                    placeholder="@lang('view_pages.enter') @lang('view_pages.tax_number')">
                                                                <span
                                                                    class="text-danger">{{ $errors->first('tax_number') }}</span>
                                                            </div>
                                                        </div>

                                                        {{-- <div
                                                            class="col-12 btn-group mt-3">
                                                            <ul class="admin-add-btn">
                                                                <li>
                                                                    <button type="submit"
                                                                        class="btn btn-primary mr-1 waves-effect waves-light">{{ trans('view_pages.create') }}</button>
                                                                </li>
                                                            </ul>
                                                        </div> --}}
                                                    </div>
                                                </div>

                                                <div class="tab-pane" id="contact-person-details">
                                                    <div class="row">
                                                        <div class="col-sm-6 float-left mb-md-3">
                                                            <div class="form-group">
                                                                <label for="name">@lang('view_pages.name') <span
                                                                        class="text-danger">*</span></label>
                                                                <input class="form-control" type="text" id="name"
                                                                    name="name" value="{{ old('name') }}" required=""
                                                                    placeholder="@lang('view_pages.enter') @lang('view_pages.name')">
                                                                <span
                                                                    class="text-danger">{{ $errors->first('name') }}</span>
                                                            </div>
                                                        </div>

                                                        <div class="col-sm-6 float-left mb-md-3">
                                                            <div class="form-group">
                                                                <label for="surname">@lang('view_pages.surname') <span
                                                                        class="text-danger">*</span></label>
                                                                <input class="form-control" type="text" id="surname"
                                                                    name="surname" value="{{ old('surname') }}" required=""
                                                                    placeholder="@lang('view_pages.enter') @lang('view_pages.surname')">
                                                                <span
                                                                    class="text-danger">{{ $errors->first('surname') }}</span>
                                                            </div>
                                                        </div>

                                                        <div class="col-sm-6 float-left mb-md-3">
                                                            <div class="form-group">
                                                                <label for="mobile">@lang('view_pages.mobile') <span
                                                                        class="text-danger">*</span></label>
                                                                <input class="form-control" type="text" id="mobile"
                                                                    name="mobile" value="{{ old('mobile') }}" required=""
                                                                    placeholder="@lang('view_pages.enter') @lang('view_pages.mobile')">
                                                                <span
                                                                    class="text-danger">{{ $errors->first('mobile') }}</span>
                                                            </div>
                                                        </div>

                                                        <div class="col-sm-6 float-left mb-md-3">
                                                            <div class="form-group">
                                                                <label for="phone">@lang('view_pages.phone')</label>
                                                                <input class="form-control" type="text" id="phone"
                                                                    name="phone" value="{{ old('phone') }}"
                                                                    placeholder="@lang('view_pages.enter') @lang('view_pages.phone')">
                                                                <span
                                                                    class="text-danger">{{ $errors->first('phone') }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="tab-pane" id="bank-detail">
                                                    <div class="row">
                                                        <div class="col-sm-6 float-left mb-md-3">
                                                            <div class="form-group">
                                                                <label for="iban">@lang('view_pages.iban') <span
                                                                        class="text-danger">*</span></label>
                                                                <input class="form-control" type="text" id="iban"
                                                                    name="iban" value="{{ old('iban') }}" required
                                                                    placeholder="@lang('view_pages.enter') @lang('view_pages.iban')">
                                                                <span
                                                                    class="text-danger iban_err">{{ $errors->first('iban') }}</span>
                                                            </div>
                                                        </div>

                                                        <div class="col-sm-6 float-left mb-md-3">
                                                            <div class="form-group">
                                                                <label for="bank_name">@lang('view_pages.bank_name')</label>
                                                                <input class="form-control" type="text" readonly
                                                                    id="bank_name" name="bank_name"
                                                                    value="{{ old('bank_name') }}"
                                                                    placeholder="@lang('view_pages.enter') @lang('view_pages.bank_name')">
                                                                <span
                                                                    class="text-danger">{{ $errors->first('bank_name') }}</span>
                                                            </div>
                                                        </div>

                                                        <div class="col-sm-6 float-left mb-md-3">
                                                            <div class="form-group">
                                                                <label for="bic">@lang('view_pages.bic')</label>
                                                                <input class="form-control" type="text" readonly id="bic"
                                                                    name="bic" value="{{ old('bic') }}"
                                                                    placeholder="@lang('view_pages.enter') @lang('view_pages.bic')">
                                                                <span class="text-danger">{{ $errors->first('bic') }}</span>
                                                            </div>
                                                        </div>

                                                        <div class="col-12 btn-group mt-3">
                                                            <ul class="admin-add-btn">
                                                                <li>
                                                                    <button type="submit"
                                                                        class="btn btn-primary mr-1 waves-effect waves-light">{{ trans('view_pages.create') }}</button>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                            {{-- <ul
                                                class="pager wizard twitter-bs-wizard-pager-link">
                                                <li class="previous"><a href="#">Previous</a></li>
                                                <li class="next"><a href="#">Next</a></li>
                                            </ul> --}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- twitter-bootstrap-wizard js -->
    <script src="{{ asset('taxi/assets/libs/twitter-bootstrap-wizard/jquery.bootstrap.wizard.min.js') }}"></script>

    <script src="{{ asset('taxi/assets/libs/twitter-bootstrap-wizard/prettify.js') }}"></script>

    <!-- form wizard init -->
    <script src="{{ asset('taxi/assets/js/form-wizard.init.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.js"></script>
    <script>
        var err = false;

        $(".date-pick").datepicker({
            autoclose: true,
            todayHighlight: true,
            format: 'yyyy-mm-dd',
            startDate: '0'
        });

        $(document).on('blur keypress', '#iban', function(e) {
            var iban = $(this).val();

            if (e.type == 'keypress') {
                $('.iban_err').text('');
            } else {
                $.ajax({
                    url: "{{ url('api/v1/iban-validation') }}",
                    data: {
                        iban: iban
                    },
                    method: 'post',
                    success: function(response) {
                        console.log(response);
                        if (response.success == false) {
                            $('.iban_err').text('Provide valid IBAN');
                            $("#bank_name").val('');
                            $("#bic").val('');
                            err = true;
                            return false;
                        } else {
                            var bic = response.data.bank_code.bic;
                            var bank_name = response.data.bank_code.bank_name;
                            $("#bank_name").val(bank_name);
                            $("#bic").val(bic);
                        }
                    }
                });
            }
        });

        $('form').on('submit', function(event) {
            event.preventDefault();
            if (err) {
                $('.iban_err').text('Provide valid IBAN');
                return false;
            } else {
                $('.iban_err').text('');
                this.submit();
            }
        });

    </script>
@endsection
