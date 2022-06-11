<?php

namespace App\Jobs;

use App\Jobs\NotifyViaMqtt;
use App\Jobs\NotifyViaSocket;
use Illuminate\Bus\Queueable;
use App\Models\Request\Request;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use App\Base\Constants\Masters\PushEnums;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Jobs\Notifications\AndroidPushNotification;
use App\Transformers\Requests\TripRequestTransformer;
use App\Transformers\Requests\CronTripRequestTransformer;

class NoDriverFoundNotifyJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $requestids;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($requestids)
    {
        $this->requestids = $requestids;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach ($this->requestids as $key => $request_id) {
            $request_detail = Request::find($request_id);
            $request_detail->update(['is_cancelled'=>true,'cancel_method'=>0,'cancelled_at'=>date('Y-m-d H:i:s')]);
            $request_detail->fresh();
            $request_result =  fractal($request_detail, new CronTripRequestTransformer);
            $pus_request_detail = $request_result->toJson();
            $title = trans('push_notifications.no_driver_found_title');
            $body = trans('push_notifications.no_driver_found_body');
            $push_data = ['notification_enum'=>PushEnums::NO_DRIVER_FOUND];
            if ($request_detail->userDetail()->exists()) {
                $user = $request_detail->userDetail;
                $socket_data = new \stdClass();
                $socket_data->success = true;
                $socket_data->success_message  = PushEnums::NO_DRIVER_FOUND;
                $socket_data->result = $request_result;
                // Form a socket sturcture using users'id and message with event name
                // $socket_message = structure_for_socket($user->id, 'user', $socket_data, 'trip_status');

                // dispatch(new NotifyViaSocket('transfer_msg', $socket_message));

                dispatch(new NotifyViaMqtt('trip_status_'.$user->id, json_encode($socket_data), $user->id));

                $user->notify(new AndroidPushNotification($title, $body, $push_data));
            }
        }
    }
}
