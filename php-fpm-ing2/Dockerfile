#FROM alpine:3.7.1 - 새로운시도 가능성있음
#FROM alpine:20190228
#FROM alpine:20190408 - apk 안됨
#FROM alpine:3.4 - php7 버전 없음
#FROM alpine:3.6 - php7 버전 7.1 이상
# FROM php:7.0-apache-stretch
FROM php:7.0-fpm-alpine

RUN apk add --update --no-cache libgd libpng-dev libjpeg-turbo-dev freetype-dev

RUN apk add openrc openssl curl git unzip lynx libmcrypt
RUN apk add apache2 apache-mod-fcgid apache2-proxy apache2-proxy-html apache2-ssl
RUN cp /usr/lib/libxml2.so.2 /usr/lib/libxml2.so

RUN docker-php-ext-install -j$(nproc) mysqli opcache gd
RUN docker-php-ext-install -j$(nproc) mbstring zip
#RUN docker-php-ext-install -j$(nproc) mcrypt xml soap

#RUN apk add php7-apache2
#RUN apk add php7-curl php7-gd php7-common php7-mbstring 
RUN apk add php7-xml php7-zip php7-soap php7-mcrypt 
RUN apk add php7-imagick php7-redis


#RUN apk add php7-json php7-phar php7-iconv php7-zlib
#RUN apk add curl nano git unzip supervisor lynx openssl

# Composer install
RUN curl -sS https://getcomposer.org/installer | php
RUN mv composer.phar /usr/local/bin/composer


# 설정파일 카피
#ADD config/php/php.ini /etc/php7/php.ini
ADD config/php/php.ini /usr/local/etc/php/
ADD config/httpd/conf.d/* /etc/apache2/conf.d/
ADD config/httpd/httpd.conf /etc/apache2/
#ADD config/hosts /etc/hosts
ADD config/.bashrc /root/
ADD extensions/*.so /usr/lib/php7/modules/
ADD config/ssl/ /etc/ssl/
#ADD config/cronjob /etc/cron.d/cronjob
#ADD cron/ /www/src/
RUN mkdir -p /www/html
RUN mkdir -p /run/apache2

# 시간대변경 - 아시아/서울
#RUN ln -sf /usr/share/zoneinfo/Asia/Seoul /etc/localtime
RUN apk --no-cache add tzdata && \
	cp /usr/share/zoneinfo/Asia/Seoul /etc/localtime && \
	echo "Asia/Seoul" > /etc/timezone \
	apk del tzdata


# web src copy
#COPY html /www/html
#COPY ["E:\\work\\cjfreshway\\html\\","/www/html"]
#COPY E:\work\cjfreshway\html /www/html
#COPY ../../html /www/html

# licence
#COPY config/licence/environment_helper.php /www/html/app/helpers/
#COPY config/licence/dbenvironment.php /www/html/app/libraries/

# php-fpm7 실행
#RUN ln -s /usr/local/sbin/php-fpm /etc/init.d/php-fpm
#EXPOSE 9000
#CMD ["php-fpm7"]
#CMD ["/usr/local/sbin/php-fpm"] 
#ENTRYPOINT ["service php-fpm"]
#RUN service php-fpm &

# httpd 실행 > docker exec실행으로 변경
EXPOSE 80 443
#rc-service httpd start && rc-update add httpd default
#CMD ["/usr/sbin/httpd","-D","FOREGROUND"]
#ENTRYPOINT ["/usr/sbin/httpd"]
#RUN /usr/sbin/httpd -k start
#CMD ["/usr/sbin/httpd -k start"]
#CMD ["/usr/sbin/httpd -D FOREGROUND -f /web/config/httpd.conf"]
# /usr/sbin/httpd -D FOREGROUND &


# MYSQL 설정
EXPOSE 3306
#RUN apk add mariadb mariadb-client
#RUN mysql_install_db --user=mysql --ldata=/var/lib/mysql
#RUN /etc/init.d/mariadb start && rc-update add mariadb default
#RUN /usr/bin/mysqld_safe &
#RUN /usr/bin/mysqladmin -u root password 'password'
#ENTRYPOINT ["mysqld_safe"]

RUN git clone -b 5.7 --single-branch https://github.com/mysql/mysql-server.git --depth=1
RUN cd mysql-server && \
    apk add cmake && \
    mkdir bld && \
    cd bld && \
    cmake .. && \
    make && \
    make install

#ADD config/entrypoint.sh /usr/local/bin/
#RUN chmod u+x,g+x /usr/local/bin/entrypoint.sh && ln -s /usr/local/bin/entrypoint.sh / # backwards compat
#ENTRYPOINT ["entrypoint.sh"]
#ENTRYPOINT ["/usr/bin/dumb-init","--","/app/1.sh"]


WORKDIR /www/html

#clear cache
RUN rm -rf /var/cache/apk/*
