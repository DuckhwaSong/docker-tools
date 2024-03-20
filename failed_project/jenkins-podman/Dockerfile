FROM jenkins/jenkins:latest

MAINTAINER gabiacns

ENV JENKINS_OPTS='--httpPort=9999 --prefix=/jenkins'

USER root

RUN alias ll='ls -alF' 
RUN apt-get upgrade && apt-get update
#RUN apt-get install -y docker containerd
#RUN apt-get install -y buildah podman

# podman 세팅
RUN apt-get install -y podman
#RUN useradd podman; \
#echo podman:10000:5000 > /etc/subuid; \
#echo podman:10000:5000 > /etc/subgid;
#RUN mkdir -p /home/podman/.local/share/containers
#ADD https://raw.githubusercontent.com/containers/libpod/master/contrib/podmanimage/stable/containers.conf /etc/containers/containers.conf
#ADD https://raw.githubusercontent.com/containers/libpod/master/contrib/podmanimage/stable/podman-containers.conf /home/podman/.config/containers/containers.conf
#RUN chown podman:podman -R /home/podman
#RUN chmod 644 /etc/containers/containers.conf; sed -i -e 's|^#mount_program|mount_program|g' -e '/additionalimage.*/a "/var/lib/shared",' -e 's|^mountopt[[:space:]]*=.*$|mountopt = "nodev,fsync=0"|g' /etc/containers/storage.conf
#RUN mkdir -p /var/lib/shared/overlay-images /var/lib/shared/overlay-layers /var/lib/shared/vfs-images /var/lib/shared/vfs-layers; touch /var/lib/shared/overlay-images/images.lock; touch /var/lib/shared/overlay-layers/layers.lock; touch /var/lib/shared/vfs-images/images.lock; touch /var/lib/shared/vfs-layers/layers.lock
RUN sed -i 's/jenkins:x:1000:1000::/jenkins:x:0:0::/' /etc/passwd



# pwd
# podman --storage-driver vfs --runroot /var/jenkins_home/ --root /var/jenkins_home/
# podman --storage-driver vfs --runroot /var/jenkins_home/ --root /var/jenkins_home/ -t test-podman-build .



# docker-cli 설치
#RUN curl -sSL -O https://get.docker.com/builds/Linux/x86_64/docker-latest.tgz && tar -xvzf docker-latest.tgz
#RUN mv docker/* /usr/bin/

#RUN apt install python3-pip -y
#RUN pip3 install awscli — upgrade