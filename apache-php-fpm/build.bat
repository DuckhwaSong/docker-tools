:: 현재 폴더를 확인하여 %projectDir% 변수 사용
@echo off
SET var=%CD%
:: FOR /f "tokens=4" %%G IN ("%var:\= %") DO SET projectDir=%%G
FOR %%A IN (%var:\= %) DO SET projectDir=%%A
:: echo %projectDir%

:: 기본포트 지정
IF "%1"=="" (SET port=8880)
@echo on

:: 1. 컨테이너 종료+삭제
docker stop "%projectDir%" && docker rm "%projectDir%"

:: 2. 도커 빌드
docker build . -t img-%projectDir%

:: 3. 도커 컨테이너 런
docker run -it -d -p %port%:80 -v "%CD%\html:/www/html" --name "%projectDir%" img-%projectDir%
:: docker run -it -d -p %port%:80 --name "%projectDir%" img-%projectDir%
::docker run -it -d -v "%CD%\html:/www/html" --name "%projectDir%" img-%projectDir%

:: 4. 도커 컨테이너 아파치 + mysql 실행 
:: docker exec -itd "%projectDir%" "/usr/sbin/httpd"
:: docker exec -itd "%projectDir%" "/usr/bin/mysqld_safe"

:: 5. 도커 컨테이너 mysql 실행 dumping
:: docker cp ./etc/firstmall.sql %projectDir%:/www/
:: docker cp apache-php-fpm:/etc/apache2/httpd.conf ./
:: docker exec -it "%projectDir%" "/usr/bin/mysql -uroot -ppassword -e 'CREATE DATABASE firstmall;'"
:: docker exec -it "%projectDir%" "/usr/bin/mysql -uroot -ppassword firstmall < /www/firstmall.sql"
:: docker cp apache-php-fpm:/usr/lib/php83/modules/screwim.so ./

:: 5. 도커 컨테이너 확인
docker exec -it "%projectDir%" ps ax
docker exec -it "%projectDir%" /bin/sh

:: 6. 컨테이너 종료
docker stop "%projectDir%"

:: 7. 잠시 멈춰 상태 확인
pause
