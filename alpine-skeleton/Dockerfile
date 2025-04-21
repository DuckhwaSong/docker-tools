#FROM alpine:latest
FROM alpine:3.19

#set $ENV
ENV ENV=/etc/profile
# alias 'll=ls -alF'
ADD config/alias.sh /etc/profile.d/alias.sh
ADD config/.bashrc /root/

EXPOSE 80/tcp
EXPOSE 443/tcp

RUN apk update
RUN apk upgrade

RUN apk add --update --no-cache libgd libpng-dev libjpeg-turbo-dev freetype-dev

# 시간대변경 - 아시아/서울
#RUN ln -sf /usr/share/zoneinfo/Asia/Seoul /etc/localtime
RUN apk --no-cache add tzdata && \
	cp /usr/share/zoneinfo/Asia/Seoul /etc/localtime && \
	echo "Asia/Seoul" > /etc/timezone \
	apk del tzdata

# 필수 패키지 설치
RUN apk add bash openrc openssl curl git unzip lynx libmcrypt util-linux
#RUN cp /usr/lib/libxml2.so.2 /usr/lib/libxml2.so

# 시작 데몬 설정
RUN mkdir /run/openrc
RUN touch /run/openrc/softlevel


# 아파치 설정
RUN apk add apache2 apache-mod-fcgid apache2-proxy apache2-proxy-html apache2-ssl
RUN sed -i "s/#ServerName/ServerName/" /etc/apache2/httpd.conf
#RUN sed -i "s/DocumentRoot \"\/var\/www\/localhost\/htdocs\"/DocumentRoot \"\/www\/html\"/" /etc/apache2/httpd.conf
RUN mkdir -p /www/html
RUN mkdir -p /run/apache2
RUN chmod u+x,g+x /www/html
RUN echo "hello!!" > /www/html/index.html
RUN echo "<?=phpinfo()?>" > /www/html/index.php
ADD config/*.conf /etc/apache2/conf.d/

# php+fpm
#RUN apk add php83 
#RUN apk add php83-mysqli php83-opcache php83-gd
#RUN apk add php83-xml php83-zip php83-soap php83-redis
#RUN apk add php83-pecl-imagick php83-mbstring php83-pdo_mysql php83-pecl-yaml
#RUN apk add php83-fpm
#RUN rc-update add php-fpm83 default


#ENTRYPOINT
COPY config/entrypoint.sh /usr/local/bin/
RUN chmod u+x,g+x /usr/local/bin/entrypoint.sh\
	&& ln -s /usr/local/bin/entrypoint.sh / # backwards compat
ENTRYPOINT ["entrypoint.sh"]

# delete apk cache
RUN rm -rf /var/cache/apk/*

