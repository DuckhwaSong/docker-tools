FROM rockylinux/rockylinux:8.9

RUN set -eux; \
    \
    printf "LANG=\"ko_KR.UTF-8\"\n" > /etc/locale.conf; \
    \
    ln -sf /usr/share/zoneinfo/Asia/Seoul /etc/localtime; \
    \
    #
    dnf install -y dnf-plugins-core epel-release; \
    \
    dnf upgrade -y; \
    \
    # powertool enable
    dnf config-manager --set-enabled powertools; \
    \
    # Add Remi's RPM repository (php)
    dnf install -y https://rpms.remirepo.net/enterprise/remi-release-8.rpm; \
    dnf module -y reset php; \
    dnf module -y enable php:remi-8.2; \
    \
    # base package
    dnf install -y \
        GeoIP-devel \
        git \
        glibc-langpack-ko \
        httpd \
	httpd-devel mod_ssl mod_security \
        patch \
        php-fpm \
        php-cli \
        php-curl \
        php-common \
        php-gd \
        php-json \
        php-mbstring \
        php-mysqlnd \
        php-opcache \
        php-pdo \
        php-pdo_mysql \
        php-pear \
        php-pecl-imagick \
        php-pecl-mcrypt \
        php-pecl-runkit7 \
        php-pecl-zip \
        php-soap \
        php-xml \
        php-xmlrpc \
        postfix \
        redhat-rpm-config \
        supervisor \
        xinetd; \
    dnf clean all; \
    systemctl disable httpd

# 기존 php7.0 대비 불필요 모듈 제거
# RUN dnf install -y php-bcmath php-devel php-xdebug php-intl
# 불필요 패키지 : 필요시 설치
# RUN dnf install -y net-tools openssh-clients htop bind-utils bzip2 convmv lsof lynx procps rsync sed tar vim httpd-devel mod_ssl mod_security

# init script
COPY docker/init.d/* /etc/init.d/
RUN chmod 755 /etc/init.d/httpd /etc/init.d/php-fpm

# supervisor conf
COPY docker/supervisord/* /etc/supervisord.d/

# apache conf
COPY docker/apache/conf /etc/httpd/conf
COPY docker/apache/conf.d /etc/httpd/conf.d
COPY docker/apache/modsecurity.d /etc/httpd/modsecurity.d
RUN set -eux; \
    mkdir -p /www; \
    rm -f /etc/httpd/conf.d/ssl.conf
RUN ln -s /var/www/html /www/html

# php-fpm conf
COPY docker/php/php.ini /etc/
COPY docker/php/php-fpm.conf /etc/
COPY docker/php/php-fpm.d /etc/php-fpm.d
COPY docker/php/extensions/*.so /usr/lib64/php/modules/
RUN set -eux; \
    mkdir -p /run/php-fpm
RUN mkdir -p etc/php
RUN ln -s /etc/php-fpm.d/ /etc/php/php-fpm.d
RUN echo "extension=php_screw_plus.so" >> /etc/php.d/99-etc.ini
RUN echo "extension=php_screw_plus.so" >> /etc/php-zts.d/99-etc.ini

# mysql8 설치
#RUN dnf -y install https://dev.mysql.com/get/mysql80-community-release-el8-8.noarch.rpm
#RUN dnf module reset mysql
#RUN dnf module disable mysql
#RUN dnf -y install mysql-community-server
#RUN mysqld --initialize-insecure --user=mysql
#RUN echo "user=mysql" >> /etc/my.cnf
#RUN /usr/sbin/mysqld --defaults-file=/etc/my.cnf --initialize-insecure &
#RUN echo "[program:mysqld]" > /etc/supervisord.d/mysqld.ini
#RUN echo "command=/usr/sbin/mysqld --defaults-file=/etc/my.cnf" >> /etc/supervisord.d/mysqld.ini
#RUN echo "process_name=%(program_name)s" >> /etc/supervisord.d/mysqld.ini
#RUN echo "startsecs = 0" >> /etc/supervisord.d/mysqld.ini
#RUN echo "autostart=true" >> /etc/supervisord.d/mysqld.ini
#RUN echo "autorestart=true" >> /etc/supervisord.d/mysqld.ini
#RUN echo "stdout_logfile=/dev/stdout" >> /etc/supervisord.d/mysqld.ini
#RUN echo "stdout_logfile_maxbytes=0" >> /etc/supervisord.d/mysqld.ini
#RUN echo "stderr_logfile=/dev/stderr" >> /etc/supervisord.d/mysqld.ini
#RUN echo "stderr_logfile_maxbytes=0" >> /etc/supervisord.d/mysqld.ini

#
WORKDIR /www/html

STOPSIGNAL SIGWINCH

COPY docker/docker-entrypoint /
RUN chmod 755 /docker-entrypoint

EXPOSE 80

CMD ["/docker-entrypoint"]
