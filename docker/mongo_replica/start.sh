#!/bin/sh
# sed -i 's/\r//' ./wait-for
# /mongo/wait-for lsv_mongo_test1:27017 --

chmod 400 /mongo/key/keyFile.key

mongod  -f ./mongodb.conf

# 先无权限启动
mongosh --tlsCertificateKeyFile=/mongo/key/keyFile.key /mongo/mongo.init.js

echo "start success"

# # 权限方式打开

/bin/bash


