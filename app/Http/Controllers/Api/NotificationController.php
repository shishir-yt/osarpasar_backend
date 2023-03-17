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
            // $user = auth()->user();
            // $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            //     'order_id' => 'required|exists:orders,id',
            //     'service_provider_id' => 'required',
            // ]);
            // if ($validator->fails()) {
            //     $response['message'] = $validator->messages()->first();
            //     $response['status'] = false;
            //     return $response;
            // }
            // $orderId = $request->order_id;

            // $order = Order::where('id', $orderId)->first();
            $order = Order::where('id', 1)->first();
            // $serviceProvider = User::where('id', $request->service_provider_id)->first();
            $serviceProvider = User::where('id', 2)->first();

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
}