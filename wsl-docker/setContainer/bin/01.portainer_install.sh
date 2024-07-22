#!/bin/bash

script_dir=`dirname $0`
cd $script_dir
#source ../../.env
. ../.env

# 8. portainer 설치
docker run -d --name portainer -p 9000:9000 -v /var/run/docker.sock:/var/run/docker.sock --restart=always portainer/portainer

# 5초 슬립
sleep ${_SLEEP_TIME_SECOND_}