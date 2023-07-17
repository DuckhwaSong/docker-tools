#FROM alpine:latest
#FROM ubuntu:latest
#FROM quay.io/podman/stable:v3.1.2
#FROM jenkins/jenkins:latest
FROM quay.io/podman/stable

USER root

#RUN echo "FROM alpine:latest" >> /root/Dockerfile && echo "" >> /root/Dockerfile &&  echo "RUN apk upgrade && apk update" >> /root/Dockerfile

#WORKDIR /root/
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