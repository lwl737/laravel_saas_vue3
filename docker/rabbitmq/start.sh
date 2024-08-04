#!/bin/sh
chmod -R 777 /var/log/rabbitmq
# ui管理界面
rabbitmq-plugins enable rabbitmq_management
# 启动mq队列
rabbitmq-server start
