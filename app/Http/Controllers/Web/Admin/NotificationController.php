<?php

namespace App\Http\Controllers\Web\Admin;

use App\Base\Constants\Auth\Role;
use App\Base\Constants\Masters\PushEnums;
use App\Base\Filters\Master\CommonMasterFilter;
use App\Base\Libraries\QueryFilter\QueryFilterContract;
use App\Base\Services\ImageUploader\ImageUploaderContract;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Web\BaseController;
use App\Jobs\Notifications\AndroidPushNotification;
use App\Jobs\UserDriverNotificationSaveJob;
use App\Models\Admin\Driver;
use App\Models\Admin\Notification;
use App\Models\User;
use Illuminate\Http\Request;

class NotificationController extends BaseController
{
    protected $notification;

    protected $imageUploader;
    /**
     * NotificationController constructor.
     *
     * @param \App\Models\Admin\Notification $notification
     */
    public function __construct(Notification $notification, ImageUploaderContract $imageUploader)
    {
        $this->notification = $notification;
        $this->imageUploader = $imageUploader;
    }

    public function index()
    {
        $page = trans('pages_names.push_notification');

        $main_menu = 'notifications';
        $sub_menu = 'push_notification';

        return view('admin.notification.push.index', compact('page', 'main_menu', 'sub_menu'));
    }

    public function fetch(QueryFilterContract $queryFilter)
    {
        $query = $this->notification->query();
        $results = $queryFilter->builder($query)->customFilter(new CommonMasterFilter)->paginate();

        return view('admin.notification.push._pushnotification', compact('results'));
    }

    public function pushView()
    {
        $page = trans('pages_names.push_notification');

        $main_menu = 'notifications';
        $sub_menu = 'push_notification';

        $users = User::companyKey()->belongsToRole(Role::USER)->active()->get();
        $drivers = Driver::get();

        if (env('APP_FOR')=='demo') {
            $drivers = Driver::whereHas('user', function ($query) {
                $query->where('company_key', auth()->user()->company_key);
            })->get();
        }

        return view('admin.notification.push.sendpush', compact('page', 'main_menu', 'sub_menu', 'users', 'drivers'));
    }

    public function sendPush(Request $request)
    {
        $created_params = $request->only(['title']);
        $created_params['push_enum'] = PushEnums::GENERAL_NOTIFICATION;
        $created_params['body'] = $request->message;

        if ($uploadedFile = $this->getValidatedUpload('image', $request)) {
            $created_params['image'] = $this->imageUploader->file($uploadedFile)
                ->savePushImage();
        }

        $notification = $this->notification->create($created_params);

        if ($request->has('user')) {
            $notification->update(['for_user' => true]);

            User::whereIn('id', $request->user)->chunk(20, function ($userData) use ($notification,$request) {
                $title = $notification->title;
                $body = $notification->body;
                $push_data = ['title' => $notification->title,'message' => $notification->body,'image' => $notification->push_image];
                $image = $notification->push_image;

                foreach ($userData as $key => $value) {
                    $value->notify(new AndroidPushNotification($title, $body, $push_data, $image));
                }
            });
        }

        if ($request->has('driver')) {
            $notification->update(['for_driver' => true]);

            Driver::whereIn('id', $request->driver)->chunk(20, function ($driverData) use ($notification,$request) {
                $title = $notification->title;
                $body = $notification->body;
                $push_data = ['title' => $notification->title,'message' => $notification->body,'image' => $notification->push_image];
                $image = $notification->push_image;

                foreach ($driverData as $key => $value) {
                    $value->user->notify(new AndroidPushNotification($title, $body, $push_data, $image));
                }
            });
        }

        dispatch(new UserDriverNotificationSaveJob($request->user, $request->driver, $notification));

        $message = trans('succes_messages.push_notification_send_successfully');

        return redirect('notifications/push')->with('success', $message);
    }

    public function delete(Notification $notification)
    {
        $notification->delete();

        $message = trans('succes_messages.push_notification_deleted_successfully');

        return redirect('notifications/push')->with('success', $message);
    }
}
