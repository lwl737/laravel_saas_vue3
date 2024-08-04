<?php

declare(strict_types=1);
namespace App\Services\Saas;
use App\Utils\Permissions\{CheckApi,Menu,MenuButtons};
use App\Models\Mysql\Orm\Dev\{
    SaasMenuAuth as MenuAuthModel,
    SaasMenu as MenuModel,
    SaasMenuAuthApi as MenuAuthApiModel,
    SaasMenuAuthButtons as MenuAuthButtonsModel,
};
use App\Models\Mysql\Orm\Saas\{
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
