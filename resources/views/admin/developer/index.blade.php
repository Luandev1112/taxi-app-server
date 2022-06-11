@extends('admin.layouts.app')

@section('title', 'Admin')

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
                <input type="text" id="search_keyword" name="search" class="form-control" placeholder="@lang('view_pages.enter_keyword')">
                </div>
                </div>


                <div class="col-1">
                    <button class="btn btn-success btn-outline btn-sm mt-5" type="submit">
                        @lang('view_pages.search')
                    </button>
                </div>


                <div class="col-9 text-right">
                    <a href="{{url('developer/create')}}" class="btn btn-primary btn-sm">
                        <i class="mdi mdi-plus-circle mr-2"></i>@lang('view_pages.add_developer')</a>
                   <!--  <a class="btn btn-danger">
                        Export</a> -->
                </div>

                </div>

    </div>

    <div id="js-developer-partial-target">
        <include-fragment src="developer/fetch">
            <span style="text-align: center;font-weight: bold;"> Loading...</span>
        </include-fragment>
    </div>

</div>
</div>
</div>
<!-- container -->

{{--</div>--}}

<!-- content -->
<script src="{{asset('assets/js/fetchdata.min.js')}}"></script>
<script>
    $(function() {
    $('body').on('click', '.pagination a', function(e) {
    e.preventDefault();
    var url = $(this).attr('href');
    $.get(url, $('#search').serialize(), function(data){
        $('#js-developer-partial-target').html(data);
    });
});

$('#search').on('click', function(e){
    e.preventDefault();
        var search_keyword = $('#search_keyword').val();
        console.log(search_keyword);
        fetch('developer/fetch?search='+search_keyword)
        .then(response => response.text())
        .then(html=>{
            document.querySelector('#js-developer-partial-target').innerHTML = html
        });
});


});
</script>

@endsection

