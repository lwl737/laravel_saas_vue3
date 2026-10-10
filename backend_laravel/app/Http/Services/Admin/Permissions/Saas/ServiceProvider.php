<?php

declare(strict_types=1);

namespace App\Http\Services\Admin\Permissions\Saas;

use App\Services\Admin\Permissions\BaseServiceProvider;


use App\Common\Models\Mysql\Orm\Dev\{
    SaasMenuAuth as MenuAuthModel,
    SaasMenu as MenuModel,
    SaasMenuAuthApi as MenuAuthApiModel,
    SaasMenuAuthButtons as MenuAuthButtonsModel,
};

use App\Common\Models\Mysql\Orm\Saas\Role as RoleModel;


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
