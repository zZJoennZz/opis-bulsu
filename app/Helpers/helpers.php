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
            '', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine', 'Ten', 'Eleven',
            'Twelve', 'Thirteen', 'Fourteen', 'Fifteen', 'Sixteen', 'Seventeen', 'Eighteen', 'Nineteen'
        );

        $list2 = array('', 'Ten', 'Twenty', 'Thirty', 'Forty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety', 'Hundred');

        $list3 = array(
            '', 'Thousand', 'Million', 'Billion', 'Trillion', 'Quadrillion', 'Quintillion', 'Sextillion', 'Septillion',
            'Octillion', 'Nonillion', 'Decillion', 'Undecillion', 'Duodecillion', 'Tredecillion', 'Quattuordecillion',
            'Quindecillion', 'Sexdecillion', 'Septendecillion', 'Octodecillion', 'Novemdecillion', 'Vigintillion'
        );

        $num_length = strlen($num);
        $levels = (int) (($num_length + 2) / 3);
        $max_length = $levels * 3;
        $num = substr('00' . $num, -$max_length);
        $num_levels = str_split($num, 3);
        for ($i = 0; $i < count($num_levels); $i++) {
            $levels--;
            $hundreds = (int) ($num_levels[$i] / 100);
            $hundreds = ($hundreds ? ' ' . $list1[$hundreds] . ' Hundred' . ' ' : '');
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
        return implode(' ', $words) . " Pesos";
    }
}

if (!function_exists('ordinal')) {
    function ordinal($number)
    {
        $ends = array('th', 'st', 'nd', 'rd', 'th', 'th', 'th', 'th', 'th', 'th');
        if ((($number % 100) >= 11) && (($number % 100) <= 13)) {
            return $number . 'th';
        } else {
            return $number . $ends[$number % 10];
        }
    }
}

if (!function_exists('convertNumberToWords')) {
    function convertNumberToWords($number)
    {
        $words = array(
            'zero',
            'one',
            'two',
            'three',
            'four',
            'five',
            'six',
            'seven',
            'eight',
            'nine',
            'ten',
            'eleven',
            'twelve',
            'thirteen',
            'fourteen',
            'fifteen',
            'sixteen',
            'seventeen',
            'eighteen',
            'nineteen'
        );

        $tens = array(
            '',
            '',
            'twenty',
            'thirty',
            'forty',
            'fifty',
            'sixty',
            'seventy',
            'eighty',
            'ninety'
        );

        if (!is_numeric($number)) {
            return 'Not a number';
        }

        $number = (int)$number;

        if ($number < 0) {
            return 'minus ' . convertNumberToWords(abs($number));
        }

        if ($number < 20) {
            return $words[$number];
        }

        if ($number < 100) {
            return $tens[floor($number / 10)] . (($number % 10 > 0) ? '-' . $words[$number % 10] : '');
        }

        if ($number < 1000) {
            return $words[floor($number / 100)] . ' hundred' . (($number % 100 > 0) ? ' and ' . convertNumberToWords($number % 100) : '');
        }

        if ($number < 1000000) {
            return convertNumberToWords(floor($number / 1000)) . ' thousand' . (($number % 1000 > 0) ? ' ' . convertNumberToWords($number % 1000) : '');
        }

        return 'number too large';
    }
}

if (!function_exists('daysDiff')) {
    function daysDiff(DateTime $date1, DateTime $date2)
    {
        // Calculate the difference between the two dates
        $diff = $date2->diff($date1);

        // Calculate the number of days between the two dates
        $days = $diff->format('%r%a');

        // Check the order of the dates and mark the days accordingly
        return $days;
    }
}

if (!function_exists('pluralize')) {
    function pluralize($word, $count = 2)
    {
        if ($count === 1) {
            return $word;
        }

        $irregularPlurals = array(
            'man' => 'men',
            'woman' => 'women',
            // Add more irregular plurals here
        );

        $pluralRules = array(
            '/(s|x|sh|ch)$/i' => '$1es',
            '/([^aeiouy]|qu)y$/i' => '$1ies',
            '/(f|fe)$/i' => 'ves',
            '/(child)$/i' => '$1ren',
            '/(tooth)$/i' => '$1eeth',
            '/(person)$/i' => '$1s',
            '/(.*)(?=mouse$)/i' => '$1ice',
            // Add more plural rules here
        );

        foreach ($irregularPlurals as $singular => $plural) {
            if (preg_match('/' . $singular . '$/i', $word)) {
                return preg_replace('/' . $singular . '$/i', $plural, $word);
            }
        }

        foreach ($pluralRules as $pattern => $replacement) {
            if (preg_match($pattern, $word)) {
                return preg_replace($pattern, $replacement, $word);
            }
        }

        return $word . 's';
    }
}
