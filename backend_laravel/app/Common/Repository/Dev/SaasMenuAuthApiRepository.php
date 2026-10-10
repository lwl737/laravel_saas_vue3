<?php
declare(strict_types=1);

namespace App\Common\Repository\Dev;
use App\Common\Dao\Mysql\Dev\SaasMenuAuthApiDao\Orm as SaasMenuAuthApiDao;
class SaasMenuAuthApiRepository extends BaseRepository{
    public function __construct(
        protected SaasMenuAuthApiDao $dao
    ) {}

    public function checkApi(array $params,?int $api_id = null){
        $res = $this->dao->checkApi($params , $api_id);
        if(!$res) return null;
        $res->api_name = $res->auth_name ? $res->auth_name : $res->api_name;
        return $res;
    }


    public function list(array $params){
        $dao = $this->dao->listConfig($params);
        return [
            'total' => $dao->query()->count(),
            'list' => $dao->list($params)->query()->get(),
        ];
    }


}
