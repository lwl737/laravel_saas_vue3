<?php

declare(strict_types=1);
namespace App\Service\Role\Modules;
use App\Library\BaseOrm;


class Role{

    public function __construct(
        public  BaseOrm $roleModel
    )
    { }


    public function add($params)
    {
        $qeury = $this->roleModel::static();

        $qeury->role_name = $params['role_name'];
        $qeury->role_json = [];
        $qeury->role_sort = $params['role_sort'];
        $qeury->role_describe = $params['role_describe'];
        $qeury->permission_type = $params['permission_type'];
        $qeury->colleges = $params['colleges'];
        $qeury->save();
    }

    public function list(array $params)
    {

        $query = clone $this->roleModel;

        if (isset($params['role_name']))  $query =  $query->where('role_name',$params['role_name']);
        if (isset($params['permission_type']))  $query =  $query->where('permission_type',$params['permission_type']);
        $list =  $query->orderBy('role_sort','desc')->orderBy('role_id','desc')->offset( ($params['pageNum'] - 1) * $params['pageSize'])->limit($params['pageSize'])->get();
        foreach($list as $item){
            if($item->colleges)
            $item->colleges = array_map('intval',explode(',',$item->colleges));
        }

        return [
            "total" => $query->count(),
            "list" => $list,
            "pageNum" => (int) $params['pageNum'],
            "pageSize" => (int) $params['pageSize'],
        ];
    }

    public function del($params)
    {
        $this->roleModel->destroy($params['ids']);
    }

    public function edit($params)
    {
        $this->roleModel->where('role_id',$params['role_id'])->update([
            "role_name" => $params['role_name'],
            "role_sort" => $params['role_sort'],
            "role_describe" => $params['role_describe'],
            "permission_type" => $params['permission_type'],
            "colleges" => $params['colleges'],
        ]);
    }

    public function set($params)
    {

        $this->roleModel->where('role_id', $params['role_id'])->update([
            "role_json" => serialize( $params['role_json'] ),
        ]);
    }

    public function all()
    {

        return ['list' => $this->roleModel->orderBy('role_sort','desc')->orderBy('role_id','desc')->get(['role_name', 'role_id'])->toArray()];

    }

}
