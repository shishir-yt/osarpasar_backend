<?php


namespace App\Helpers;


class UserTypeHelper
{
   static function check(){

     return auth()->user()->is_admin;
    }
}