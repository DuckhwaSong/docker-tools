FROM alpine:latest

#set $ENV
ENV ENV=/etc/profile
ADD config/alias.sh /etc/profile.d/alias.sh

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

RUN apk add openrc openssl curl git unzip lynx libmcrypt
RUN apk add apache2 apache-mod-fcgid apache2-proxy apache2-proxy-html apache2-ssl
RUN cp /usr/lib/libxml2.so.2 /usr/lib/libxml2.so

RUN apk add php83 
RUN apk add php83-mysqli php83-opcache php83-gd
RUN apk add php83-xml php83-zip php83-soap php83-redis
RUN apk add php83-pecl-imagick php83-mbstring php83-pdo_mysql

RUN apk add php83-fpm
RUN ln -s /usr/bin/php83 /usr/bin/php
RUN ln -s /usr/sbin/php-fpm83 /usr/sbin/php-fpm
RUN adduser -H -D -S -G www-data -s /sbin/nologin www-data

# Composer install
#RUN curl -sS https://getcomposer.org/installer | /usr/bin/php
#RUN mv composer.phar /usr/local/bin/composer

# php 확장모듈 설치
#RUN apk add php83-dev gcc g++ automake make

# php-mod_screwim 설치
#RUN mkdir -p /usr/local/src/ && cd /usr/local/src/ && git clone https://github.com/OOPS-ORG-PHP/mod_screwim.git
#WORKDIR /usr/local/src/mod_screwim
#RUN phpize83
#RUN ./configure --with-php-config=/usr/bin/php-config83 && make install
#RUN echo "screwim.enable = 1" >> /etc/php83/conf.d/91-screwim.ini && echo "extension=screwim.so" >> /etc/php83/conf.d/91-screwim.ini
#RUN cd tools && ./autogen.sh && ./configure && make install 
# which screwim

# php-runkit7 설치
#RUN mkdir -p /usr/local/src/ && cd /usr/local/src/ && git clone https://github.com/runkit7/runkit7.git
#WORKDIR /usr/local/src/runkit7
#RUN phpize83
#RUN ./configure --with-php-config=/usr/bin/php-config83 && make && make install
#RUN echo "extension=runkit7.so" >> /etc/php83/conf.d/92-runkit7.ini
# ls -alF /usr/lib/php*/modules

# cron + certbot
RUN apk add dcron certbot

# php 확장모듈 설치 php-mod_screwim / php-runkit7
ADD config/php.modules/* /usr/lib/php83/modules/
ADD config/php.conf/* /etc/php83/conf.d/

# 설정파일 카피
ADD config/conf.d/* /etc/apache2/conf.d/
ADD config/httpd.conf /etc/apache2/
RUN mkdir -p /www/html
RUN mkdir -p /run/apache2

RUN apk add util-linux openrc
VOLUME /sys/fs/cgroup                 # As suggested above
RUN rc-update add php-fpm83 default\
	&& mkdir /run/openrc\
	&& touch /run/openrc/softlevel      # Workaround for the Error Message
COPY config/entrypoint.sh /usr/local/bin/
RUN chmod u+x,g+x /usr/local/bin/entrypoint.sh\
	&& ln -s /usr/local/bin/entrypoint.sh / # backwards compat
ENTRYPOINT ["entrypoint.sh"]
CMD ["httpd", "-DNO_DETACH", "-DFOREGROUND", "-e", "info"]

# delete apk cache
RUN rm -rf /var/cache/apk/*