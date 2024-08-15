#!/bin/bash

_KEEP_ALIVE_=`ps ax | grep 00.keep_alive.sh |grep -v grep`

# 실행 되고 있는경우 종료
if [ "${_KEEP_ALIVE_}" != "" ]; then
	echo "wsl keep alive 실행중입니다."
	exit;
fi

script_dir=`dirname $0`
cd $script_dir

nohup ./00.keep_alive.sh & >> /dev/null

sleep 5