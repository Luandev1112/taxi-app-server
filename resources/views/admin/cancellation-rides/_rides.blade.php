<table class="table table-hover">
    <thead>
        <tr>
            <th> @lang('view_pages.s_no')</th>
            <th> @lang('view_pages.request_id')</th>
            <th> @lang('view_pages.date')</th>
            <th> @lang('view_pages.user_name')</th>
            <th> @lang('view_pages.driver_name')</th>
            <th> @lang('view_pages.cancelled_by')</th>
            <th> @lang('view_pages.cancellation_reason')</th>
            <th> @lang('view_pages.cancellation_fee')</th>
            <th> @lang('view_pages.paid')</th>
            
        </tr>
    </thead>

<tbody>
    @php  $i= $results->firstItem(); @endphp

    @forelse($results as $key => $result)
        <tr>
            <td>{{ $i++ }} </td>
            <td>{{$result->requestDetail->request_number}}</td>
            <td>{{$result->requestDetail->getConvertedAcceptedAtAttribute()}}</td>
            <td>
                <span>{{$result->userDetail->name ?? '-' }}</span>
            </td>
            <td>
                <span>{{ $result->driverDetail->name ?? '-' }}</span>
            </td>
             <td>
                @if ($result->requestDetail->cancel_method == 0)
                   <span>Automatic</span>
                @else
                   @if ($result->requestDetail->cancel_method == 1)
                        <span>User</span>
                   @else
                        <span>Driver</span>
                   @endif
                @endif
            </td>

             <td>
               {{$result->requestDetail->cancelReason?$result->requestDetail->cancelReason->reason:'-'}}
            </td>
           

            <td class="text-center">
                <span class="label label-warning">{{$result->requestdetail->requested_currency_symbol ?? $result->requestDetail->requested_currency_code }} {{ $result->cancellation_fee }}</span>
            </td>
            
            @if($result->is_paid)
            <td><span class="label label-success">@lang('view_pages.paid')</span></td>
            @else
            <td><span class="label label-danger">@lang('view_pages.not_paid')</span></td>
            @endif
            @if(!auth()->user()->company_key)
           
            @endif
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
    <ul class="pagination pagination-sm pull-right">
        <li>
            <a href="#">{{$results->links()}}</a>
        </li>
    </ul>
