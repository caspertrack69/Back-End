<?php

namespace App\Http\Controllers\Api\V1;

use App\CentralLogics\helpers;
use App\Http\Controllers\Controller;
use App\Http\Resources\NotificationResource;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NotificationController extends Controller
{
    public function get_customer_notification(Request $request)
    {
        $limit = $request->has('limit') ? $request->limit : 10;
        $offset = $request->has('offset') ? $request->offset : 1;

        $notifications = Notification::active()->where('receiver', 'customers')->orWhere('receiver', 'all')->latest()->paginate($limit, ['*'], 'page', $offset);
        $notifications = NotificationResource::collection($notifications);
        return [
            'total_size' => $notifications->total(),
            'limit' => $limit,
            'offset' => $offset,
            'notifications' => $notifications->items()
        ];
    }

    public function get_agent_notification(Request $request)
    {
        $limit = $request->has('limit') ? $request->limit : 10;
        $offset = $request->has('offset') ? $request->offset : 1;

        $notifications = Notification::active()->where('receiver', 'agents')->orWhere('receiver', 'all')->paginate($limit, ['*'], 'page', $offset);
        $notifications = NotificationResource::collection($notifications);
        return [
            'total_size' => $notifications->total(),
            'limit' => $limit,
            'offset' => $offset,
            'notifications' => $notifications->items()
        ];
    }
}
