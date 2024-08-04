#!/bin/sh
chmod 400 /mongo/key/keyFile.key

mongod  -f ./mongodb.conf

mongosh /mongo/mongo.user.js

# 执行js脚本 添加管理员账号
mongod -f ./mongodb.conf --shutdown

mongod  --auth --keyFile=/mongo/key/keyFile.key --replSet=cmdbrs  -f ./mongodb.conf

# 先无权限启动
mongosh -u root -p 0epbavnfpCR750t3Ms4Tw  /mongo/mongo.init.js


echo "start success"

# 权限方式打开

/bin/bash
