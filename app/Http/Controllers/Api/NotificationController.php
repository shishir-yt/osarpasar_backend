<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseController;
use App\Models\Order;
use App\Models\User;
use App\Notifications\user\OrderRequestNotification;
use Illuminate\Http\Request;

class NotificationController extends BaseController
{
    public function orderRequest(Request $request)
    {
        try {
            $user = auth()->user();
            $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
                'order_id' => 'required|exists:orders,id',
                'service_provider_id' => 'required',
            ]);
            if ($validator->fails()) {
                $response['message'] = $validator->messages()->first();
                $response['status'] = false;
                return $response;
            }
            $orderId = $request->order_id;

            $order = Order::where('id', $orderId)->first();
            $serviceProvider = User::where('id', $request->service_provider_id)->first();

            if(!$serviceProvider) {
                return response()->json([
                    'status' => false,
                    'message' => "Invalid Service Provider"
                ]);
            }

            if(!$order) {
                return response()->json([
                    'status' => false,
                    'message' => "Invalid Order"
                ]);
            }

            $serviceProvider->notify(new OrderRequestNotification($order));

            return response()->json([
                'status' => true,
                'message' => "Order Request Successfully."
            ]);

        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function notifications(Request $request)
    {
        $customer = auth()->user();
        $data = $customer->notifications()->orderBy('created_at', 'desc')->limit(20)->get();

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    function markRead(Request $request, $id)
    {
        $notification = auth()->user()->notifications->find($id);
        if ($notification) {
            $notification->markAsRead();
            return response()->json([
                'success' => true,
                'message' => "Mark read successfully."
            ]);
        }
        return response()->json([
            'success' => false,
            'message' => "Notification not found."
        ]);
    }

    function markAllRead(Request $request)
    {
        auth()->user()->notifications->markAsRead();
        return response()->json([
            'success' => true,
            'message' => "Mark all as read successfully."
        ]);
    }

    function unreadNotificationsCount(Request $request)
    {
        try {
            $totalUnreadNotifications = auth()->user()->unreadNotifications->count();
            if ($totalUnreadNotifications > 99)
            {
                $totalUnreadNotifications = "99+";
            } else {
                $totalUnreadNotifications = "".$totalUnreadNotifications."";
            }
            return response()->json([
               'success' => true,
               'data' => $totalUnreadNotifications
            ]);
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }
}