FROM  ubuntu:18.04
LABEL MAINTAINER="linweile<674969619@qq.com>"

ARG DEBIAN_FRONTEND=noninteractive
# 取消命令行交互

USER root
# 切换用户


# -----------
RUN  sed -i s@/archive.ubuntu.com/@/mirrors.aliyun.com/@g /etc/apt/sources.list
RUN  sed -i s@/security.ubuntu.com/@/mirrors.aliyun.com/@g /etc/apt/sources.list
RUN  apt-get clean
# 设置国内镜像源 apt-get 加速

RUN  apt-get update
RUN  apt-get install libcurl3 -y
RUN  apt-get install libcurl4 -y
RUN  apt-get install php-curl -y

#压缩包资源
ADD  ../../volumes/mongodb-6.0.0.tgz  /
ADD  ../../volumes/mongosh-1.10.1.tgz  /



RUN  mkdir /usr/local/mongod/

RUN  mkdir /usr/local/mongosh/

RUN  cp -r /mongodb-linux-x86_64-ubuntu1804-6.0.7/* /usr/local/mongod/

RUN  cp -r /mongosh-1.10.1-linux-x64/* /usr/local/mongosh/


ENV MONGOSH_PATH=/usr/local/mongosh/bin MONGOD_PATH=/usr/local/mongod/bin
ENV PATH=$PATH:$MONGOSH_PATH:$MONGOD_PATH

CMD  /bin/bash




