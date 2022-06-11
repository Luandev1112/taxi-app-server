<div class="row p-0 m-0">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="col-sm-12 p-0">
                    <table class="table table-hover" style="border-collapse: collapse; border-spacing: 0px; width: 100%;" id="DataTables_Table_0" role="grid" aria-describedby="DataTables_Table_0_info">
                        <thead>
                            <tr>
                                <th> @lang('view_pages.s_no')</th>
                                <th> @lang('view_pages.company_name')</th>
                                <th> @lang('view_pages.owner_name')</th>
                                <th> @lang('view_pages.email')</th>
                                <th> @lang('view_pages.mobile')</th>
                                <th> @lang('view_pages.document_view')</th>
                                <th> @lang('view_pages.approve_status')</th>
                                {{-- @if (auth()->user()->can('add-owner')) --}}
                                    <th> @lang('view_pages.action')</th>    
                                {{-- @endif --}}
                                
                            </tr>
                        </thead>

                        <tbody>
                            @if (count($results) < 1)
                                <td class="no-result" colspan="11">{{ trans('view_pages.no_data_found')}}</td>
                            @else

                                @php $i= $results->firstItem(); @endphp

                                @foreach ($results as $key => $result)

                                    <tr>
                                        <td>{{ $i++ }} </td>
                                        <td> {{ $result->company_name }}</td>
                                        <td> {{ $result->owner_name }}</td>
                                        <td>{{ $result->email }}</td>
                                        <td>{{ $result->mobile }}</td>
                                        <td class="manage-driver text-center">
                                            <a href="{{url('owners/document/view', $result->id) }}" class="btn btn-social-icon btn-bitbucket">
                                                <i class="fa fa-file-code-o"></i>
                                            </a>
                                        </td>

                                        <td>
                                            @if ($result->approve == '1')
                                                <span class="badge badge-success font-size-10">{{trans('view_pages.approved')}}</span>
                                            @else
                                                <span class="badge badge-danger font-size-10">{{trans('view_pages.blocked')}}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="dropdown">
    <button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">@lang('view_pages.action')
    </button>
        <div class="dropdown-menu">
            <a class="dropdown-item" href="{{url('owners',$result->id)}}">
            <i class="fa fa-pencil"></i>@lang('view_pages.edit')</a>

            @if($result->approve=='1')
            <a class="dropdown-item" href="{{url('owners/toggle_approve',$result->id)}}">
            <i class="fa fa-dot-circle-o"></i>@lang('view_pages.blocked')</a>
            @else
            <a class="dropdown-item" href="{{url('owners/toggle_approve',$result->id)}}">
            <i class="fa fa-dot-circle-o"></i>@lang('view_pages.approve')</a>
            @endif
            <a class="dropdown-item sweet-delete" href="{{url('owners/delete',$result->id)}}">
            <i class="fa fa-trash-o"></i>@lang('view_pages.delete')</a>
           

        </div>
    </div>
                                        </td>
{{-- 
                                        <td class="action">
                                            <div class="dropdown">
                                                <button class="dropbtn">Actions</button>
                                                <div class="dropdown-content">
                                                    @if (auth()->user()->can('edit-owner'))
                                                        <a class="grey-text text-darken-2" href="{{url('owners',$result->id) }}">{{ trans('view_pages.edit')}}</a>
                                                    @endif

                                                    @if (auth()->user()->can('delete-owner'))
                                                        <a class="sweet-delete grey-text text-darken-2" href="#" data-url="{{url('owners/delete',$result->id) }}">{{ trans('view_pages.delete')}}</a>
                                                    @endif
                                                    
                                                    @if (auth()->user()->can('toggle-owner-status'))
                                                        @if ($result->approve)
                                                            <a class="sweet-decline grey-text text-darken-2" href="{{url('owners/toggle_approve',$result->id) }}">{{ trans('view_pages.block')}}</a>
                                                        @else
                                                            <a class="sweet-approve grey-text text-darken-2" href="{{url('owners/toggle_approve',$result->id) }}">{{ trans('view_pages.approve')}}</a>
                                                        @endif
                                                    @endif
                                                </div>
                                            </div>
                                        </td> --}}
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="">
                <div class="col-sm-12 col-md-5 float-left">

                </div>
                <div class="col-sm-12 col-md-7 float-left">
                    <div class="dataTables_paginate paging_simple_numbers" id="DataTables_Table_0_paginate">
                        <ul class="pagination float-right">
                            {{ $results->links() }}
                        </ul>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
