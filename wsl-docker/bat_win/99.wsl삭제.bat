@echo off
:: (.env) 파일을 읽고 변수처리
setlocal
for /f "delims=" %%x in (..\.env) do set %%x
C:

echo WSL을 정말 삭제하시겠습니까?
echo 삭제하면 기존 데이터가 완전 삭제 되며 복구되지 않습니다.
:LOOP
set /p YN=([yes]를 입력하시면 완전 삭제 됩니다)?

if /i "%YN%" == "yes" goto YES

goto LOOP

:YES

:: # Ubuntu 삭제 (설치 리스트 확인 : wsl -l --online)
wsl -t Ubuntu
wsl --unregister Ubuntu
wslconfig /u Ubuntu

:: 심볼릭 삭제
del %CD%\..\..\www

echo WSL이 완전 삭제 되었습니다.
echo 01.wsl설치.bat 파일부터 재실행하시기 바랍니다.
pause