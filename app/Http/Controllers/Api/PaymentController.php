<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Order;
use App\Models\Payment;

class PaymentController extends Controller
{
    public function verifyPayment(Request $request){
        $request->validate([
        "token"=>"required|string",
        "amount"=>"required",
        "payment_method"=>"required",
        // "service_provider_id"=>"required|exists:users,id",
        "order_id" => "required|exists:orders,id"
    ]);
        // $response = Http::withHeaders([
        //     'Authorization' => "Key ".env("KHALTI_SECRET_KEY"),

        // ])->post('https://khalti.com/api/v2/payment/verify/', [
        //     'token' => request()->token,
        //     'amount' => request()->amount,
        // ]);

        // $response_data = json_decode($response->body(), TRUE);
        // error_log($response->body());
        // if(!$response->ok() && $response->failed()){

            $args = http_build_query(array(
                'token' => $request->token,
                'amount' => $request->amount * 100
            ));

            $url = "https://khalti.com/api/v2/payment/verify/";

# Make the call using API.
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $args);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

            $headers = ['Authorization: Key '.env("KHALTI_SECRET_KEY")];
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

// Response
            $response = curl_exec($ch);
            if (curl_errno($ch)) {
                $error_msg = curl_error($ch);
            } else {
                $error_msg = null;
            }
            $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            error_log($response, $status_code);

//            $response_data = json_decode($response->body(), TRUE);

//            error_log($response->status());
//
//            error_log(json_encode($response->body()));

            if ($status_code == 400) {
                return response()->json([
                    "success" => false,
                    "message" => "Payment failed."
                ]);
            }
        //     return response()->json([
        //         'status' => false,
        //         'message' => "Payment Failed",
        //         'data' => $response_data,
        //     ], 200);
        // }

        $order = Order::where('id', $request->order_id)->first();

        if(!$order)
        {
            return response()->json([
                'success' => false,
                'message' => "Invalid order."
            ]);
        }

        $order->status = "Paid";
        $order->update();

        $payment = new Payment();
        $payment->service_provider_id = $order->service_provider_id;
        $payment->order_id = $request->order_id;
        $payment->payment_method = $request->payment_method;
        $payment->save();

        return response()->json([
            'status' => true,
            'message' => "Payment Success",
            // 'data' => $response_data,
        ], 200);



    }
}
