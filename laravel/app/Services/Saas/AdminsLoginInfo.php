<?php

declare(strict_types=1);

namespace App\Services\Saas;

use App\Services\JWT\Server as JWTServer;
use App\Helpers\Enums\HeaderRes;
use App\Helpers\Func;
use App\Models\Mysql\Orm\Saas\Admins;
use App\Models\Mysql\Orm\Saas\Role;

class AdminsLoginInfo
{
    public   int    $admins_id;
    private  int    $organi_id = 0;
    private  string $organi_name = "";
    private  array|null $api_auth = null;
    private  array|bool $organi_link = false;

    public function __construct(
        private  Admins|null $admins_model = null,
        private  Role|null   $role_model = null,
        private  array   $jwt = [],
        private  bool    $is_over = false,
        private  int     $tenant_id = 0
    ) {
        $this->admins_id = $jwt["admins_id"] ?? 0;
    }

    public function getPrimaryKey()
    {
        return $this->admins_id;
    }

    public function getTenantId():int
    {
        return  $this->tenant_id;
    }

    public function getInfo(array|null $field = null): array
    {
        if (!$field || empty($field)) return $this->admins_model->toArray();
        else {

            $nowKeys = array_keys($this->admins_model->toArray());

            $newArr = [];
            //不在这个key里面的拿出来
            foreach ($field as $val) if (!in_array($val, $nowKeys)) $newArr[] = $val;

            if (!empty($newArr)) {
                $this->admins_model = $this->admins_model
                    ->where($this->admins_model->getPrimaryKey(), $this->admins_id)
                    ->first(array_merge($nowKeys, $newArr));
            }

            return Func::formExtractData($this->admins_model->toArray(), $field);
        }
    }

    public function setJwt(array $jwt)
    {
        $this->jwt = $jwt;
        $this->admins_id = $jwt["admins_id"] ?? 0;
        return $this;
    }


    public function setIsOver(bool $is_over)
    {
        $this->is_over = $is_over;
        return $this;
    }
    public function getJwt(): array
    {
        return $this->jwt;
    }

    public function setAuthApi(array|null $api_auth = null)
    {
        $this->api_auth = $api_auth;
        return $this;
    }
    public function setAdminsModel(Admins|null $adminsModel)
    {
        $this->admins_model = $adminsModel;
        return $this;
    }
    public function setRoleModel(Role|null $roleModel)
    {
        $this->role_model = $roleModel;
        return $this;
    }

    public function getAuthApi()
    {
        return $this->api_auth;
    }

    public function setLoginOrganiId(int $organi_id)
    {
        $this->organi_id  = $organi_id;
        return $this;
    }

    public function setLoginOrganiLink(array|true $organi_ids)
    {
        $this->organi_link  = $organi_ids;
        return $this;
    }

    public function getLoginOrganiLink()
    {
        return $this->organi_link;
    }

    public function setLoginOrganiName(string $organi_name)
    {
        $this->organi_name  = $organi_name;
        return $this;
    }

    public function getLoginOrganiId()
    {
        return $this->organi_id;
    }
    public function getLoginOrgani()
    {
        return [
            "organi_name" =>  $this->organi_name,
            "organi_id" =>  $this->organi_id,
            "organi_link" =>  $this->organi_link,
        ];
    }
    public function getIsOver(): bool
    {
        return $this->is_over;
    }

    public function resetToken()
    {
        return $this->createToken($this->getJwt());
    }

    public static function createToken(array $params)
    {
        return [
            //返回登录token
            HeaderRes::SAAS_LOGIN_TOKEN->name => JWTServer::saasLogin()->create(
                [
                    'admins_id' => $params["admins_id"],
                    'password' => $params["password"],
                    'username' =>   $params["username"],
                    'tenant_id' => $params["tenant_id"]
                ]
            )
        ];
    }
}
