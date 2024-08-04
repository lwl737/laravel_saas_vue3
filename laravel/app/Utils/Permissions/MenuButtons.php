<?php

declare(strict_types=1);

namespace App\Utils\Permissions;

use App\Helpers\Func;

use App\Models\Mysql\Orm\BaseOrm;


class MenuButtons
{

    /**
     * Undocumented function
     *
     * @param BaseOrm $roleModel            权限orm
     * @param BaseOrm $menuModel            栏目orm
     * @param BaseOrm $menuAuthModel        栏目权限orm
     * @param BaseOrm $menuAuthButtonsModel
     **/
    public function __construct(
       public readonly BaseOrm  $roleModel,
       public readonly BaseOrm  $menuModel,
       public readonly BaseOrm  $menuAuthModel,
       public readonly BaseOrm  $menuAuthButtonsModel,
    )
    { }

    /**
     * @description:  获取用户权限
     * @param  int    $role_id   权限id
     * @return {*}
     */
    public  function getMenuButtons(int $role_id)
    {
        $result = [
            'menu'  => [],            //栏目
            'buttons'  => [],         //按钮权限
            // 'auth_pages'   => [],     //权限页面
        ];

        [$menu, $auth] = $this->getRoleJson($role_id);

        /*  没有栏目显示并且不是超级管理员 空栏目  */
        if ($role_id > 0 && empty($menu)) return $result;

        /* 根据菜单menu_id生成树 */
        [$tree, $tmp] =  Func::createTree(
            //空栏目时全查
            $this->menuModel->where(function ($query) use ($menu,$auth) {
                if(!empty($menu)) $query->whereIn('menu_id', $menu);
                if(!empty($auth)) $query->orWhereIn('auth_id', $auth);
            })->orderBy('menu_sort', 'desc')->orderBy('menu_id', 'desc')->get(
                [
                    'menu_id',
                    'menu_pid',
                    'path',
                    'name',
                    'redirect',
                    'component',
                    'menu_sort',
                    'icon',
                    'title',
                    'isLink',
                    'isHide',
                    'isFull',
                    'isAffix',
                    'isKeepAlive'
                ]
            )->toArray(),
            'menu_id',
            'menu_pid',
            'children',
            fn($item) =>[
                    "menu_id" => $item["menu_id"],
                    "menu_pid" => $item['menu_pid'] === null ? 0 : $item['menu_pid'],
                    "component" => $item['component'], //组件
                    "name" => $item['name'], //路由名称
                    "path" => $item['path'],   //路径
                    "redirect" => $item['redirect'],  //重定向
                    "meta" => [
                        "icon" => $item['icon'] ,  //栏目icon
                        "title" =>   $item['title'] ,  //栏目名称
                        "isAffix" =>   $item['isAffix']  == 1 ? true : false,
                        "isFull" =>   $item['isFull']  == 1 ? true : false,
                        "isHide" =>   $item['isHide']  == 1 ? true : false,
                        "isKeepAlive" =>   $item['isKeepAlive']  === 1 ? true : false,
                        "isLink" =>   $item['isLink']  == 1 ? true : false,
                    ],
               ]
            ,
            true
        );
        /* */

        /* 栏目树  */
        $result['menu'] = $tree;
        /* */


        /* 没有权限并且不是超级管理员  */
        $auths = $role_id > 0 && empty($auth) ? [] : $this->auth($tmp, $auth);
        /* */


        /* 有按钮权限才进行组装数据 */
        if (!empty($auths)) {
            /* 生成path => [buttons] 按钮权限数据 */
            $result['buttons'] = $this->buttons($auths, $tmp);
            /* */
        }

        return  $result;
    }


    private  function auth($menu, $auth): array
    {
        if(empty($menu)) return [];
        else{

            $dao = $this->menuAuthModel->whereIn('menu_id', array_keys($menu));

            if(!empty($auth)) $dao = $dao->whereIn('auth_id', array_values($auth));

            return $dao->orderBy('auth_sort', 'desc')->orderBy('auth_id','desc')->get()->toArray();
        }
    }


    private  function buttons($auth, $tmp): array
    {
        $buttons = [];
        /* 把auth_id变成key 顺便找到该权限的路径  */
        $auth = Func::keyArray($auth, 'auth_id', function ($item) use ($tmp) {
            $item['path'] = $tmp[$item['menu_id']]['path'];
            return $item;
        });





        if (!empty($auth)) {
            foreach ( $this->menuAuthButtonsModel->where(function ($query) use ($auth) {
                foreach ($auth as $val) $query->orWhere('auth_id', $val['auth_id']);
            })->get() as $val) {
                $buttons[$auth[$val->auth_id]['path']] ??  $buttons[$auth[$val->auth_id]['path']] = [];
                $buttons[$auth[$val->auth_id]['path']][] = $val->buttons;
            }
        }
        return $buttons;
    }

    private  function getRoleJson(int $role_id): array
    {
        $menu_id = [];
        $auth_id = [];
        if ($role_id > 0) {
           $role_json = $this->roleModel->where('role_id',$role_id)->first(['role_json'])?->role_json;
           if( $role_json){
            foreach ($role_json as $key => $val) {
                $menu_id[] = $key;
                $auth_id = array_merge($auth_id, $val);
            }
           }
        }
        return [$menu_id, $auth_id];
    }



}
