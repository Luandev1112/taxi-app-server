<table class="table table-hover">
    <thead>
        <tr>
            <th> @lang('view_pages.s_no')</th>
            <th> @lang('view_pages.make')</th>
            <th> @lang('view_pages.name')</th>
            <th> @lang('view_pages.status')</th>
            <th> @lang('view_pages.action')</th>
        </tr>
    </thead>

<tbody>
    @php  $i= $results->firstItem(); @endphp

    @forelse($results as $key => $result)
        <tr>
            <td>{{ $i++ }} </td>
            <td>{{ $result->makeDetail->name }}</td>
            <td>{{ $result->name }}</td>
            @if($result->active)
            <td><span class="label label-success">@lang('view_pages.active')</span></td>
            @else
            <td><span class="label label-danger">@lang('view_pages.inactive')</span></td>
            @endif
            <td>

            <div class="dropdown">
            <button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">@lang('view_pages.action')
            </button>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="{{url('carmodel',$result->id)}}"><i class="fa fa-pencil"></i>@lang('view_pages.edit')</a>

                    @if($result->active)
                    <a class="dropdown-item" href="{{url('carmodel/toggle_status',$result->id)}}"><i class="fa fa-dot-circle-o"></i>@lang('view_pages.inactive')</a>
                    @else
                    <a class="dropdown-item" href="{{url('carmodel/toggle_status',$result->id)}}"><i class="fa fa-dot-circle-o"></i>@lang('view_pages.active')</a>
                    @endif

                    {{-- <a class="dropdown-item sweet-delete" href="{{url('carmodel/delete',$result->id)}}"><i class="fa fa-trash-o"></i>@lang('view_pages.delete')</a> --}}
                </div>
            </div>

            </td>
        </tr>
    @empty
        <tr>
            <td colspan="11">
                <p id="no_data" class="lead no-data text-center">
                    <img src="{{asset('assets/img/dark-data.svg')}}" style="width:150px;margin-top:25px;margin-bottom:25px;" alt="">
                    <h4 class="text-center" style="color:#333;font-size:25px;">NO DATA FOUND</h4>
                </p>
            </td>
        </tr>
    @endforelse

    </tbody>
    </table>
    <nav class="mt-15 pb-10">
        <ul class="pagination pagination-sm pull-right">
            <li class="page-item">
                <a class="page-link" href="#">{{$results->links()}}</a>
            </li>
        </ul>
    </nav>
