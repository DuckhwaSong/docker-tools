#!/bin/sh

set -e
echo "Service 'All': Status"
rc-status -a

#echo "Service 'RSyslog': Starting ..."
#rc-service rsyslog start

#echo "Service 'apache2': Starting ..."
#rc-service apache2 start

#echo "Service 'php-fpm82': Starting ..."
rc-service php-fpm82 start

httpd -DNO_DETACH -DFOREGROUND -e info

#if [ "$1" = 'httpd' ]; then
#  echo "Command: '$@'"
#  echo "Service '$1': Launching ..."
#fi
exec $@