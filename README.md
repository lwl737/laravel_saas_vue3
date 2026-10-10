#### 在线预览 👀

* SAAS后台管理 [http://106.55.196.101:886]()
* 租户1 [http://106.55.196.101:887/#/1]()

* 租户2 [http://106.55.196.101:887/#/2]()

### 复制和启动

#### 开发环境

```
cp ./docker-compose-dev.yml ./docker-compose.yml  #复制测试环境docker-compose.yml文件
cp ./laravel/supervisord-dev.conf ./laravel/supervisord.conf
docker-compose up -d
```

#### 线上环境

```
cp ./docker-compose-pro.yml ./docker-compose.yml  #复制测试环境docker-compose.yml文件
cp ./laravel/supervisord-pro.conf ./laravel/supervisord.conf
docker-compose up -d
```

### 域名

#### SAAS后台管理

[http://localhost:886]()

#### 租户后台

1. 在 [http://localhost:886/#/saas/tenant/index]() 创建租户
2. 在 [http://localhost:886/#/saas/super_admin/index]() 创建初始账号
3. [http://localhost:887/#/1/home/index]() 进入租户后台

   租户1 : [http://localhost:887/#/1/home/index]()

   租户2 : [http://localhost:887/#/2/home/index]()

   **......**

   租户xxx : [http://localhost:887/#/xxx/home/index]()
