<?php

declare(strict_types=1);

namespace App\Models\Mysql\Scopes;

use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class DevOrganiLinkScope implements Scope
{

    public function __construct(
        public  readonly  array|true  $organi_link,
        public  readonly  int  $admins_id,
        public  readonly  int  $organi_id,
        public  readonly  string $insert_admins_id_key,
        public  readonly  string $insert_organi_id_key
    )
    { }

    /**
     * 把约束加到 Eloquent 查询构造中。
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param \Illuminate\Database\Eloquent\Model $model
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        if(is_array($this->organi_link) && !empty($this->organi_link)){
            $builder->where(function($query) use ($model){
                $query->where(function($query) use ($model){
                    foreach($this->organi_link as $val) $query->orWhere($model->tableName.".".$this->insert_organi_id_key,$val);
                });
            })->where( function($query) use ($model){
                $query->orWhere($model->tableName.".".$this->insert_organi_id_key , "<>" , $this->organi_id)
                ->orWhere($model->tableName.".".$this->insert_admins_id_key , $this->admins_id);
            });
        }
    }
}
