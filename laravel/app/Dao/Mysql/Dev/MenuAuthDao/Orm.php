<?php

declare(strict_types=1);
namespace App\Dao\Mysql\Dev\MenuAuthDao;
use App\Dao\Mysql\Dev\BaseDev;
use App\Models\Mysql\Orm\Dev\MenuAuth;
class Orm extends BaseDev{

    public function __construct()
    {
        $this->query = new MenuAuth;
    }


    public function listConfig($params)
    {
        $this->query = $this->query->where('menu_id',$params["menu_id"]);
        return $this;
    }


    public function listOrderBy(array $params){
        $this->query =  $this->query
        ->offset(($params["pageNum"] - 1) * $params["pageSize"])->limit($params["pageSize"])
        ->orderBy('auth_sort','desc')
        ->orderBy('auth_id','desc');
        return  $this;
    }


    public function withAll()
    {

        $this->query = $this->query->with(['buttons','api','pages']);

        return $this;
    }

    public function del(array $params)
    {

       return  $this->query->where('auth_id',$params['auth_id'])->delete();
    }

    public function add(array $params)
    {

        $this->query->auth_name = $params['auth_name'];

        $this->query->auth_sort = $params['auth_sort'];

        $this->query->menu_id = $params['menu_id'];

        $this->query->save();
    }
    public function edit(array $params)
    {
        return $this->query->where('auth_id',$params['auth_id'])->update([
            'auth_name' =>  $params['auth_name'],
            'auth_sort' =>  $params['auth_sort']
        ]);
    }

}
