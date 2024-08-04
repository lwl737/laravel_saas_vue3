<?php

declare(strict_types=1);
namespace App\Helpers\Queue\Modules;
use App\Helpers\Queue\QueueInterface;
use App\Helpers\Queue\Run;
use App\Jobs\{
    MongoIndexReset,
    OssDelFile,
    MongoAsync,
    DevAdminOperation,
    SaasAdminOperation,
    UpdateOrgani,
    TenantCreate
};
enum Rabbitmq implements QueueInterface{

    case OSS_DEL_FILE ;
    case MONGO_INDEX ;
    case SYSTEM_ERROR ;
    case MONGO_ASYNC ;
    case DEV_ADMIN_OPERATION;
    case SAAS_ADMIN_OPERATION;
    case UPDATE_ORGANI ;
    case TENANT_CREATE ;
    use Run;

    public function job():string{

         return match ($this) {
            Rabbitmq::MONGO_INDEX =>  MongoIndexReset::class,
            Rabbitmq::OSS_DEL_FILE =>  OssDelFile::class,
            Rabbitmq::SYSTEM_ERROR =>  MongoAsync::class ,
            Rabbitmq::MONGO_ASYNC =>   MongoAsync::class,
            Rabbitmq::DEV_ADMIN_OPERATION =>   DevAdminOperation::class,
            Rabbitmq::UPDATE_ORGANI =>   UpdateOrgani::class,
            Rabbitmq::TENANT_CREATE =>   TenantCreate::class,
            Rabbitmq::SAAS_ADMIN_OPERATION =>   SaasAdminOperation::class,
        };
    }


    public function onConnection():string{
        return "rabbitmq";
    }


    public function onQueue():string{
        return match ($this) {
            Rabbitmq::MONGO_INDEX => 'mongo_index',
            Rabbitmq::OSS_DEL_FILE => 'oss_del_file',
            Rabbitmq::SYSTEM_ERROR => 'system_error',
            Rabbitmq::MONGO_ASYNC => 'mongo_async',
            Rabbitmq::DEV_ADMIN_OPERATION => 'dev_admin_operation',
            Rabbitmq::UPDATE_ORGANI => 'update_organi',
            Rabbitmq::TENANT_CREATE => 'tenant_create',
            Rabbitmq::SAAS_ADMIN_OPERATION => 'saas_admin_operation',
        };
    }


    public function channel(): string
    {
        return match ($this) {
            Rabbitmq::MONGO_INDEX => 'mongo_index',
            Rabbitmq::OSS_DEL_FILE => 'oss_del_file',
            Rabbitmq::SYSTEM_ERROR => 'system_error',
            Rabbitmq::MONGO_ASYNC => 'mongo_async',
            Rabbitmq::DEV_ADMIN_OPERATION => 'dev_admin_operation',
            Rabbitmq::UPDATE_ORGANI => 'update_organi',
            Rabbitmq::TENANT_CREATE => 'tenant_create',
            Rabbitmq::SAAS_ADMIN_OPERATION => 'saas_admin_operation',
            default => "rabbitmq_error"
        };
    }
}
