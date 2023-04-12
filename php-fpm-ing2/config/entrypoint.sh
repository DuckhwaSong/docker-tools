#!/bin/sh

/usr/bin/mysqld_safe &
/usr/sbin/httpd -D FOREGROUND &
#/usr/local/sbin/php-fpm &

exit;
set -e
echo "Service 'All': Status"
rc-status -a
echo "Service 'RSyslog': Starting ..."
rc-service rsyslog start
if [ "$1" = 'httpd' ]; then
  echo "Command: '$@'"
  echo "Service '$1': Launching ..."
fi
exec $@