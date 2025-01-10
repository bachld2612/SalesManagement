<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    //
    public function getNotifications()
    {
        $userId = Auth::id();

        // Gọi Stored Procedure
        $notifications = DB::select('EXEC sp_GetNotificationsByUser ?', [$userId]);

        return response()->json($notifications); // Trả về dạng JSON
    }

    public function count()
    {
        $userId = auth()->id();
        $notificationCount = DB::select('SELECT dbo.fn_CountNotifications(?) AS count', [$userId]);
        return response()->json(['count' => $notificationCount[0]->count]);
    }
}
