<?php

declare(strict_types=1);

namespace App\Http\Services\Admin\Permissions\Dev;

use App\Services\Admin\Permissions\BaseServiceProvider;

use App\Common\Models\Mysql\Orm\Dev\{
    MenuAuth as MenuAuthModel,
    Menu as MenuModel,
    MenuAuthApi as MenuAuthApiModel,
    MenuAuthButtons as MenuAuthButtonsModel,
    Role as RoleModel,
};


class ServiceProvider extends BaseServiceProvider
{
    protected function getRoleModel(): RoleModel
    {
        return app()->make(RoleModel::class);
    }
    protected function getMenuModel(): MenuModel
    {
        return app()->make(MenuModel::class);
    }

    protected function getMenuAuthModel(): MenuAuthModel
    {
        return app()->make(MenuAuthModel::class);
    }

    protected function getMenuAuthApiModel(): MenuAuthApiModel
    {
        return app()->make(MenuAuthApiModel::class);
    }

    protected function getMenuAuthButtonsModel(): MenuAuthButtonsModel
    {
        return app()->make(MenuAuthButtonsModel::class);
    }

}
