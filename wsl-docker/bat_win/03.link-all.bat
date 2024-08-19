@echo off
:: (.env) 파일을 읽고 변수처리
setlocal
for /f "delims=" %%x in (..\.env) do set %%x

:: 변수 세팅 - 개별변수 설정시 주석(::) 해제
:: SET _LOCAL_PORT4_=8884
:: SET _DEV_SERVER_=devbox.firstmall.kr
:: SET _IDNUM_=gs1902841
:: SET _DEV_SERVERIP_=192.168.243.28
:: SET _DOMAIN_ID4_=sdh4



:: 루프처리
set _FOR_COUNT_=1
set _FOR_INDEX_=0
:loop

echo Hello World %_FOR_INDEX_%!
:: start cmd /c echo Hello World %_FOR_INDEX_%!
:: ssh -NR %PORT%:localhost:%_LOCAL_PORT2_% %_IDNUM_%@%_DEV_SERVERIP_%



FOR /f "delims=" %%A IN ('curl "https://%_DEV_SERVER_%/?action=add&id=%_DOMAIN_ID2_%"') DO (SET PORT=%%A)
SET PORT=%PORT:-=%
SET PORT=%PORT: =%
echo "PORT=%PORT%"



pause

set /a _FOR_INDEX_=_FOR_INDEX_+1
if %_FOR_INDEX_%==%_FOR_COUNT_% goto exitloop
goto loop
:exitloop

pause

