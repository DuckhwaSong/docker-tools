#!/bin/sh
set -e

echo "Service 'All': Status"

rc-status -a

#echo "Service 'RSyslog': Starting ..."
#rc-service rsyslog start

echo "Service 'dcron': Starting ..."
rc-service dcron restart

echo "Service 'php-fpm83': Starting ..."
rc-service php-fpm83 restart


if [ "$1" = 'httpd' ]; then
  echo "Command: '$@'"

  echo "Service '$1': Launching ..."
fi


exec $@