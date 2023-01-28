<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController as BaseController;
use App\Models\Address;
use App\Models\OrderAddress;
use App\Models\Order;

class AddressApiController extends BaseController
{
    public function getAddress(Request $request)
    {
        try {
            $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
                'service_provider_id' => 'required|exists:users,id',
            ]);
            if ($validator->fails()) {
                $response['message'] = $validator->messages()->first();
                $response['status'] = false;
                return $response;
            } else {
                $addresses = Address::where('service_provider_id', $request->service_provider_id)->get();

                return response()->json([
                    'status' => true,
                    'data' => ['addresses' => $addresses]
                ], 201);
            }
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function storeOrderAddress(Request $request)
    {
        try {
            $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
                'order_id' => 'required|exists:orders,id',
                'pickup_address' => 'required',
                'destination_address' => 'required',
                'pickup_date' => 'required',
                'pickup_time' => 'required',
            ]);
            if ($validator->fails()) {
                $response['message'] = $validator->messages()->first();
                $response['status'] = false;
                return $response;
            } else {
                $orderAddress = new OrderAddress([
                    'pickup_address' => $request->pickup_address,
                    'destination_address' => $request->destination_address,
                    'pickup_date' => $request->pickup_date,
                    'pickup_time' => $request->pickup_time,
                ]);

                $orderAddress->save();

                $order = Order::findOrFail($request->order_id);

                if($order) {
                    $order->order_address_id = $orderAddress->id;
                    $order->update();

                    return response()->json([
                        'status' => true,
                        'message' => "Address and Time Stored Successfully.",
                        "data" => ['order' => $order, 'orderAddress' => $orderAddress]
                    ], 201);
                } else {
                    return response()->json([
                        'status' => false,
                        'message' => "This address does not has any order."
                    ]);
                }
            }
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }
}