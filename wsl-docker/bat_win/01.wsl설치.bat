@echo off
:: (.env) ������ �а� ����ó��
setlocal
for /f "delims=" %%x in (..\.env) do set %%x

:: # mnt path ����
set mnt=%CD:\=/%
set mnt=/mnt/%mnt::=%
set mnt=%mnt:/C/=/c/%
set mnt=%mnt:/D/=/d/%
set mnt=%mnt:/E/=/e/%
set mnt=%mnt:/F/=/f/%
set mnt=%mnt:/G/=/g/%
@echo on

:: # 5. Ubuntu ��ġ + ���� ����(��ġ ����Ʈ Ȯ�� : wsl -l --online)
wsl --install -d Ubuntu
wsl --set-version Ubuntu 2
wsl -s Ubuntu

:: # 6. root ����� �⺻��� (echo '�н�����' | passwd --stdin ����)
ubuntu config --default-user root

:: # 7. ��Ʈ��ũ ����̺긦 ����
net use W: \\wsl$\Ubuntu 

:: # 8. wsl�� ��ġ ���� ���� (�ɺ�����ũ ��� docker ���� �߻�)
wsl -d Ubuntu -- mkdir -p %_WSL_WORK_PATH_%/
wsl -d Ubuntu -- chmod 777 %_WSL_WORK_PATH_%/
wsl -d Ubuntu -- ln -s '%mnt%/../.env' %_WSL_WORK_PATH_%/.env
wsl -d Ubuntu -- cp -rf '%mnt%/../setContainer/'* %_WSL_WORK_PATH_%/
wsl -d Ubuntu -- cp -rf '%mnt%/../setContainer/www_sample' %_WSL_WORK_PATH_%/www

:: # 9. Ubuntu systemd ��� ���� + dos�� ���� liunx�� ��ȯ + sudo ����
wsl -d Ubuntu -- sed -i 's/\r//g' %_WSL_WORK_PATH_%/bin/*.sh
wsl -d Ubuntu -- sh %_WSL_WORK_PATH_%/bin/00.wsl_set.sh

:: # 10.wsl�� docker ��ġ + docker-portainer ��ġ
wsl -d Ubuntu -- sh %_WSL_WORK_PATH_%/bin/01.docker_install.sh
wsl -d Ubuntu -- sh %_WSL_WORK_PATH_%/bin/01.portainer_install.sh
wsl -d Ubuntu -- sh %_WSL_WORK_PATH_%/bin/01.keep_alive.sh

:: # 11. POTAINER ������ ����
start /max http://localhost:%_POTAINER_PORT_%

pause