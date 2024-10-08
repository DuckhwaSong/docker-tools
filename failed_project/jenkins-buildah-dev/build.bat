:: 현재 폴더를 확인하여 %projectDir% 변수 사용
@echo off
SET var=%CD%
:: FOR /f "tokens=4" %%G IN ("%var:\= %") DO SET projectDir=%%G
FOR %%A IN (%var:\= %) DO SET projectDir=%%A
:: echo %projectDir%

:: 기본포트 지정
IF "%1"=="" (SET port=8080)
@echo on

:: 1. 컨테이너 종료+삭제
docker stop "%projectDir%" && docker rm "%projectDir%"

:: 2. 도커 빌드
docker build . -t "img-%projectDir%"

:: 3. 도커 컨테이너 런
:: docker run -it -d -p %port%:8080 --name "%projectDir%" "img-%projectDir%"
:: docker run --detach  --net=host --security-opt label=disable --security-opt seccomp=unconfined --device /dev/fuse:rw -it -d --name "%projectDir%" "img-%projectDir%"
:: docker run --detach  --net=host --security-opt label=disable --security-opt seccomp=unconfined --device /dev/fuse:rw -it -d -p %port%:8080 -v "%CD%\efs:/efs" --name "%projectDir%" "img-%projectDir%" 
docker run --security-opt seccomp=unconfined --device /dev/fuse:rw -it -d -p %port%:8080 -v "%CD%\efs:/efs" --name "%projectDir%" "img-%projectDir%" 

:: docker stop jenkins-podman-dev && docker rm jenkins-podman-dev
:: docker run --security-opt seccomp=unconfined --device /dev/fuse:rw -it -d -p 8080:8080  -v "%CD%\efs:/efs" --name "jenkins-podman-dev" "img-jenkins-podman-dev"
:: docker run --security-opt seccomp=unconfined --device /dev/fuse:rw -it -d -v "%CD%\efs:/efs" --name "jenkins-podman-dev" "img-jenkins-podman-dev"
:: docker exec -it jenkins-podman-dev /bin/bash
:: buildah bud -t img-test . && buildah images && exit


:: 4. 도커 컨테이너 아파치 + mysql 실행 
:: docker exec -itd "%projectDir%" "/usr/sbin/httpd"
:: docker exec -itd "%projectDir%" "/usr/bin/mysqld_safe"

:: 5. 도커 컨테이너 mysql 실행 dumping
:: docker cp ./etc/firstmall.sql %projectDir%:/www/
:: docker exec -it "%projectDir%" "/usr/bin/mysql -uroot -ppassword -e 'CREATE DATABASE firstmall;'"
:: docker exec -it "%projectDir%" "/usr/bin/mysql -uroot -ppassword firstmall < /www/firstmall.sql"

:: 5. 도커 컨테이너 확인
docker exec -it "%projectDir%" /bin/bash

:: 6. 컨테이너 종료
docker stop "%projectDir%"

:: 7. 잠시 멈춰 상태 확인
pause
