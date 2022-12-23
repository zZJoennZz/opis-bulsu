<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Throwable;

class NotificationController extends Controller
{
    public $view = 'global/notifications';
    public $route = 'notification.show';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $user = Auth::user();
        $userNotif = Notification::where('sent_to', '=', $user->id)->orderBy('created_at', 'DESC')->get();

        return view($this->view)->with('user_notif', $userNotif);
    }

    public function read($notif_id)
    {
        $user = Auth::user();

        $userNotif = Notification::find($notif_id);
        $userNotif->is_read = true;

        DB::beginTransaction();
        try {
            $userNotif->save();
            DB::commit();
            $userNotifAll = Notification::where('sent_to', '=', $user->id)->get();
            return redirect()->route($this->route)->with('user_notif', $userNotifAll);
        } catch (Throwable $e) {
            DB::rollBack();
            $userNotifAll = Notification::where('sent_to', '=', $user->id)->get();
            return redirect()->route($this->route)->with('user_notif', $userNotifAll);
        }
    }
}
