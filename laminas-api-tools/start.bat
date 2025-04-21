:: 현재 폴더를 확인하여 %projectDir% 변수 사용
@echo off
SET var=%CD%
:: FOR /f "tokens=4" %%G IN ("%var:\= %") DO SET projectDir=%%G
FOR %%A IN (%var:\= %) DO SET projectDir=%%A
:: echo %projectDir%

:: 기본포트 지정
IF "%1"=="" (SET port=88)
@echo on

:: 1. 컨테이너 종료+삭제
wsl docker stop "%projectDir%" && docker rm "%projectDir%"

:: 2. 도커 빌드
wsl docker build . -t php-fpm-ing

:: 3. 도커 컨테이너 런
wsl docker run -it -d -p %port%:80 -v "%CD%\html:/www/html" --name "%projectDir%" php-fpm-ing

:: 4. 도커 컨테이너 아파치 + mysql 실행 
wsl docker exec -itd "%projectDir%" "/usr/sbin/httpd"
wsl docker exec -itd "%projectDir%" "/usr/bin/mysqld_safe"

:: 5. 도커 컨테이너 확인
wsl docker exec -it "%projectDir%" ps ax

:: 6. 잠시 멈춰 상태 확인
pause

:: 브라우져 열어 주소를 확인합니다.
"C:\Program Files (x86)\Microsoft\Edge\Application\msedge.exe" "http://localhost:%port%