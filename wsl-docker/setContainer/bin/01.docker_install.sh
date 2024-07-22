#!/bin/bash

script_dir=`dirname $0`
cd $script_dir
pwd
#source ../../.env
. ../.env

mkdir -p ${_WSL_WORK_PATH_}
cd ${_WSL_WORK_PATH_}

# 0. systemd 설정 + sudo 권한설정
#echo "$USER ALL=(ALL) NOPASSWD:ALL" > /etc/sudoers.d/$USER
echo "[boot]
systemd=true" > /etc/wsl.conf

# 1. 우분투 시스템 패키지 업데이트
sudo apt-get update -y

# 2. 필요한 패키지 설치(설치보류)
# sudo apt-get install -y apt-transport-https ca-certificates curl gnupg-agent software-properties-common

# 3. Docker의 공식 GPG키를 추가
#curl -fsSL https://download.docker.com/linux/ubuntu/gpg | sudo apt-key add -

# 4. Docker의 공식 apt 저장소를 추가
sudo add-apt-repository -y "deb [arch=amd64] https://download.docker.com/linux/ubuntu $(lsb_release -cs) stable"

# 5. 시스템 패키지 업데이트
sudo apt-get update
# 6. Docker 설치
sudo apt-get install -y docker-ce docker-ce-cli containerd.io
# * Ubuntu 환경에서 sudo 권한 없이 docker 명령어 사용하기 > root 기본사용으로 불필요
# sudo usermod -aG docker $USER
sudo apt-get install -y docker-compose

# 7. Docker가 설치 확인
#sudo systemctl status docker

# 5초 슬립
#sleep ${_SLEEP_TIME_SECOND_}