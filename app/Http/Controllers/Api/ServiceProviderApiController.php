<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController as BaseController;
use App\Models\User;

class ServiceProviderApiController extends BaseController
{
    public function getServiceProviders()
    {
        try {
            $serviceProviders = User::where('is_admin', "0")->get();
            return response()->json([
                'status' => true,
                'data' => ['serviceProviders' => $serviceProviders]
            ], 201);
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }
}
