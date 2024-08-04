<?php

declare(strict_types=1);

namespace App\Http\Controllers\Dev;

use App\Dao\Mysql\Dev\OrganiDao\Orm as OrganiDao;
use App\Helpers\Output\Json\Success;
use App\Helpers\Output\Json\Dev;
use App\Dao\Mysql\Dev\OrganiRelationDao\Orm as OrganiRelationDao;

class Organi extends BaseController
{

    public function add()
    {

        $params = $this->params;

        if ($params['organi_pid'] === 0) {

            $organi_id  =  $this->getOrganiId();

            $params['organi_pid'] = $organi_id === -1 ? null  :  $organi_id;

        } else if( !OrganiRelationDao::static()->checkIsEnable( $this->getAdminsId() ,  $params['organi_pid'] , $this->getAdminsLoginInfo()->getInfo(["role_id"])["role_id"])){
            //判断是否pid合法
            return $this->outputEnum( Dev::ORGANI_NOT_EXIST);
        }

        //祖先link
        [
            "max_level" => $max_level,
            "ancestor_link" => $ancestor_link,
            "level_link" => $level_link,
        ] = OrganiRelationDao::static()->getAncestorLink(
            $params['organi_pid']
        );

        // var_dump($level_link);

        $ancestor_link = $ancestor_link !== '' ?  explode(",", $ancestor_link) : [];
        $level_link = $level_link !== '' ?  explode(",", $level_link) : [];

        OrganiDao::static()->add($params, $ancestor_link,  (int)$max_level , $level_link);

        return $this->outputEnum(Success::ADD_SUCCESS);
    }

    public function edit()
    {

        if( !OrganiRelationDao::static()->checkIsEnable( $this->getAdminsId() ,  $this->params['organi_id'] , $this->getAdminsLoginInfo()->getInfo(["role_id"])["role_id"])){
            //判断是否组织id合法
            return $this->outputEnum( Dev::ORGANI_NOT_EXIST);
        }

        OrganiDao::static()->edit($this->params);

        return $this->outputEnum(Success::EDIT_SUCCESS);
    }

    public function del()
    {
        OrganiDao::static()->del((int)$this->params['organi_id']);

        return $this->outputEnum(Success::DEL_SUCCESS);
    }


    public function all()
    {
        return $this->output([
            "tree" =>  OrganiDao::static()->tree(
                $this->getOrganiId() === -1 ? null : $this->getOrganiId(),
                false
            ),
            "organi_name" => $this->getOrgani("organi_name")
        ], Success::SEL_SUCCESS);
    }
}
