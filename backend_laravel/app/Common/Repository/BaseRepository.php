<?php

declare(strict_types=1);

namespace App\Common\Repository;

use App\Helpers\Func;
use App\Helpers\Output\Json\Error;
use Closure;
use Illuminate\Support\Facades\DB;
use App\Http\Systems\CustomRouter;

abstract class BaseRepository
{

    protected final function getDao(Object $class , bool $returnSelf = false )
    {
        if (property_exists($class, "dao")) {
            return $returnSelf ?  $class : $class->dao;
        } else {
            Func::throwHttpCustom(Error::DAO_NOT_DEFINED);
        }
    }

    public function __call($name, $arguments)
    {
        return call_user_func_array([$this->getDao($this), $name], $arguments);
    }

    public final static function transaction(Closure $func)
    {
        DB::transaction(fn() => $func());
    }

    /**
     * 关联普通管理员上下级关系
     * @return $this
     */
    public final function organiScope()
    {
        /** @var CustomRouter **/
        $customRouter = app(CustomRouter::class);
        $class = $this->getDao($this , true);
        $class->dao = $class->dao->listOrganiConfig($customRouter->getParams(["insert_nick_name", "insert_organi_name", "insert_organi_id"]));
        return $class;
    }




    /**
     * 关联超级管理员
     * @return $this
     */
    public function superAdmin()
    {
        $class = $this->getDao($this , true);
        $class->dao = $class->dao->superAdmin();
        return $class;
    }
}
