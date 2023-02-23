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

if (!function_exists('isJson')) {
    function isJson($string)
    {
        json_decode($string);
        return json_last_error() === JSON_ERROR_NONE;
    }
}

if (!function_exists('translateToWords')) {
    function translateToWords(int $number)
    {
        $num = str_replace(array(',', ' '), '', trim($number));
        if (!$num) {
            return false;
        }
        $num = (int) $num;
        $words = array();
        $list1 = array(
            '', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine', 'ten', 'eleven',
            'twelve', 'thirteen', 'fourteen', 'fifteen', 'sixteen', 'seventeen', 'eighteen', 'nineteen'
        );
        $list2 = array('', 'ten', 'twenty', 'thirty', 'forty', 'fifty', 'sixty', 'seventy', 'eighty', 'ninety', 'hundred');
        $list3 = array(
            '', 'thousand', 'million', 'billion', 'trillion', 'quadrillion', 'quintillion', 'sextillion', 'septillion',
            'octillion', 'nonillion', 'decillion', 'undecillion', 'duodecillion', 'tredecillion', 'quattuordecillion',
            'quindecillion', 'sexdecillion', 'septendecillion', 'octodecillion', 'novemdecillion', 'vigintillion'
        );
        $num_length = strlen($num);
        $levels = (int) (($num_length + 2) / 3);
        $max_length = $levels * 3;
        $num = substr('00' . $num, -$max_length);
        $num_levels = str_split($num, 3);
        for ($i = 0; $i < count($num_levels); $i++) {
            $levels--;
            $hundreds = (int) ($num_levels[$i] / 100);
            $hundreds = ($hundreds ? ' ' . $list1[$hundreds] . ' hundred' . ' ' : '');
            $tens = (int) ($num_levels[$i] % 100);
            $singles = '';
            if ($tens < 20) {
                $tens = ($tens ? ' ' . $list1[$tens] . ' ' : '');
            } else {
                $tens = (int)($tens / 10);
                $tens = ' ' . $list2[$tens] . ' ';
                $singles = (int) ($num_levels[$i] % 10);
                $singles = ' ' . $list1[$singles] . ' ';
            }
            $words[] = $hundreds . $tens . $singles . (($levels && (int) ($num_levels[$i])) ? ' ' . $list3[$levels] . ' ' : '');
        } //end for loop
        $commas = count($words);
        if ($commas > 1) {
            $commas = $commas - 1;
        }
        return implode(' ', $words) . " pesos";
    }
}
