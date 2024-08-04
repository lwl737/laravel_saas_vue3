<?php

declare(strict_types=1);
namespace App\Services\Dev;
use App\Utils\Permissions\{CheckApi,Menu,MenuButtons};
use App\Models\Mysql\Orm\Dev\{
    MenuAuth as MenuAuthModel,
    Menu as MenuModel,
    MenuAuthApi as MenuAuthApiModel,
    MenuAuthButtons as MenuAuthButtonsModel,
    Role as RoleModel,
};
class Permissions{

     public static function checkApi():CheckApi{
        return new CheckApi(
            new RoleModel,
            new MenuModel ,
            new MenuAuthModel,
            new MenuAuthApiModel
        );
    }


    public static function menu():Menu{
        return new Menu(
            new MenuModel
        );
    }

    public static function menuButtons():MenuButtons{
        return new MenuButtons(
            new RoleModel,
            new MenuModel ,
            new MenuAuthModel,
            new MenuAuthButtonsModel
        );
    }

}
