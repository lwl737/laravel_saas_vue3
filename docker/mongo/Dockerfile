FROM  alpine:3.14
LABEL MAINTAINER="linweile<674969619@qq.com>"

ARG DEBIAN_FRONTEND=noninteractive
# 取消命令行交互

USER root
# 切换用户


USER root
# 切换用户
RUN sed -i 's/dl-cdn.alpinelinux.org/mirrors.tuna.tsinghua.edu.cn/g' /etc/apk/repositories

RUN apk update


#压缩包资源
ADD  ../../zip/mongodb-6.0.0.tgz  /
ADD  ../../zip/mongosh-1.10.1.tgz  /



RUN  mkdir /usr/local/mongod/

RUN  mkdir /usr/local/mongosh/

RUN  cp -r /mongodb-linux-x86_64-ubuntu1804-6.0.7/* /usr/local/mongod/

RUN  cp -r /mongosh-1.10.1-linux-x64/* /usr/local/mongosh/


ENV MONGOSH_PATH=/usr/local/mongosh/bin MONGOD_PATH=/usr/local/mongod/bin
ENV PATH=$PATH:$MONGOSH_PATH:$MONGOD_PATH

# CMD  /bin/bash




