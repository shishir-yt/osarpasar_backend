<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Models\District;
use App\Models\Province;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('forget-password', [AuthController::class, 'forgetPassword']);
Route::post('reset-password', [AuthController::class, 'resetPassword']);

Route::group(['middleware' => 'auth:api'], function () {
    // Route::get('/service-providers', [App\Http\Controllers\Api\ServiceProviderApiController::class, "getServiceProviders"]);
//     Route::get('/items', [App\Http\Controllers\Api\ItemApiController::class, "getItems"]);
// Route::get('/categories', [App\Http\Controllers\Api\ItemApiController::class, "getCategories"]);
Route::post('/update-profile', [AuthController::class, 'updateProfile']);
Route::post('/other-items/store', [App\Http\Controllers\Api\ItemApiController::class, "addOtherItems"]);
Route::post('/orders/store', [App\Http\Controllers\Api\ItemApiController::class, "storeOrder"]);
Route::get('/order-details/{id}', [App\Http\Controllers\Api\ItemApiController::class, "orderDetails"]);
Route::get('/order-status', [App\Http\Controllers\Api\ItemApiController::class, "getOrderStatus"]);
Route::get('/active-requests', [App\Http\Controllers\Api\ItemApiController::class, "activeRequests"]);
Route::get('/active-bookings', [App\Http\Controllers\Api\ItemApiController::class, "activeBookings"]);
// Route::get('address', [App\Http\Controllers\Api\AddressApiController::class, "getAddress"]);

Route::group(['prefix' => 'notifications'], function () {
    Route::get('list', [\App\Http\Controllers\Api\NotificationController::class, 'notifications']);
    Route::get('mark-all-read', [\App\Http\Controllers\Api\NotificationController::class,'markAllRead']);
    Route::get('mark-read/{id}', [\App\Http\Controllers\Api\NotificationController::class,'markRead']);
    Route::get('unread-notifications', [\App\Http\Controllers\Api\NotificationController::class,'unreadNotificationsCount']);
});

});
Route::get('/service-providers', [App\Http\Controllers\Api\ServiceProviderApiController::class, "getServiceProviders"]);
Route::get('/items/{id}', [App\Http\Controllers\Api\ItemApiController::class, "getItems"]);
Route::get('/categories/{id}', [App\Http\Controllers\Api\ItemApiController::class, "getCategories"]);
Route::get('address', [App\Http\Controllers\Api\AddressApiController::class, "getAddress"]);
Route::get('provinces-address', [App\Http\Controllers\ProvinceController::class, "index"]);
Route::post('order-address', [App\Http\Controllers\Api\AddressApiController::class, "storeOrderAddress"]);
Route::get('/order-request', [App\Http\Controllers\Api\NotificationController::class, "orderRequest"]);
Route::post('/complete-request', [App\Http\Controllers\Api\PaymentController::class, "verifyPayment"]);

Route::get("provinces",function(){
    $provinceParsedArray = [
        [
            "id" => 1,
            "name" => "Province No 1",
         ],
         [
            "id" => 2,
            "name" => "Madhesh",
         ],
         [
            "id" => 3,
            "name" => "Bagmati",
         ],
         [
            "id" => 4,
            "name" => "Gandaki",
         ],
         [
            "id" => 5,
            "name" => "Lumbini",
         ],
         [
            "id" => 6,
            "name" => "Karnali",
         ],
         [
            "id" => 7,
            "name" => "Sudur Pashchim"
         ],
        ];

        foreach($provinceParsedArray as $province){

            $pr = new Province();

            $pr->id = $province["id"];
            $pr->name = $province["name"];
            $pr->save();
           }
           return "ok";
});

