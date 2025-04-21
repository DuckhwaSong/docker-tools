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
RUN sed -i "s/#LoadModule rewrite_module/LoadModule rewrite_module/" /etc/apache2/httpd.conf
#RUN sed -i "s/DocumentRoot \"\/var\/www\/localhost\/htdocs\"/DocumentRoot \"\/www\/html\"/" /etc/apache2/httpd.conf
RUN mkdir -p /www/html
RUN mkdir -p /run/apache2
RUN chmod u+x,g+x /www/html
#RUN echo "hello!!" > /www/html/index.html
#RUN echo "<?=phpinfo()?>" > /www/html/index.php
ADD config/*.conf /etc/apache2/conf.d/

# php+fpm
RUN apk add php82 
RUN apk add php82-mysqli php82-opcache php82-gd
RUN apk add php82-xml php82-zip php82-soap php82-redis
RUN apk add php82-pecl-imagick php82-mbstring php82-pdo_mysql php82-pecl-yaml
RUN apk add php82-fpm php82-phar
RUN rc-update add php-fpm82 default
#RUN /usr/bin/php82 /usr/bin/php

# php 확장모듈 설치
#RUN apk add php82-dev gcc g++ automake make

# Composer install
RUN curl -sS https://getcomposer.org/installer | /usr/bin/php
RUN mv composer.phar /usr/local/bin/composer

# laminas-api-tools 설치
RUN apk update
RUN apk add php82-simplexml php82-dom php82-intl php82-tokenizer php82-xmlwriter
#WORKDIR /root
RUN composer create-project laminas-api-tools/api-tools-skeleton:1.9.0 /www/html
WORKDIR /www/html
RUN composer install
RUN composer development-enable

# mariadb 설정
RUN apk add mariadb
RUN /etc/init.d/mariadb setup
RUN rc-update add mariadb default

# ENTRYPOINT 설정
WORKDIR /
COPY config/entrypoint.sh /usr/local/bin/
RUN chmod u+x,g+x /usr/local/bin/entrypoint.sh\
	&& ln -s /usr/local/bin/entrypoint.sh / # backwards compat
ENTRYPOINT ["entrypoint.sh"]

# 작업 DIR 설정
WORKDIR /www/html
RUN chmod 777 -R config data module

# delete apk cache
RUN rm -rf /var/cache/apk/*
