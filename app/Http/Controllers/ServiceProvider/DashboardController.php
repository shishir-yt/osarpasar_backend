<?php

namespace App\Http\Controllers\ServiceProvider;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Item;
use App\Models\Order;
use App\Models\Payment;
use App\Models\OrderAddress;
use App\Models\Quantity;
use App\Models\User;
use Illuminate\Http\Request;
use App\Notifications\user\ResponseOrderNotification;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function home()
    {
        $data['categories'] = Category::where('service_provider_id', auth()->user()->id)->count();
        $data['items'] = Item::where('service_provider_id', auth()->user()->id)->count();
        $data['notifications'] = Auth::user()->notifications->count();
        $collections = 0;
        $payments = Payment::where('service_provider_id', auth()->user()->id)->get();
        forEach($payments ?? [] as $payment) {
            if($payment->order){
                $collections += $payment->order->price ?: 0;
            }
        }
        $data['collections'] = $collections;
        return view('service_providers.home', $data);
    }

    public function profile(Request $request)
    {
        return view('service_providers.profile');
    }

    public function profileStore(Request $request)
    {
        $data = $request->all();
        $serviceProvider = User::findOrFail(auth()->user()->id);
        $serviceProvider->update($data);

        if ($request->profile_image) {
                $serviceProvider->clearMediaCollection();
                $serviceProvider->addMediaFromRequest('profile_image')
                    ->toMediaCollection();
                $serviceProvider->save();

        }

        return redirect()->route('serviceProvider.profile')->with('success', 'Profile updated successfully.');
    }

    public function orderRequest($id)
    {

        $order = Order::findOrFail($id);
        $info['items'] = Quantity::where('order_id', $order->id)->get();
        $info['order'] = $order;
        return view('service_providers.order-details', $info);
    }

    public function response(Request $request)
    {
        $user = User::findOrFail($request->user_id);
        $note = $request->note;
        $serviceProvider = auth()->user();
        $order = Order::where('id', $request->order_id)->first();
        $order->status = $request->status;
        $order->price = $request->price;
        $order->update();

        $user->notify(new ResponseOrderNotification($note, $serviceProvider, $order));

        return redirect()->back()->with('success', 'Response Sent Successfully.');
    }
}