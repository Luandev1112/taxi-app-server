<div class="box-body no-padding">
    <div class="table-responsive">
      <table class="table table-hover">
    <thead>
    <tr>

    <th> @lang('view_pages.s_no')
    <span style="float: right;">

    </span>
    </th>

    <th> @lang('view_pages.developer_name')
    <span style="float: right;">
    </span>
    </th>
    <th> @lang('view_pages.team')
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
    <td> {{ $result->first_name .' '.$result->last_name }}</td>
    <td>{{$result->team}}</td>
    <td>{{$result->mobile}}</td>
    <td>{{$result->email}}</td>
     @if($result->user->active)
    <td><button class="btn btn-success btn-sm">Active</button></td>
    @else
    <td><button class="btn btn-danger btn-sm">InActive</button></td>
    @endif

    <td>
    <div class="dropdown">
    <button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">@lang('view_pages.action')
    </button>
        <div class="dropdown-menu">
            <a class="dropdown-item" href="{{url('developer/edit',$result->id)}}"><i class="fa fa-pencil"></i>@lang('view_pages.edit')</a>
             @if($result->user->active)
            <a class="dropdown-item" href="{{url('developer/toggle_status',$result->user->id)}}">
            <i class="fa fa-dot-circle-o"></i>@lang('view_pages.inactive')</a>
            @else
            <a class="dropdown-item" href="{{url('developer/toggle_status',$result->user->id)}}">
            <i class="fa fa-dot-circle-o"></i>@lang('view_pages.active')</a>
            @endif
            <a class="dropdown-item sweet-delete" href="{{url('developer/delete',$result->id)}}"><i class="fa fa-trash-o"></i>@lang('view_pages.delete')</a>
        </div>
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
    </div></div></div>
