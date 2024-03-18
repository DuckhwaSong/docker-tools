#FROM alpine:latest
#FROM ubuntu:latest
#FROM quay.io/podman/stable:v3.1.2
#FROM jenkins/jenkins:latest
#FROM quay.io/podman/stable
#FROM buildah/buildah
#FROM quay.io/buildah/stable:v1.31.0
FROM quay.io/buildah/stable:v1.28.0

USER root

RUN echo "FROM alpine:latest" >> /root/Dockerfile && echo "" >> /root/Dockerfile &&  echo "RUN apk upgrade && apk update" >> /root/Dockerfile

WORKDIR /root/
# podman --log-level debug build -t img-test .
# podman image
# podman build --device /dev/fuse -t demo .

# vfs설정문제는 아래 옵션으로 해결 [--storage-driver=vfs]
# podman --storage-driver=vfs images
# podman --storage-driver=vfs build -t img-test .


# ns 오류 error creating new mount namespace for [/bin/sh -c apk upgrade]: operation not permittederror creating new mount namespace for [/bin/sh -c apk upgrade && apk update]: operation not permitted

# podman build --device /dev/fuse -t img-test .
# podman build --security-opt seccomp=unconfined -t img-test .
# podman build --security-opt seccomp=unconfined --security-opt label=disabled --runtime-flag debug -t img-test .
# cat /etc/*release*


# apt-get install fuse-overlayfs
# mknod /dev/fuse -m 0666 c 10 229


# buildah 설치
#RUN apk update
#RUN apk add buildah
#RUN apt-get update
#RUN apt-get install -y buildah
# buildah bud -t img-test . && buildah images && exit

# jenkins 설치
#RUN yum upgrade
#RUN yum update
RUN yum install -y java-11-openjdk-devel wget
#RUM sdk i java 11.0.13-tem 
RUN wget -O /etc/yum.repos.d/jenkins.repo https://pkg.jenkins.io/redhat-stable/jenkins.repo
RUN rpm --import https://pkg.jenkins.io/redhat-stable/jenkins.io-2023.key
RUN yum install -y jenkins
#RUN systemctl enable jenkins
#RUN systemctl start jenkins