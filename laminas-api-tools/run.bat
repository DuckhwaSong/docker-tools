:: 현재 폴더를 확인하여 %projectDir% 변수 사용
@echo off
SET var=%CD%
:: FOR /f "tokens=4" %%G IN ("%var:\= %") DO SET projectDir=%%G
FOR %%A IN (%var:\= %) DO SET projectDir=%%A
:: echo %projectDir%

:: (.env) 파일을 읽고 변수처리
setlocal
for /f "delims=" %%x in (.env) do set %%x

:: 기본포트 지정
IF "%1"=="" (SET port=%_PUBLISH_PORT_%)
@echo on

:: 1. 컨테이너 종료
wsl docker stop "%_PROJECT_NAME_%" 

:: 4. 도커 컨테이너 아파치 + mysql 실행 
:: wsl docker exec -itd "%_PROJECT_NAME_%" "/usr/sbin/httpd"
:: wsl docker exec -itd "%_PROJECT_NAME_%" "/usr/bin/mysqld_safe"

:: 5. 도커 컨테이너 확인
wsl docker exec -it "%projectDir%" ps ax

:: 6. 잠시 멈춰 상태 확인
pause

:: 브라우져 열어 주소를 확인합니다.
"C:\Program Files (x86)\Microsoft\Edge\Application\msedge.exe" "http://localhost:%port%