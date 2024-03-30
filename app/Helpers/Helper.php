<?php
use App\Notifications\GeneralNotification;
use App\Models\Admin;


function send_admin_notification($fcm_data)
{
    $admins = Admin::active()->get();
    foreach ($admins as $admin) {
        $admin->notify(new GeneralNotification($fcm_data, ['broadcast', 'database']));
    }
}
