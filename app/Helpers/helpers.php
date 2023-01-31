<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\Setting;
use App\Models\ProProManPlan;
use App\Models\Notification;

if (!function_exists('checkIfDeleted')) {
    function checkIfDeleted(string $tableName, int $id)
    {
        if ($tableName === "" || $id === "") {
            return "ERROR: Please provide arguments.";
        }
        $checkIfDelete = DB::table($tableName)->select('is_delete')->where('id', '=', $id)->get();
        return $checkIfDelete[0]->is_delete;
    }
}

if (!function_exists('getSettingValue')) {
    function getSettingValue(string $settingName)
    {
        if ($settingName === "" || $settingName === null) {
            return "Error: Please provide which setting to get.";
        }
        try {
            $setting = Setting::where('name', '=', $settingName)->get();
            return $setting[0]->value;
        } catch (Throwable $e) {
            return "Something went wrong with fetching '" . $settingName . "' setting.";
        }
    }
}

if (!function_exists('getPpmpYear')) {
    function getPpmpYear()
    {
        return Auth::user()->ppmp_year;
    }
}

if (!function_exists('getCartCount')) {
    function getCartCount()
    {
        return count(ProProManPlan::where('year', '=', Auth::user()->ppmp_year)
            ->where('is_draft', '=', 1)
            ->where('is_delete', '=', 0)
            ->where('submitted_by', '=', Auth::user()->id)
            ->get());
    }
}

if (!function_exists('sendNotification')) {
    function sendNotification(int $user_id, string $title, string $message, string $url): bool
    {
        if (!Auth::check()) {
            Session::flush();
            Auth::logout();
            return false;
        }
        DB::beginTransaction();
        try {
            $newNotif = new Notification();
            $newNotif->title = $title;
            $newNotif->message = $message;
            $newNotif->url = $url;
            $newNotif->is_read = false;
            $newNotif->sent_to = $user_id;
            $newNotif->sent_by = Auth::user()->id;
            $newNotif->save();
            DB::commit();
            return true;
        } catch (Throwable $e) {
            DB::rollBack();
            return false;
        }
    }
}
