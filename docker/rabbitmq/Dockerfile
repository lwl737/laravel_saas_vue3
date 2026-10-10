FROM  ubuntu:18.04
LABEL MAINTAINER="linweile<674969619@qq.com>"

ARG DEBIAN_FRONTEND=noninteractive
# 取消命令行交互

USER root
# 切换用户

#切换中国时间
RUN  echo "Asia/Shanghai" > /etc/timezone
RUN  ln -sf /usr/share/zoneinfo/Asia/Shanghai /etc/localtime
#------


# -----------
RUN \
sed -i s@/archive.ubuntu.com/@/mirrors.tuna.tsinghua.edu.cn/@g /etc/apt/sources.list \
&& sed -i s@/security.ubuntu.com/@/mirrors.tuna.tsinghua.edu.cn/@g /etc/apt/sources.list \
&& apt clean \
&& apt-get update -y
# 设置国内镜像源 apt-get 加速




RUN  apt-get install curl  -y
RUN  apt-get install gnupg  -y
RUN  apt-get install apt-transport-https -y

## Team RabbitMQ's main signing key
RUN curl -1sLf "https://keys.openpgp.org/vks/v1/by-fingerprint/0A9AF2115F4687BD29803A206B73A36E6026DFCA" |  gpg --dearmor |  tee /usr/share/keyrings/com.rabbitmq.team.gpg > /dev/null
## Community mirror of Cloudsmith: modern Erlang repository
RUN curl -1sLf https://ppa1.novemberain.com/gpg.E495BB49CC4BBE5B.key |  gpg --dearmor |  tee /usr/share/keyrings/rabbitmq.E495BB49CC4BBE5B.gpg > /dev/null
## Community mirror of Cloudsmith: RabbitMQ repository
RUN curl -1sLf https://ppa1.novemberain.com/gpg.9F4587F226208342.key |  gpg --dearmor |  tee /usr/share/keyrings/rabbitmq.9F4587F226208342.gpg > /dev/null

## Add apt repositories maintained by Team RabbitMQ jammi
# Release	Distribution
# Ubuntu 22.04	jammy
# Ubuntu 20.04	focal
# Ubuntu 18.04	bionic
# Debian Trixie	trixie
# Debian Bookworm	bookworm
# Debian Bullseye	bullseye
# Debian Buster	buster
COPY ./rabbitmq.list /etc/apt/sources.list.d/

## Update package indices
RUN apt-get update -y


RUN apt-get install -y erlang-base \
    erlang-asn1 erlang-crypto erlang-eldap erlang-ftp erlang-inets \
    erlang-mnesia erlang-os-mon erlang-parsetools erlang-public-key \
    erlang-runtime-tools erlang-snmp erlang-ssl \
    erlang-syntax-tools erlang-tftp erlang-tools erlang-xmerl


RUN apt-get install rabbitmq-server -y --fix-missing

CMD  /bin/bash




