#!/bin/sh
# 获取当前脚本文件的相对路径
script_relative_path=$0

working_directory=$(pwd);

#没有vendor脚本就安装第三方包
composer_file="$working_directory/vendor/autoload.php"

chmod -R 777 ./storage/
#赋予www写入权限

if [ ! -f $composer_file ]; then
#    运行 composer install
    php  "$working_directory/composer.phar" install;
fi

env_file="$working_directory/.env"

if [ ! -f $env_file ]; then
  cp "$working_directory/.env.example" $env_file;
  wait4xText=$(php artisan master_db --timeout=100s);
  # 初始化时 等待能链接就执行迁移
  wait4xText="$wait4xText -- php artisan migrate --seed --path=database/migrations/*";
  $wait4xText;
fi



node_modules_directory="$working_directory/node_modules"

if [ ! -d $node_modules_directory ]; then
    pnpm config set registry http://registry.npm.taobao.org
    # 运行 pnpm install
    pnpm  install --prefix $working_directory
fi

# 执行其他命令
supervisord -c ./supervisord.conf && /bin/sh
