#!/bin/bash
#第一次启动运行的脚本
cd /php  && php composer.phar config -g repo.packagist composer https://mirrors.aliyun.com/composer/
#设置国内镜像源
cd /php  && php composer.phar update
# php版本过高 忽略版本添加 --ignore-platform-reqs
cd /php  && php composer.phar install
# php版本过高 忽略版本添加 --ignore-platform-reqs
# echo 安装成功，并启动
# cd /php  && php swoole_server/Ws.php





