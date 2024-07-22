@echo off
:: (.env) 파일을 읽고 변수처리
setlocal
for /f "delims=" %%x in (..\.env) do set %%x

:: # mnt path 설정
set mnt=%CD:\=/%
set mnt=/mnt/%mnt::=%
set mnt=%mnt:/C/=/c/%
set mnt=%mnt:/D/=/d/%
set mnt=%mnt:/E/=/e/%
set mnt=%mnt:/F/=/f/%
set mnt=%mnt:/G/=/g/%
@echo on

:: # 5. Ubuntu 설치 + 버전 변경(설치 리스트 확인 : wsl -l --online)
wsl --install -d Ubuntu
wsl --set-version Ubuntu 2
wsl -s Ubuntu

:: # 6. root 사용자 기본사용 (echo '패스워드' | passwd --stdin 계정)
ubuntu config --default-user root

:: # 7. 네트워크 드라이브를 설정
net use W: \\wsl$\Ubuntu 

:: # 8. wsl에 설치 파일 복사 (심볼릭링크 경우 docker 문제 발생)
wsl -d Ubuntu -- mkdir -p %_WSL_WORK_PATH_%/
wsl -d Ubuntu -- chmod 777 %_WSL_WORK_PATH_%/
wsl -d Ubuntu -- ln -s '%mnt%/../.env' %_WSL_WORK_PATH_%/.env
wsl -d Ubuntu -- cp -rf '%mnt%/../setContainer/'* %_WSL_WORK_PATH_%/
wsl -d Ubuntu -- cp -rf '%mnt%/../setContainer/www_sample' %_WSL_WORK_PATH_%/www

:: # 9. Ubuntu systemd 사용 설정 + dos용 파일 liunx용 변환 + sudo 권한
wsl -d Ubuntu -- sed -i 's/\r//g' %_WSL_WORK_PATH_%/bin/*.sh
wsl -d Ubuntu -- sh %_WSL_WORK_PATH_%/bin/00.wsl_set.sh

:: # 10.wsl에 docker 설치 + docker-portainer 설치
wsl -d Ubuntu -- sh %_WSL_WORK_PATH_%/bin/01.docker_install.sh
wsl -d Ubuntu -- sh %_WSL_WORK_PATH_%/bin/01.portainer_install.sh
wsl -d Ubuntu -- sh %_WSL_WORK_PATH_%/bin/01.keep_alive.sh

:: # 11. POTAINER 브라우져 실행
start /max http://localhost:%_POTAINER_PORT_%

pause