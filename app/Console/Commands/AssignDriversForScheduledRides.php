<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Jobs\NotifyViaMqtt;
use App\Models\Admin\Driver;
use App\Jobs\NotifyViaSocket;
use App\Models\Request\Request;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\Request\RequestMeta;
use App\Jobs\NoDriverFoundNotifyJob;
use App\Base\Constants\Masters\PushEnums;
use App\Jobs\Notifications\AndroidPushNotification;
use App\Transformers\Requests\TripRequestTransformer;
use App\Transformers\Requests\CronTripRequestTransformer;

class AssignDriversForScheduledRides extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'assign_drivers:for_schedule_rides';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Assign Drivers for schdeulesd rides';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $current_date = Carbon::now()->format('Y-m-d H:i:s');
        $add_45_min = Carbon::now()->addMinutes(45)->format('Y-m-d H:i:s');
        // DB::enableQueryLog();
        $requests = Request::where('is_later', 1)
                    ->where('trip_start_time', '<=', $add_45_min)
                    ->where('trip_start_time', '>', $current_date)
                    ->where('is_completed', 0)->where('is_cancelled', 0)->where('is_driver_started', 0)->get();

        if ($requests->count()==0) {
            return $this->info('no-schedule-rides-found');
        }

        // dd(DB::getQueryLog());
        foreach ($requests as $key => $request) {
            // Check if the request has any meta drivers
            if ($request->requestMeta()->exists()) {
                break;
            }
            // Get Drivers
            $pick_lat = $request->pick_lat;
            $pick_lng = $request->pick_lng;
            $type_id = $request->zoneType->type_id;


            $driver_search_radius = get_settings('driver_search_radius')?:30;

            $haversine = "(6371 * acos(cos(radians($pick_lat)) * cos(radians(pick_lat)) * cos(radians(pick_lng) - radians($pick_lng)) + sin(radians($pick_lat)) * sin(radians(pick_lat))))";
            // Get Drivers who are all going to accept or reject the some request that nears the user's current location.
            $meta_drivers = RequestMeta::whereHas('request.requestPlace', function ($query) use ($haversine,$driver_search_radius) {
                $query->select('request_places.*')->selectRaw("{$haversine} AS distance")
                ->whereRaw("{$haversine} < ?", [$driver_search_radius]);
            })->pluck('driver_id')->toArray();

            // NEW flow
            $client = new \GuzzleHttp\Client();
            $url = env('NODE_APP_URL').':'.env('NODE_APP_PORT').'/'.$pick_lat.'/'.$pick_lng.'/'.$type_id;

            $res = $client->request('GET', "$url");

            if ($res->getStatusCode() == 200) {
                $fire_drivers = \GuzzleHttp\json_decode($res->getBody()->getContents());
                if (empty($fire_drivers->data)) {
                    $this->info('no-drivers-available');
                    // @TODO Update attempt to the requests
                    $request->attempt_for_schedule += 1;
                    $request->save();
                    if ($request->attempt_for_schedule>5) {
                        $no_driver_request_ids = [];
                        $no_driver_request_ids[0] = $request->id;
                        dispatch(new NoDriverFoundNotifyJob($no_driver_request_ids));
                    }
                } else {
                    $nearest_driver_ids = [];
                    foreach ($fire_drivers->data as $key => $fire_driver) {
                        $nearest_driver_ids[] = $fire_driver->id;
                    }
                    $nearest_drivers = Driver::where('active', 1)->where('approve', 1)->where('available', 1)->where('vehicle_type', $type_id)->whereIn('id', $nearest_driver_ids)->whereNotIn('id', $meta_drivers)->limit(10)->get();

                    if ($nearest_drivers->isEmpty()) {
                        $this->info('no-drivers-available');
                        // @TODO Update attempt to the requests
                        $request->attempt_for_schedule += 1;
                        $request->save();
                        if ($request->attempt_for_schedule>5) {
                            $no_driver_request_ids = [];
                            $no_driver_request_ids[0] = $request->id;
                            dispatch(new NoDriverFoundNotifyJob($no_driver_request_ids));
                        }
                    } else {
                        $selected_drivers = [];
                        $i = 0;
                        foreach ($nearest_drivers as $driver) {
                            $selected_drivers[$i]["user_id"] = $request->user_id;
                            $selected_drivers[$i]["driver_id"] = $driver->id;
                            $selected_drivers[$i]["active"] = $i == 0 ? 1 : 0;
                            $selected_drivers[$i]["assign_method"] = 1;
                            $selected_drivers[$i]["created_at"] = date('Y-m-d H:i:s');
                            $selected_drivers[$i]["updated_at"] = date('Y-m-d H:i:s');
                            $i++;
                        }

                        // Send notification to the very first driver
                        $first_meta_driver = $selected_drivers[0]['driver_id'];
                        $request_result =  fractal($request, new CronTripRequestTransformer)->parseIncludes('userDetail');
                        $pus_request_detail = $request_result->toJson();
                        $push_data = ['notification_enum'=>PushEnums::REQUEST_CREATED,'result'=>(string)$pus_request_detail];
                        $title = trans('push_notifications.new_request_title');
                        $body = trans('push_notifications.new_request_body');

                        $socket_data = new \stdClass();
                        $socket_data->success = true;
                        $socket_data->success_message  = PushEnums::REQUEST_CREATED;
                        $socket_data->result = $request_result;

                        $driver = Driver::find($first_meta_driver);

                        $notifable_driver = $driver->user;
                        $notifable_driver->notify(new AndroidPushNotification($title, $body, $push_data));

                        // Form a socket sturcture using users'id and message with event name
                        // $socket_message = structure_for_socket($driver->id, 'driver', $socket_data, 'create_request');

                        // dispatch(new NotifyViaSocket('transfer_msg', $socket_message));

                        dispatch(new NotifyViaMqtt('create_request_'.$driver->id, json_encode($socket_data), $driver->id));

                        foreach ($selected_drivers as $key => $selected_driver) {
                            $request->requestMeta()->create($selected_driver);
                        }
                    }
                }
            } else {
                $this->info('there is an error-getting-drivers');
            }
        }

        $this->info('success');
    }
}
