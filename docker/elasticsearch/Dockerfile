FROM ubuntu:18.04
LABEL MAINTAINER="linweile<674969619@qq.com>"

ARG DEBIAN_FRONTEND=noninteractive
# 取消命令行交互

USER root
# 切换用户
RUN \
  sed -i s@/archive.ubuntu.com/@/mirrors.tuna.tsinghua.edu.cn/@g /etc/apt/sources.list \
  && sed -i s@/security.ubuntu.com/@/mirrors.tuna.tsinghua.edu.cn/@g /etc/apt/sources.list \
  && apt clean \
  && apt-get update -y
# 设置国内镜像源 apt-get 加速

#切换中国时间
RUN  echo "Asia/Shanghai" > /etc/timezone
RUN  ln -sf /usr/share/zoneinfo/Asia/Shanghai /etc/localtime
# -----------

# 解压es
ADD https://artifacts.elastic.co/downloads/elasticsearch/elasticsearch-8.15.0-linux-x86_64.tar.gz /

RUN tar -xvzf /elasticsearch-8.15.0-linux-x86_64.tar.gz

RUN useradd -m es
RUN chown es -R /elasticsearch-8.15.0
CMD /bin/bash






