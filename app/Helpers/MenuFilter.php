<?php


namespace App\Helpers;


use Illuminate\Support\Facades\Auth;
use JeroenNoten\LaravelAdminLte\Menu\Filters\FilterInterface;

class MenuFilter implements FilterInterface
{
    public function transform($item)
    {
        //if role left empty or role is of the logged user

        if(UserTypeHelper::check() != ($item['is_admin'] ?? "") && ($item['is_admin'] ??"All")!= "All" ){
            $item['restricted'] = true;
        }

        return $item;
    }
}