@echo off
:: (.env) ������ �а� ����ó��
setlocal
for /f "delims=" %%x in (..\.env) do set %%x
@echo on

wsl -d Ubuntu -- sh %_WSL_WORK_PATH_%/bin/04.stop.sh '%_WSL_WORK_PATH_%'

pause
