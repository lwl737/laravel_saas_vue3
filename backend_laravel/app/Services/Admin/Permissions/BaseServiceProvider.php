<?php

declare(strict_types=1);
namespace App\Services\Admin\Permissions;
use App\Common\Models\Mysql\Orm\BaseOrm;


abstract class BaseServiceProvider{


    abstract protected function getRoleModel():BaseOrm;
    abstract protected function getMenuModel():BaseOrm;
    abstract protected function getMenuAuthModel():BaseOrm;
    abstract protected function getMenuAuthApiModel():BaseOrm;
    abstract protected function getMenuAuthButtonsModel():BaseOrm;


    /**
     *  检查api
     *  @return CheckApi
     *  **/
    public final function checkApi():CheckApi{
        return new CheckApi(
            $this->getRoleModel(),
            $this->getMenuModel() ,
            $this->getMenuAuthModel(),
            $this->getMenuAuthApiModel()
        );
    }

    /**
     *  获取栏目
     *  @return Menu
     *  **/
    public final function menu():Menu{
        return new Menu(
            $this->getMenuModel()
        );
    }


    /**
     * 获取栏目按钮权限
     * @return MenuButtons
     */
    public  final function menuButtons():MenuButtons{
        return new MenuButtons(
            $this->getRoleModel(),
            $this->getMenuModel() ,
            $this->getMenuAuthModel(),
            $this->getMenuAuthButtonsModel()
        );
    }

}
