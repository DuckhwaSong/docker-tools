#!/bin/bash

script_dir=`dirname $0`
cd $script_dir
. ../.env

mkdir -p ${_WSL_WORK_PATH_}
chmod -R 777 ${_WSL_WORK_PATH_}
cd ${_WSL_WORK_PATH_}

# 0. systemd 설정 + sudo 권한설정
# echo "$USER ALL=(ALL) NOPASSWD:ALL" > /etc/sudoers.d/$USER
echo "[boot]
systemd=true" > /etc/wsl.conf

