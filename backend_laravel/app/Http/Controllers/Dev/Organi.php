<?php

declare(strict_types=1);

namespace App\Http\Controllers\Dev;

use App\Dao\Mysql\Dev\OrganiDao\Orm as OrganiDao;
use App\Helpers\Output\Json\Success;
use App\Helpers\Output\Json\Dev;
// use App\Dao\Mysql\Dev\OrganiRelationDao\Orm as OrganiRelationDao;
use App\Common\Repository\Dev\OrganiRepository;
use App\Common\Repository\Dev\OrganiRelationRepository;
use App\Common\Repository\Dev\OrganiAdminsBindRepository;


class Organi extends BaseController
{

    public function add(
        OrganiRelationRepository $organiRelationRepository,
        OrganiRepository $organiRepository
    ) {

        $params = $this->params;

        if ($params['organi_pid'] === 0) {
            $organi_id  =  $this->getOrganiId();
            $params['organi_pid'] = $organi_id === -1 ? null  :  $organi_id;
        } else if (!$organiRelationRepository->checkIsEnable($this->getAdminsId(),  (int)$params['organi_pid'], $this->getAdminsLoginInfo()->getInfo(["role_id"])["role_id"])) {
            //判断是否pid合法
            return $this->outputEnum(Dev::ORGANI_NOT_EXIST);
        }

        //祖先link
        [
            "max_level" => $max_level,
            "ancestor_link" => $ancestor_link,
            "level_link" => $level_link,
        ] = $organiRelationRepository->getAncestorLink(
            $params['organi_pid']
        );

        $ancestor_link = $ancestor_link !== '' ?  explode(",", $ancestor_link) : [];
        $level_link = $level_link !== '' ?  explode(",", $level_link) : [];

        $organiRepository->add($params, $ancestor_link,  (int)$max_level, $level_link);

        return $this->outputEnum(Success::ADD_SUCCESS);
    }

    public function edit(
        int $organi_id,
        OrganiRelationRepository $organiRelationRepository,
        OrganiRepository $organiRepository
    ) {

        if (!$organiRelationRepository->checkIsEnable($this->getAdminsId(),  (int)$organi_id, $this->getAdminsLoginInfo()->getInfo(["role_id"])["role_id"])) {
            //判断是否组织id合法
            return $this->outputEnum(Dev::ORGANI_NOT_EXIST);
        }

        $organiRepository->edit($organi_id, $this->params);

        return $this->outputEnum(Success::EDIT_SUCCESS);
    }

    public function del(
        OrganiRelationRepository $organiRelationRepository,
        OrganiRepository $organiRepository
    ) {

        if (!$organiRelationRepository->checkIsEnable($this->getAdminsId(),  (int)$this->params['organi_id'], $this->getAdminsLoginInfo()->getInfo(["role_id"])["role_id"])) {
            //判断是否组织id合法
            return $this->outputEnum(Dev::ORGANI_NOT_EXIST);
        }

        $organiRepository->del((int)$this->params['organi_id']);

        return $this->outputEnum(Success::DEL_SUCCESS);
    }


    public function all(
        OrganiRepository $organiRepository
    ) {

        return $this->output([
            "tree" => $organiRepository->tree(
                $this->getOrganiId() === -1 ? null : $this->getOrganiId(),
                false
            ),
            "organi_name" => $this->getOrgani("organi_name")
        ], Success::SEL_SUCCESS);
    }

    public function option(
        OrganiRepository $organiRepository
    ) {
        $organi_id = $this->getOrganiId();
        return $this->output([
            "tree" =>  $organiRepository->tree($organi_id  == -1 ? null : $organi_id, false)
        ], Success::SEL_SUCCESS);
    }

    public function homeOption(
        OrganiRepository $organiRepository,
        OrganiAdminsBindRepository $organiAdminsBindRepository,
    ) {
        return $this->output([
            "tree" =>  $organiRepository->tree(
                $this->getAdminsLoginInfo()->getInfo(['role_id'])["role_id"] > 0 ?
                    $organiAdminsBindRepository->findAdminsOrganiIds($this->getAdminsId())
                    : null
            )
        ], Success::SEL_SUCCESS);
    }
}
