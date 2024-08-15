@echo off
:: (.env) 파일을 읽고 변수처리
setlocal
for /f "delims=" %%x in (..\.env) do set %%x
@echo on

wsl -d Ubuntu -- sh %_WSL_WORK_PATH_%/bin/04.stop.sh '%_WSL_WORK_PATH_%'

pause
