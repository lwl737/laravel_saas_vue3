FROM  ubuntu:18.04
LABEL MAINTAINER="linweile<674969619@qq.com>"

ARG DEBIAN_FRONTEND=noninteractive

USER root
# 切换用户

#切换中国时间
RUN  echo "Asia/Shanghai" > /etc/timezone
RUN  ln -sf /usr/share/zoneinfo/Asia/Shanghai /etc/localtime
# -----------
RUN \
sed -i s@/archive.ubuntu.com/@/mirrors.tuna.tsinghua.edu.cn/@g /etc/apt/sources.list \
&& sed -i s@/security.ubuntu.com/@/mirrors.tuna.tsinghua.edu.cn/@g /etc/apt/sources.list \
&& apt clean \
&& apt-get update -y
# 设置国内镜像源 apt-get 加速

# 安装所需环境 sasl
RUN apt-get install gcc -y

RUN apt-get install libsasl2-dev -y

RUN apt-get install libevent-dev -y

RUN apt-get install make -y

RUN apt-get install sasl2-bin -y
#  ----------

ADD ./memcached-1.6.21.tar.gz /

RUN cd /memcached-1.6.21 && ./configure --enable-sasl && make && make install



# 添加账号
#  saslpasswd2 -c -a memcached admin

ENV SASL_CONF_PATH="/etc/sasldb2"

CMD  memcached --version




