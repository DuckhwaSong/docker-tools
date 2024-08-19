@echo off
:: (.env) 파일을 읽고 변수처리
setlocal
for /f "delims=" %%x in (..\.env) do set %%x

:: 변수 세팅 - 개별변수 설정시 주석(::) 해제
:: SET _LOCAL_PORT1_=8881
:: SET _DEV_SERVER_=devbox.firstmall.kr
:: SET _IDNUM_=gs1902841
:: SET _DEV_SERVERIP_=192.168.243.28
:: SET _DOMAIN_ID1_=sdh1

IF "%_IDNUM_%"=="" (
	GOTO STDIN_IDNUM_
)
GOTO STDINPASS_IDNUM_
:STDIN_IDNUM_
set /p _IDNUM_=ssh접속 가능한 사번(ID)를 입력하세요:
:STDINPASS_IDNUM_

IF "%_DOMAIN_ID1_%"=="" (
	GOTO STDIN_DOMAIN_ID1_
)
GOTO STDINPASS_DOMAIN_ID1_
:STDIN_DOMAIN_ID1_
SET /p _DOMAIN_ID1_=https접속할 도메인 앞자리(prefix-ID)를 입력하세요:
:STDINPASS_DOMAIN_ID1_


:: devbox.firstmall.kr-api id 확인/포트확인 > curl "https://devbox.firstmall.kr/?action=add&id=DOMAINID" 
:: 실행 명령어 저장 
FOR /f "delims=" %%A IN ('curl "https://%_DEV_SERVER_%/?action=add&id=%_DOMAIN_ID1_%"') DO (SET PORT=%%A)
SET PORT=%PORT:-=%
SET PORT=%PORT: =%
echo "PORT=%PORT%"

echo "비밀번호를 정확하게 입력한 뒤 [https://%_DOMAIN_ID1_%.%_DEV_SERVER_%]으로 접속 가능합니다."

:: 리버스터널링 접속 ex> ssh -NR 24080:localhost:8880 gs1902841@192.168.243.28
ssh -NR %PORT%:localhost:%_LOCAL_PORT1_% %_IDNUM_%@%_DEV_SERVERIP_%
