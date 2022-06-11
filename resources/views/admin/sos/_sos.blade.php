<table class="table table-hover">
    <thead>
    <tr>


    <th> @lang('view_pages.s_no')
    <span style="float: right;">

    </span>
    </th>

    <th> @lang('view_pages.name')
    <span style="float: right;">
    </span>
    </th>
    <th> @lang('view_pages.number')
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


    @php  $i= $results->firstItem(); @endphp

    @forelse($results as $key => $result)

    <tr>
    <td>{{ $i++ }} </td>
    <td>{{$result->name}}</td>
    <td>{{$result->number}}</td>
    @if($result->active)
    <td><span class="label label-success">Active</span></td>
    @else
    <td><span class="label label-danger">InActive</span></td>
    @endif
    <td>

    <div class="dropdown">
    <button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">@lang('view_pages.action')
    </button>
        <div class="dropdown-menu">
            <a class="dropdown-item" href="{{url('sos',$result->id)}}">
            <i class="fa fa-pencil"></i>@lang('view_pages.edit')</a>

            @if($result->active)
            <a class="dropdown-item" href="{{url('sos/toggle_status',$result->id)}}">
            <i class="fa fa-dot-circle-o"></i>@lang('view_pages.inactive')</a>
            @else
            <a class="dropdown-item" href="{{url('sos/toggle_status',$result->id)}}">
            <i class="fa fa-dot-circle-o"></i>@lang('view_pages.active')</a>
            @endif

            <a class="dropdown-item sweet-delete" href="#" data-url="{{url('sos/delete',$result->id)}}">
            <i class="fa fa-trash-o"></i>@lang('view_pages.delete')</a>
        </div>
    </div>

    </td>
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
    <ul class="pagination pagination-sm pull-right">
        <li>
            <a href="#">{{$results->links()}}</a>
        </li>
    </ul>
