<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController as BaseController;
use App\Models\Category;
use App\Models\Item;
use App\Models\OtherItem;
use App\Models\Order;

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
                'quantity' => 'required'
            ]);
            if ($validator->fails()) {
                $response['message'] = $validator->messages()->first();
                $response['status'] = false;
                return $response;
            } else {

                 $itemId = $request->item_id ?: null;
                 $otherItemId = $request->other_item_id ?: null;

                 if($itemId) {
                    $name = Item::findOrFail($itemId)->name;
                 } else {
                    $name = OtherItem::findOrFail($otherItemId)->name;
                 }

                $order = new Order([
                    'service_provider_id' => $request->service_provider_id,
                    'user_id' => $user->id,
                    'item_id' => $itemId,
                    'other_item_id' => $otherItemId,
                    'name' => $name,
                    'quantity' => $request->quantity
                ]);

                $order->save();

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
}