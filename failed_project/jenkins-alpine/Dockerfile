#FROM jenkins/jenkins:latest
FROM jenkins/jenkins:lts-alpine

MAINTAINER gabiacns

ENV JENKINS_OPTS='--httpPort=8888 -Duser.timezone=Asia/Seoul'
#ENV JENKINS_OPTS='--httpPort=9999 --prefix=/jenkins'
ENV JENKINS_JAVA_OPTIONS='-Dorg.apache.commons.jelly.tags.fmt.timeZone=Asia/Seoul'

USER root

#RUN alias ll='ls -alF' 
#RUN apt-get upgrade && apt-get update
#RUN apt-get install -y rsync
RUN apk upgrade && apk update
RUN apk add rsync

# 시간변경
RUN ln -sf /usr/share/zoneinfo/Asia/Seoul /etc/localtime

# jenkins root 권한
RUN sed -i 's/jenkins:x:1000:1000::/jenkins:x:0:0::/' /etc/passwd

# podman 세팅
#RUN apt-get install -y podman


# aws cli 설치
#WORKDIR /root
#RUN curl "https://awscli.amazonaws.com/awscli-exe-linux-x86_64.zip" -o "awscliv2.zip"
#RUN unzip awscliv2.zip
#RUN ./aws/install
#RUN apt-get install -y python3-pip
RUN apk add py3-pip
RUN pip install awscli --break-system-packages

# Session Manager 플러그인 설치 (https://docs.aws.amazon.com/systems-manager/latest/userguide/install-plugin-debian.html)
#RUN curl "https://s3.amazonaws.com/session-manager-downloads/plugin/latest/ubuntu_64bit/session-manager-plugin.deb" -o "session-manager-plugin.deb"
#RUN dpkg -i session-manager-plugin.deb
