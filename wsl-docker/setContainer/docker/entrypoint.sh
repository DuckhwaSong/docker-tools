#!/bin/sh
set -e

echo "Service 'All': Status"

rc-status -a

#echo "Service 'RSyslog': Starting ..."
#rc-service rsyslog start

echo "Service 'php-fpm82': Starting ..."
rc-service php-fpm82 restart

echo "Service 'postfix': Starting ..."
rc-service postfix restart

#echo "Service 'mariadb': Starting ..."
#rc-service mariadb restart

if [ "$1" = 'httpd' ]; then
  echo "Command: '$@'"

  echo "Service '$1': Launching ..."
fi


exec $@