<?php

namespace App\Services;

use App\Models\View;
use DateTime;

class ViewService
{
    public static function checkDay($dateString)
    {
        $today = new DateTime('today');
        $date = new DateTime($dateString);

        if ($today > $date) {
            return true;
        }
        return false;
    }

    public static function view($model_id, $model, $model_type)
    {
        $view = new View();

        // if (auth('api')->check()) {
        //     $view->user_id = auth('api')->id();
        //     $view->ip = request()->ip();
        //     $view_exist = View::where(['user_id' => $view->user_id, 'viewable_id' => $model_id, 'viewable_type' => $model_type])->latest()->first();
        //     $view_exist_with_ip = View::where(['ip' => $view->ip, 'viewable_id' => $model_id, 'viewable_type' => $model_type])->latest()->first();
        //     if ($view_exist_with_ip && !self::checkDay($view_exist_with_ip->date)) {
        //         $view_exist_with_ip->user_id = auth('api')->id();
        //         $view_exist_with_ip->save();
        //         return;
        //     }
        // } else {
        //     $view->ip = request()->ip();
        //     $view_exist = View::where(['ip' => $view->ip, 'viewable_id' => $model_id, 'viewable_type' => $model_type])->latest()->first();
        // }

        // if ($view_exist && !self::checkDay($view_exist->date)) {
        //     return;
        // }

        $view->viewable_id = $model_id;
        $view->viewable_type = $model_type;
        $view->date = new DateTime('today');
        $view->save();
        $model->increment('views_count');
    }
}
