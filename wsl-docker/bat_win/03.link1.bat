@echo off
:: (.env) ������ �а� ����ó��
setlocal
for /f "delims=" %%x in (..\.env) do set %%x

:: ���� ���� - �������� ������ �ּ�(::) ����
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
set /p _IDNUM_=ssh���� ������ ���(ID)�� �Է��ϼ���:
:STDINPASS_IDNUM_

IF "%_DOMAIN_ID1_%"=="" (
	GOTO STDIN_DOMAIN_ID1_
)
GOTO STDINPASS_DOMAIN_ID1_
:STDIN_DOMAIN_ID1_
SET /p _DOMAIN_ID1_=https������ ������ ���ڸ�(prefix-ID)�� �Է��ϼ���:
:STDINPASS_DOMAIN_ID1_


:: devbox.firstmall.kr-api id Ȯ��/��ƮȮ�� > curl "https://devbox.firstmall.kr/?action=add&id=DOMAINID" 
:: ���� ��ɾ� ���� 
FOR /f "delims=" %%A IN ('curl "https://%_DEV_SERVER_%/?action=add&id=%_DOMAIN_ID1_%"') DO (SET PORT=%%A)
SET PORT=%PORT:-=%
SET PORT=%PORT: =%
echo "PORT=%PORT%"

echo "��й�ȣ�� ��Ȯ�ϰ� �Է��� �� [https://%_DOMAIN_ID1_%.%_DEV_SERVER_%]���� ���� �����մϴ�."

:: �������ͳθ� ���� ex> ssh -NR 24080:localhost:8880 gs1902841@192.168.243.28
ssh -NR %PORT%:localhost:%_LOCAL_PORT1_% %_IDNUM_%@%_DEV_SERVERIP_%
