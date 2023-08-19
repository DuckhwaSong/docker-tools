FROM nginx:stable

#set $ENV
ENV ENV=/etc/profile
#ADD config/alias.sh /etc/profile.d/alias.sh
RUN echo "alias ll='ls -alF'" >> /etc/bash.bashrc

RUN apt-get -y update
RUN apt-get -y install vim

ADD config/proxy.conf /etc/nginx/conf.d/default.conf
#RUN /usr/sbin/nginx -s reload
# /usr/sbin/nginx -version #latest>1.25.2 / stable>1.24.0
