<?php

namespace App\Http\Controllers\ServiceProvider;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class PaymentController extends BaseController
{
    public function __construct()
    {
        $this->title = "Payments";
        $this->resources = "service_providers.payments.";
        parent::__construct();
        $this->route = "payments.";
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Payment::where('service_provider_id', auth()->user()->id)->orderBy('id', 'DESC');
            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('order_id', function ($data) {
                    if($data->order) {
                        return '#'. $data->order->id;
                    } else {
                        return 'N/A';
                    }
                })
                ->editColumn('price', function ($data) {
                    if($data->order) {
                        return $data->order->price ?: 'N/A';
                    } else {
                        return 'N/A';
                    }
                })
                ->editColumn('user', function ($data) {
                    if($data->order) {
                        return $data->order->user ? $data->order->user->name : 'N/A';
                    } else {
                        return 'N/A';
                    }
                })
                ->editColumn('created_at', function($data) {
                    return $data->created_at ? $data->created_at->diffForHumans() : 'N/A';
                })
                ->filter(function ($query) {
                    $query->where('order_id', 'like', "%" . request('orderFilter') . "%");
                }, true)
                ->rawColumns(['order_id', 'price', 'user', 'created_at'])
                ->make(true);
        }
        $info = $this->crudInfo();
        $info['hideCreate'] = true;
        return view($this->indexResource(), $info);
    }
}
