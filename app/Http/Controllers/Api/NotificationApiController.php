<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController as BaseController;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use App\Notifications\User\RequestNotification;
use Illuminate\Http\Request;

class NotificationApiController extends BaseController
{
    public function requestOrder(Request $request)
    {
        try {
            $user = auth()->user();
//            $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
//                'order_id' => 'required|exists:orders,id',
//            ]);
//            if ($validator->fails()) {
//                $response['message'] = $validator->messages()->first();
//                $response['status'] = false;
//                return $response;
//            }

            $order = Order::where('id', 1)->first();
            $serviceProvider = User::where('id', 2)->first();
            $serviceProvider->notify(new RequestNotification($order));

        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }
}
