@echo off
:: (.env) ������ �а� ����ó��
setlocal
for /f "delims=" %%x in (..\.env) do set %%x
C:

echo WSL�� ���� �����Ͻðڽ��ϱ�?
echo �����ϸ� ���� �����Ͱ� ���� ���� �Ǹ� �������� �ʽ��ϴ�.
:LOOP
set /p YN=([yes]�� �Է��Ͻø� ���� ���� �˴ϴ�)?

if /i "%YN%" == "yes" goto YES

goto LOOP

:YES

:: # Ubuntu ���� (��ġ ����Ʈ Ȯ�� : wsl -l --online)
wsl -t Ubuntu
wsl --unregister Ubuntu
wslconfig /u Ubuntu

:: �ɺ��� ����
del %CD%\..\..\www

echo WSL�� ���� ���� �Ǿ����ϴ�.
echo 01.wsl��ġ.bat ���Ϻ��� ������Ͻñ� �ٶ��ϴ�.
pause