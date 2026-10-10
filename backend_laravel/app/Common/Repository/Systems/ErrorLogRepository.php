<?php

declare(strict_types=1);

namespace App\Common\Repository\Systems;
use App\Common\Dao\Mongo\ErrorLogDao as ErrorLogDao;
use \Illuminate\Http\Request;

class ErrorLogRepository extends BaseRepository{

    public function __construct(
        protected ErrorLogDao $dao
    ) {}


    public function insertOneAsync(array $data , Request $request){
        return $this->dao->createDate( time() , 'Ymd')->insertOneAsync($data , $request);
    }



    public function list(array $params){
        $this->dao = $this->dao->createDate(strtotime($params['ymd']) , 'Ymd');
        return [
            'total' => $this->dao->total(),
            'list' => $this->dao->list($params),
        ];
    }


    public function getDate(){
        return $this->dao->getTableName();
    }

}