Route::get("districts",function(){



    $jayParsedAry = [
      [
            "id" => 1,
            "name" => "Bhojpur",
            "province_id" => 1
         ],
      [
               "id" => 2,
               "name" => "Dhankuta",
               "province_id" => 1
            ],
      [
                  "id" => 3,
                  "name" => "Ilam",
                  "province_id" => 1
               ],
      [
                     "id" => 4,
                     "name" => "Jhapa",
                     "province_id" => 1
                  ],
      [
                        "id" => 5,
                        "name" => "Khotang",
                        "province_id" => 1
                     ],
      [
                           "id" => 6,
                           "name" => "Morang",
                           "province_id" => 1
                        ],
      [
                              "id" => 7,
                              "name" => "Okhaldhunga",
                              "province_id" => 1
                           ],
      [
                                 "id" => 8,
                                 "name" => "Pachthar",
                                 "province_id" => 1
                              ],
      [
                                    "id" => 9,
                                    "name" => "Sankhuwasabha",
                                    "province_id" => 1
                                 ],
      [
                                       "id" => 10,
                                       "name" => "Solukhumbu",
                                       "province_id" => 1
                                    ],
      [
                                          "id" => 11,
                                          "name" => "Sunsari",
                                          "province_id" => 1
                                       ],
      [
                                             "id" => 12,
                                             "name" => "Taplejung",
                                             "province_id" => 1
                                          ],
      [
                                                "id" => 13,
                                                "name" => "Terhathum",
                                                "province_id" => 1
                                             ],
      [
                                                   "id" => 14,
                                                   "name" => "Udayapur",
                                                   "province_id" => 1
                                                ],
      [
                                                      "id" => 15,
                                                      "name" => "Parsa",
                                                      "province_id" => 2
                                                   ],
      [
                                                         "id" => 16,
                                                         "name" => "Bara",
                                                         "province_id" => 2
                                                      ],
      [
                                                            "id" => 17,
                                                            "name" => "Rautahat",
                                                            "province_id" => 2
                                                         ],
      [
                                                               "id" => 18,
                                                               "name" => "Sarlahi",
                                                               "province_id" => 2
                                                            ],
      [
                                                                  "id" => 19,
                                                                  "name" => "Siraha",
                                                                  "province_id" => 2
                                                               ],
      [
                                                                     "id" => 20,
                                                                     "name" => "Dhanusha",
                                                                     "province_id" => 2
                                                                  ],
      [
                                                                        "id" => 21,
                                                                        "name" => "Saptari",
                                                                        "province_id" => 2
                                                                     ],
      [
                                                                           "id" => 22,
                                                                           "name" => "Mahottari",
                                                                           "province_id" => 2
                                                                        ],
      [
                                                                              "id" => 23,
                                                                              "name" => "Bhaktapur",
                                                                              "province_id" => 3
                                                                           ],
      [
                                                                                 "id" => 24,
                                                                                 "name" => "Chitwan",
                                                                                 "province_id" => 3
                                                                              ],
      [
                                                                                    "id" => 25,
                                                                                    "name" => "Dhading",
                                                                                    "province_id" => 3
                                                                                 ],
      [
                                                                                       "id" => 26,
                                                                                       "name" => "Dolakha",
                                                                                       "province_id" => 3
                                                                                    ],
      [
                                                                                          "id" => 27,
                                                                                          "name" => "Kathmandu",
                                                                                          "province_id" => 3
                                                                                       ],
      [
                                                                                             "id" => 28,
                                                                                             "name" => "Kavrepalanchok",
                                                                                             "province_id" => 3
                                                                                          ],
      [
                                                                                                "id" => 29,
                                                                                                "name" => "Lalitpur",
                                                                                                "province_id" => 3
                                                                                             ],
      [
                                                                                                   "id" => 30,
                                                                                                   "name" => "Makwanpur",
                                                                                                   "province_id" => 3
                                                                                                ],
      [
                                                                                                      "id" => 31,
                                                                                                      "name" => "Nuwakot",
                                                                                                      "province_id" => 3
                                                                                                   ],
      [
                                                                                                         "id" => 32,
                                                                                                         "name" => "Ramechap",
                                                                                                         "province_id" => 3
                                                                                                      ],
      [
                                                                                                            "id" => 33,
                                                                                                            "name" => "Rasuwa",
                                                                                                            "province_id" => 3
                                                                                                         ],
      [
                                                                                                               "id" => 34,
                                                                                                               "name" => "Sindhuli",
                                                                                                               "province_id" => 3
                                                                                                            ],
      [
                                                                                                                  "id" => 35,
                                                                                                                  "name" => "Sindhupalchok",
                                                                                                                  "province_id" => 3
                                                                                                               ],
      [
                                                                                                                     "id" => 36,
                                                                                                                     "name" => "Baglung",
                                                                                                                     "province_id" => 4
                                                                                                                  ],
      [
                                                                                                                        "id" => 37,
                                                                                                                        "name" => "Gorkha",
                                                                                                                        "province_id" => 4
                                                                                                                     ],
      [
                                                                                                                           "id" => 38,
                                                                                                                           "name" => "Kaski",
                                                                                                                           "province_id" => 4
                                                                                                                        ],
      [
                                                                                                                              "id" => 39,
                                                                                                                              "name" => "Lamjung",
                                                                                                                              "province_id" => 4
                                                                                                                           ],
      [
                                                                                                                                 "id" => 40,
                                                                                                                                 "name" => "Manang",
                                                                                                                                 "province_id" => 4
                                                                                                                              ],
      [
                                                                                                                                    "id" => 41,
                                                                                                                                    "name" => "Mustang",
                                                                                                                                    "province_id" => 4
                                                                                                                                 ],
      [
                                                                                                                                       "id" => 42,
                                                                                                                                       "name" => "Myagdi",
                                                                                                                                       "province_id" => 4
                                                                                                                                    ],
      [
                                                                                                                                          "id" => 43,
                                                                                                                                          "name" => "Nawalpur",
                                                                                                                                          "province_id" => 4
                                                                                                                                       ],
      [
                                                                                                                                             "id" => 44,
                                                                                                                                             "name" => "Parwat",
                                                                                                                                             "province_id" => 4
                                                                                                                                          ],
      [
                                                                                                                                                "id" => 45,
                                                                                                                                                "name" => "Syangja",
                                                                                                                                                "province_id" => 4
                                                                                                                                             ],
      [
                                                                                                                                                   "id" => 46,
                                                                                                                                                   "name" => "Tanahun",
                                                                                                                                                   "province_id" => 4
                                                                                                                                                ],
      [
                                                                                                                                                      "id" => 47,
                                                                                                                                                      "name" => "Kapilvastu",
                                                                                                                                                      "province_id" => 5
                                                                                                                                                   ],
      [
                                                                                                                                                         "id" => 48,
                                                                                                                                                         "name" => "Parasi",
                                                                                                                                                         "province_id" => 5
                                                                                                                                                      ],
      [
                                                                                                                                                            "id" => 49,
                                                                                                                                                            "name" => "Rupandehi",
                                                                                                                                                            "province_id" => 5
                                                                                                                                                         ],
      [
                                                                                                                                                               "id" => 50,
                                                                                                                                                               "name" => "Arghakhanchi",
                                                                                                                                                               "province_id" => 5
                                                                                                                                                            ],
      [
                                                                                                                                                                  "id" => 51,
                                                                                                                                                                  "name" => "Gulmi",
                                                                                                                                                                  "province_id" => 5
                                                                                                                                                               ],
      [
                                                                                                                                                                     "id" => 52,
                                                                                                                                                                     "name" => "Palpa",
                                                                                                                                                                     "province_id" => 5
                                                                                                                                                                  ],
      [
                                                                                                                                                                        "id" => 53,
                                                                                                                                                                        "name" => "Dang",
                                                                                                                                                                        "province_id" => 5
                                                                                                                                                                     ],
      [
                                                                                                                                                                           "id" => 54,
                                                                                                                                                                           "name" => "Pyuthan",
                                                                                                                                                                           "province_id" => 5
                                                                                                                                                                        ],
      [
                                                                                                                                                                              "id" => 55,
                                                                                                                                                                              "name" => "Rolpa",
                                                                                                                                                                              "province_id" => 5
                                                                                                                                                                           ],
      [
                                                                                                                                                                                 "id" => 56,
                                                                                                                                                                                 "name" => "Eastern Rukum",
                                                                                                                                                                                 "province_id" => 5
                                                                                                                                                                              ],
      [
                                                                                                                                                                                    "id" => 57,
                                                                                                                                                                                    "name" => "Banke",
                                                                                                                                                                                    "province_id" => 5
                                                                                                                                                                                 ],
      [
                                                                                                                                                                                       "id" => 58,
                                                                                                                                                                                       "name" => "Bardiya",
                                                                                                                                                                                       "province_id" => 5
                                                                                                                                                                                    ],
      [
                                                                                                                                                                                          "id" => 59,
                                                                                                                                                                                          "name" => "Western Rukum",
                                                                                                                                                                                          "province_id" => 6
                                                                                                                                                                                       ],
      [
                                                                                                                                                                                             "id" => 60,
                                                                                                                                                                                             "name" => "Salyan",
                                                                                                                                                                                             "province_id" => 6
                                                                                                                                                                                          ],
      [
                                                                                                                                                                                                "id" => 61,
                                                                                                                                                                                                "name" => "Dolpa",
                                                                                                                                                                                                "province_id" => 6
                                                                                                                                                                                             ],
      [
                                                                                                                                                                                                   "id" => 62,
                                                                                                                                                                                                   "name" => "Humla",
                                                                                                                                                                                                   "province_id" => 6
                                                                                                                                                                                                ],
      [
                                                                                                                                                                                                      "id" => 63,
                                                                                                                                                                                                      "name" => "Jumla",
                                                                                                                                                                                                      "province_id" => 6
                                                                                                                                                                                                   ],
      [
                                                                                                                                                                                                         "id" => 64,
                                                                                                                                                                                                         "name" => "Kalikot",
                                                                                                                                                                                                         "province_id" => 6
                                                                                                                                                                                                      ],
      [
                                                                                                                                                                                                            "id" => 65,
                                                                                                                                                                                                            "name" => "Mugu",
                                                                                                                                                                                                            "province_id" => 6
                                                                                                                                                                                                         ],
      [
                                                                                                                                                                                                               "id" => 66,
                                                                                                                                                                                                               "name" => "Surkhet",
                                                                                                                                                                                                               "province_id" => 6
                                                                                                                                                                                                            ],
      [
                                                                                                                                                                                                                  "id" => 67,
                                                                                                                                                                                                                  "name" => "Dailekh",
                                                                                                                                                                                                                  "province_id" => 6
                                                                                                                                                                                                               ],
      [
                                                                                                                                                                                                                     "id" => 68,
                                                                                                                                                                                                                     "name" => "Jajarkot",
                                                                                                                                                                                                                     "province_id" => 6
                                                                                                                                                                                                                  ],
      [
                                                                                                                                                                                                                        "id" => 69,
                                                                                                                                                                                                                        "name" => "Darchula",
                                                                                                                                                                                                                        "province_id" => 7
                                                                                                                                                                                                                     ],
      [
                                                                                                                                                                                                                           "id" => 70,
                                                                                                                                                                                                                           "name" => "Bajhang",
                                                                                                                                                                                                                           "province_id" => 7
                                                                                                                                                                                                                        ],
      [
                                                                                                                                                                                                                              "id" => 71,
                                                                                                                                                                                                                              "name" => "Bajura",
                                                                                                                                                                                                                              "province_id" => 7
                                                                                                                                                                                                                           ],
      [
                                                                                                                                                                                                                                 "id" => 72,
                                                                                                                                                                                                                                 "name" => "Baitadi",
                                                                                                                                                                                                                                 "province_id" => 7
                                                                                                                                                                                                                              ],
      [
                                                                                                                                                                                                                                    "id" => 73,
                                                                                                                                                                                                                                    "name" => "Doti",
                                                                                                                                                                                                                                    "province_id" => 7
                                                                                                                                                                                                                                 ],
      [
                                                                                                                                                                                                                                       "id" => 74,
                                                                                                                                                                                                                                       "name" => "Acham",
                                                                                                                                                                                                                                       "province_id" => 7
                                                                                                                                                                                                                                    ],
      [
                                                                                                                                                                                                                                          "id" => 75,
                                                                                                                                                                                                                                          "name" => "Dadeldhura",
                                                                                                                                                                                                                                          "province_id" => 7
                                                                                                                                                                                                                                       ],
      [
                                                                                                                                                                                                                                             "id" => 76,
                                                                                                                                                                                                                                             "name" => "Kanchanpur",
                                                                                                                                                                                                                                             "province_id" => 7
                                                                                                                                                                                                                                          ],
      [
                                                                                                                                                                                                                                                "id" => 77,
                                                                                                                                                                                                                                                "name" => "Kailali",
                                                                                                                                                                                                                                                "province_id" => 7
                                                                                                                                                                                                                                             ]
   ];
   foreach($jayParsedAry as $data){

    $dis = new District();

    $dis->id = $data["id"];
    $dis->name = $data["name"];
    $dis->province_id = $data["province_id"];
    $dis->save();
   }
   return "ok";


});