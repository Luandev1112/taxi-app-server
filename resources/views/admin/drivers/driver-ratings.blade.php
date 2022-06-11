@extends('admin.layouts.app')

@section('title', 'Company page')

@section('content')
    <style>
        .demo-radio-button label {
            min-width: 100px;
            margin: 0 0 5px 50px;
        }

    </style>
    <!-- Start Page content -->
    <section class="content">
        {{-- <div class="container-fluid"> --}}

        <div class="row">
            <div class="col-12">
                <div class="box">

                    <div class="box-header with-border">
                        <div class="row text-right">
                            <div class="col-8 col-md-3">
                                <div class="form-group">
                                    <div class="controls">
                                        <input type="text" id="search_keyword" name="search" class="form-control"
                                            placeholder="@lang('view_pages.enter_keyword')">
                                    </div>
                                </div>
                            </div>

                            <div class="col-2 col-md-1 text-left">
                                <button id="search" class="btn btn-success btn-outline btn-sm py-2" type="submit">
                                    @lang('view_pages.search')
                                </button>
                            </div>

                          
                           
                        </div>

                     
                    </div>

                    
                        <div class="row">

                            <div class="col-12">
                                <div class="box">           
                                   <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th> @lang('view_pages.s_no')</th>
                                            <th> @lang('view_pages.name')</th>
                                            <th> @lang('view_pages.mobile')</th>
                                            <th> @lang('view_pages.type')</th>
                                            <th> @lang('view_pages.rating')</th>
                                            <th> @lang('view_pages.action')</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        {{-- @php  $i= $results->firstItem(); @endphp --}}

                                        @forelse($results as $key => $result)

                                        <tr>
                                            <td>{{ $key+1}} </td>
                                            <td>{{$result->name}}</td>
                                            <td>{{ $result->mobile }}</td>
                                            <td>{{$result->vehicleType->name }}</td>
                                           
                                           <td>
                                          @php $rating = $result->rating($result->user_id); @endphp  

                                                    @foreach(range(1,5) as $i)
                                                        <span class="fa-stack" style="width:1em">
                                                           

                                                            @if($rating > 0)
                                                                @if($rating > 0.5)
                                                                    <i class="fa fa-star checked"></i>
                                                                @else
                                                                    <i class="fa fa-star-half-o"></i>
                                                                @endif
                                                    @else


                                                             <i class="fa fa-star-o "></i>
                                                            @endif
                                                            @php $rating--; @endphp
                                                        </span>
                                                    @endforeach 

                                        </td>
                                        <td> <a href="{{ url('driver-ratings/view',$result->id) }}" class="btn btn-primary btn-sm">@lang('view_pages.view')</a></td>

                                        
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="11">
                                                <p id="no_data" class="lead no-data text-center">
                                                    <img src="{{asset('assets/img/dark-data.svg')}}" style="width:150px;margin-top:25px;margin-bottom:25px;" alt="">
                                                    <h4 class="text-center" style="color:#333;font-size:25px;">@lang('view_pages.no_data_found')</h4>
                                                </p>
                                            </td>
                                        </tr>
                                        @endforelse

                                    </tbody>
                                </table>

    </div>
</div>
</div>


                </div>
            </div>
        </div>

        {{-- </div> --}}
        <!-- container -->


        <script src="{{ asset('assets/js/fetchdata.min.js') }}"></script>
        <script>
            var search_keyword = '';
            var query = '';

            $(function() {
                $('body').on('click', '.pagination a', function(e) {
                    e.preventDefault();
                    var url = $(this).attr('href');
                    $.get(url, $('#search').serialize(), function(data) {
                        $('#js-drivers-partial-target').html(data);
                    });
                });

                $('#search').on('click', function(e) {
                    e.preventDefault();
                    search_keyword = $('#search_keyword').val();

                    fetch('drivers/fetch?search=' + search_keyword)
                        .then(response => response.text())
                        .then(html => {
                            document.querySelector('#js-drivers-partial-target').innerHTML = html
                        });
                });

               
            });

           
         

        </script>
    @endsection
