@echo off
:: (.env) 파일을 읽고 변수처리
setlocal
for /f "delims=" %%x in (..\.env) do set %%x

:: ============================== 바로가기 파일 만들기 ==============================
::바로가기가 만들어질 경로와 이름, 확장자 lnk를 합쳐서 변수로 저장
:: set lnk=%userprofile%\desktop\cmd.lnk
set lnk=%CD%\..\www_workspace.lnk

::바로가기를 만들 대상
:: set tgpath=%systemroot%\system32\cmd.exe
SET tgpath=W:%_WSL_WORK_PATH_:/=\%\www

::arguments (필요하지 않으면 사용하지 않습니다.)
:: set "arg=/c start explorer.exe"
:: set "arg=W:\\workspace\firstmall-local\www"

::바로가기 아이콘(기본 아이콘으로 설정 시 이 변수는 설정이 필요 없습니다.)
set icon=

::바로가기 시작위치(필요하지 않으면 공백으로 설정해주세요.)
::set startLocation=C:\
set startLocation=W:\

:: 바로가기 파일 만들기
set command=^
$objshell = New-object -ComObject WScript.Shell;^
$lnk = $objshell.CreateShortcut('%lnk%');^
$lnk.TargetPath = '%tgpath%';^
$lnk.WorkingDirectory = '%startLocation%';^
$lnk.Arguments = '%arg%';
if not "%icon%"=="" set command=%command%$lnk.IconLocation ^= '%icon%';
set command=%command%$lnk.Save();

powershell -command "& {%command%}"
:: ============================== 바로가기 파일 만들기 ==============================

wsl -d Ubuntu -- sh %_WSL_WORK_PATH_%/bin/01.keep_alive.sh
wsl -d Ubuntu -- sh %_WSL_WORK_PATH_%/bin/02.run.sh '%_WSL_WORK_PATH_%'

pause