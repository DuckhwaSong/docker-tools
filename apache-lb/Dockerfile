FROM httpd:alpine


#set $ENV
ENV ENV=/etc/profile
ADD config/alias.sh /etc/profile.d/alias.sh

# 타임존 설정
ENV TZ=Asia/Seoul
RUN apk add --no-cache tzdata && \
    cp /usr/share/zoneinfo/$TZ /etc/localtime && \
    echo $TZ > /etc/timezone

ADD config/alias.sh /etc/profile.d/alias.sh
RUN apk add --no-cache bash

#RUN apk update
#RUN apk upgrade
#RUN set -x \
#    && sed -i "s/^#LoadModule proxy_module/LoadModule proxy_module/" /usr/local/apache2/conf/httpd.conf \
#    && sed -i "s/^#LoadModule proxy_http_module/LoadModule proxy_http_module/" /usr/local/apache2/conf/httpd.conf \
#    && sed -i "s/^#LoadModule rewrite_module/LoadModule rewrite_module/" /usr/local/apache2/conf/httpd.conf 
#    && sed -i "s/^#LoadModule lbmethod_/LoadModule lbmethod_/" /usr/local/apache2/conf/httpd.conf \
#    && sed -i "s/^#LoadModule slotmem_shm_module/LoadModule slotmem_shm_module/" /usr/local/apache2/conf/httpd.conf \
#    && sed -i "s/^#LoadModule proxy_balancer_module/LoadModule proxy_balancer_module/" /usr/local/apache2/conf/httpd.conf
#RUN echo "Include conf/extra/proxy-balancer.conf" >> /usr/local/apache2/conf/httpd.conf
#ADD config/proxy-balancer.conf /usr/local/apache2/conf/extra/

RUN mv /usr/local/apache2/conf/httpd.conf /usr/local/apache2/conf/httpd.conf.backup
ADD config/httpd.conf /usr/local/apache2/conf/

RUN /usr/local/apache2/bin/apachectl restart