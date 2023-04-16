<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController as BaseController;
use App\Models\Address;
use App\Models\Category;
use App\Models\Item;
use App\Models\OtherItem;
use App\Models\Order;
use App\Models\OrderAddress;
use App\Models\Quantity;
use App\Models\User;

use function PHPUnit\Framework\isEmpty;

class ItemApiController extends BaseController
{
    public function getCategories(Request $request)
    {
        try {
            $categories = Category::where('service_provider_id', $request->id)->with('items')->get();

            return response()->json([
                'status' => true,
                'data' => ['categories' => $categories]
            ], 201);
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function addOtherItems(Request $request)
    {
        try {
            $user = auth()->user();
            $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
                'service_provider_id' => 'required|exists:users,id',
                'name' => 'required'
            ]);
            if ($validator->fails()) {
                $response['message'] = $validator->messages()->first();
                $response['status'] = false;
                return $response;
            } else {
                if($user) {
                    $otherItem = new OtherItem([
                        'name' => $request->name,
                        'user_id' => auth()->user()->id,
                        'service_provider_id' => $request->service_provider_id,
                    ]);
                    $otherItem->save();

                    // $listItems = OtherItem::where(['user_id'=> auth()->user()->id, 'service_provider_id' => $request->service_provider_id ])->get();

                    return response()->json([
                        'status' => true,
                        'message' => "Other Item Stored Successfully.",
                        'data' => ['OtherItem' => $otherItem]
                    ], 201);
                } else {
                    return response()->json([
                        'status' => false,
                        'message' => "The user is not authenticated."
                    ], 500);
                }

        }
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function getItems(Request $request)
    {
        try {
            $items = Item::where('service_provider_id', $request->id)->with('category')->get();
            return response()->json([
                'status' => true,
                'data' => ['items' => $items]
            ], 201);
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }



    public function storeOrder(Request $request)
    {



        try {
            $user = auth()->user();
            if($user) {
            $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
                'service_provider_id' => 'required|exists:users,id',
                // 'quantity' => 'required'
            ]);
            if ($validator->fails()) {
                $response['message'] = $validator->messages()->first();
                $response['status'] = false;
                return $response;
            } else {
                $address = new OrderAddress([
                "pickup_address"=>$request->pickup_address,
                "destination_address"=>$request->destination_address,
                "pickup_date"=> $request->pickup_date,
                "pickup_time"=>$request->pickup_time
            ]);
            $address->save();
                $order = new Order([
                    'service_provider_id' => $request->service_provider_id,
                    'user_id' => $user->id,
                    'order_address_id' => $address->id,
                    'status' => 'Pending',
                ]);
                $order->save();

                if(!empty($request->item_id))
                {

                    foreach($request->item_id as $i => $item)
                    {
                        $itemID = Item::findOrFail($item['id']);
                        $name = $itemID->name;

                        $data = [
                            "item_name"=> $name,
                            "quantity" => $item['quantity'],
                            "user_id"=> $user->id,
                            "service_provider_id" => $request->service_provider_id,
                            "order_id"=>$order->id
                        ];

                        $quantity = new Quantity($data);
                        $quantity->save();

                    }
                }

                if($request->other_item_id)
                {
                    foreach($request->other_item_id as $i => $item)
                    {
                        $othertIemID = OtherItem::findOrFail($item['id']);
                        $name = $othertIemID->name;
                        $data = [
                            "item_name"=> $name,
                            "quantity" => $item['quantity'],
                            "user_id"=> $user->id,
                            "service_provider_id" => $request->service_provider_id,
                            "order_id"=>$order->id
                        ];

                        $quantity = new Quantity($data);
                        $quantity->save();
                    }
                }
               $request["order_id"] = $order->id;
               $request["service_provider_id"] = $request->service_provider_id;
                $noti =  new  NotificationController();
                $noti->orderRequest( $request);

                return response()->json([
                    'status' => true,
                    'message' => "Order Stored Successfully.",
                    'data' => ['order' => $order]
                ],201);
            }
        } else {
            return response()->json([
                'status' => false,
                'message' => "The user is not authenticated."
            ], 500);
        }
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function orderDetails($id)
    {
        try{
            $order = Order::findOrFail($id);
            $user = User::findOrFail($order->user_id);
            $address = OrderAddress::findOrFail($order->order_address_id);
            $item = Quantity::where('order_id', $order->id)->get();
            $data = [
                'customer_name'=>$user->name,
                'pickup_address'=>$address->pickup_address,
                'destination_address'=>$address->destination_address,
                'pickup_date'=>$address->pickup_date,
                'pickup_time'=>$address->pickup_time,
                'items'=>$item,
            ];

            return response()->json([
                'success' => true,
                'data' => $data
            ], 201);
        }
        catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function getOrderStatus($id)
    {
        try {
            $user_id = auth()->user()->id;
            $order = Order::where(['user_id' => $user_id, 'status' => 'Pending'])->with('serviceProvider')->first();
            if(!$order) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid Order'
                ]);
        }
        return response()->json([
                    'success' => true,
                    'data' => $order
                ]);

        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function activeRequests(Request $request)
    {
        try {
            $user = auth()->user();
            $orders = Order::where(['user_id' => $user->id, 'status' => 'Pending'])->with('serviceProvider')->get();

            return response()->json([
                'success' => true,
                'data'=> $orders
            ]);

        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function activeBookings(Request $request)
    {
        try {
            $user = auth()->user();
            $orders = Order::where(['user_id' => $user->id, 'status' => 'Accepted'])->with('serviceProvider')->get();

            return response()->json([
                'success' => true,
                'data'=> $orders
            ]);

        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }
}
