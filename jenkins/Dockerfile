FROM jenkins/jenkins:latest

MAINTAINER gabiacns

ENV JENKINS_OPTS='--httpPort=80'
#ENV JENKINS_OPTS='--httpPort=9999 --prefix=/jenkins'
ENV JENKINS_JAVA_OPTIONS='-Dorg.apache.commons.jelly.tags.fmt.timeZone=Asia/Seoul'

USER root

RUN alias ll='ls -alF' 
RUN apt-get upgrade && apt-get update
RUN apt-get install -y rsync
#RUN apt-get install -y docker containerd

# 시간변경
RUN ln -sf /usr/share/zoneinfo/Asia/Seoul /etc/localtime

# jenkins root 권한
RUN sed -i 's/jenkins:x:1000:1000::/jenkins:x:0:0::/' /etc/passwd

#RUN apt install python3-pip -y
#RUN pip3 install awscli — upgrade

# podman 세팅
#RUN apt-get install -y podman

# 볼륨설정을 위한 명령어
#RUN mkdir -p /efs/jenkins
#RUN mv /var/jenkins_home /efs/jenkins/
#RUN ln -s /efs/jenkins/jenkins_home /var/jenkins_home
