<?php

use Illuminate\Support\Facades\DB;

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
