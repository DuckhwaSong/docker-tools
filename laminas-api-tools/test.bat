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

echo %_PROJECT_DIR_%
echo %_PUBLISH_PORT_%

:: 6. 잠시 멈춰 상태 확인
pause