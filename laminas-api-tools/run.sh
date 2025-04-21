#!/bin/bash

script_dir=`dirname $0`
cd $script_dir
. ./.env
#echo "!${_PUBLISH_PORT_}!"

# 1. 컨테이너 종료+삭제
docker stop "${_PROJECT_NAME_}" && docker rm "${_PROJECT_NAME_}"

# 2. 도커 빌드
docker build . -t "${_PROJECT_NAME_}"

# 3. 도커 컨테이너 런
docker run --rm -itd -p ${_PUBLISH_PORT_}:80 --name "${_PROJECT_NAME_}" "${_PROJECT_NAME_}"
#docker run --rm -itd -p ${_PUBLISH_PORT_}:80 -v "${_PROJECT_DIR_}/html:/www/html" --name "${_PROJECT_NAME_}" "${_PROJECT_NAME_}"
# > docker run --rm -itd -p 888:80 -v "/mnt/d/work/docker/docker-tools-dhsong/laminas-api-tools/html:/www/html" --name "laminas-api-tools" "laminas-api-tools"


#:: 4. 도커 컨테이너 아파치 + mysql 실행 
#wsl docker exec -itd "%projectDir%" "/usr/sbin/httpd"
#wsl docker exec -itd "%projectDir%" "/usr/bin/mysqld_safe"

# 5. 도커 컨테이너 확인
docker exec -it "${_PROJECT_NAME_}" ps ax
docker exec -it "${_PROJECT_NAME_}" /bin/sh

# 6. 잠시 멈춰 상태 확인
pause

#:: 브라우져 열어 주소를 확인합니다.
#"C:\Program Files (x86)\Microsoft\Edge\Application\msedge.exe" "http://localhost:%port%