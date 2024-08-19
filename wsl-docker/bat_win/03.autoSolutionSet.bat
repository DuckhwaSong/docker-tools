@echo off

:: ����Ʈ ��ȣ ����
:LOOP1
set /p SITE_NUM=* ������ ����Ʈ ��ȣ [0,1,2,3,4]�� �����ϼ���?
if /i "%SITE_NUM%" == "0" goto YES
if /i "%SITE_NUM%" == "1" goto YES
if /i "%SITE_NUM%" == "2" goto YES
if /i "%SITE_NUM%" == "3" goto YES
if /i "%SITE_NUM%" == "4" goto YES
goto LOOP1
:YES
:: set /p SITE_GIT=������ ����Ʈ GIT �ּҸ� �Է��ϼ���(ex:https://gdev-gitlab.gabiacns.com/gdev-perform/[project].git)?
set SITE_GIT=https://gitlab.gabiacns.com/gabiacnsDev/solution.git
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

:: @echo on


echo * SSH �ͳθ��� ���� �� ����â�� �����ϴ�. ���� ��й�ȣ�� ��Ȯ�� �Է� �Ͻð� �ͳθ� ������ �ּ���
pause

echo mysql ��Ʈ �ͳθ� localhost:6033-> 192.168.197.230:3306
:: start cmd /c ssh -NfL 6033:localhost:3306 gs1902841@192.168.197.230
start cmd /c ssh -NfL 6033:localhost:3306 %_IDNUM_%@192.168.197.230

echo * �ͳθ� �α����� �Ǿ��ٸ� ���� ������ �����ϼ���...
pause

:: DB ���� + DB ����
echo * original_solution_arsc DB ���� ������ �Դϴ�. �� 2�� �ҿ�˴ϴ�.
time /t
:: mysqldump -h localhost -P 6033 -u firstmall -p'throxldtlfvo24^*' original_solution_arsc > ../config/fm_db1.sql
:: mysqldump -uroot -p'tyvldahftkdjqqn!#%&' original_solution_expa --ignore-table=original_solution_expa.fm_db_patch_manager > firstmall_expa.sql
:: sed -i 's/DEFINER=[^*]*\*/\*/g' firstmall_expa.sql
mysqldump -h localhost -P 6033 -u firstmall -pthroxldtlfvo24^^* original_solution_arsc > ../config/fm_db.sql
time /t
echo * DB ����+SQL ���� ������ �Ϸ� �Ǿ����ϴ�. SSH �ͳθ� â�� �����ص� �˴ϴ�.
pause

:: DB ���� + DB ����
echo * DB ����� ���� + ���������� import �մϴ�. �� 2�� �ҿ�˴ϴ�. [Warning] �� �����ϼ���.
time /t
mysql -P 3366 -u root -proot -e "CREATE USER 'fm0%SITE_NUM%'@'%%' IDENTIFIED WITH caching_sha2_password BY 'fm0%SITE_NUM%_pass';"
mysql -P 3366 -u root -proot -e "GRANT ALL PRIVILEGES ON *.* TO 'fm0%SITE_NUM%'@'%%' WITH GRANT OPTION;"
mysql -P 3366 -u root -proot -e "ALTER USER 'fm0%SITE_NUM%'@'%%' REQUIRE NONE WITH MAX_QUERIES_PER_HOUR 0 MAX_CONNECTIONS_PER_HOUR 0 MAX_UPDATES_PER_HOUR 0 MAX_USER_CONNECTIONS 0;"
mysql -P 3366 -u root -proot -e "CREATE DATABASE IF NOT EXISTS `fm0%SITE_NUM%`;"
mysql -P 3366 -u root -proot -e "GRANT ALL PRIVILEGES ON `fm0%SITE_NUM%`.* TO 'fm0%SITE_NUM%'@'%%';"
mysql -P 3366 -u root -proot -e "GRANT ALL PRIVILEGES ON `fm0%SITE_NUM%\_%%`.* TO 'fm0%SITE_NUM%'@'%%';"
mysql -P 3366 -u root -proot fm0%SITE_NUM% < ../config/fm_db.sql
mysql -P 3366 -u root -proot fm0%SITE_NUM% < ../config/fm_db_add.sql
time /t

:: path ���� �� �����
echo * [%_WSL_WORK_PATH_%/www/html_%SITE_NUM%] ��θ� ���� �� ����� �մϴ�.
wsl -d Ubuntu -- rm -rf %_WSL_WORK_PATH_%/www/html_%SITE_NUM%
wsl -d Ubuntu -- mkdir -p %_WSL_WORK_PATH_%/www/html_%SITE_NUM%


:: Git üũ�ƿ�
echo * Git üũ�ƿ� �� 5�� �ҿ�˴ϴ�.
time /t
SET _WDRIVE_PATH_=%_WSL_WORK_PATH_:/=\%
W:
cd W:\%_WDRIVE_PATH_%\www\html_%SITE_NUM%
git config --global --add safe.directory *
git clone %SITE_GIT% .
git checkout -b develop remotes/origin/develop
time /t

:: # dir ���� �� ���� ����
echo * dir ���� �� ���� ����
wsl -d Ubuntu -- sh %_WSL_WORK_PATH_%/bin/03.solutionSet.sh %SITE_NUM%

:: # db���� ���� > DB����
echo * db���� ���� + DB ���� ����
:: # > cp /www/dev~/app/config/database.php app/config/database.php 
wsl -d Ubuntu -- cp -rf '%mnt%/../config/database.php' %_WSL_WORK_PATH_%/www/html_%SITE_NUM%/app/config/
wsl -d Ubuntu -- cp -rf '%mnt%/../config/dev.php' %_WSL_WORK_PATH_%/www/html_%SITE_NUM%/app/controllers/
wsl -d Ubuntu -- sed -i 's/fm00/fm0%SITE_NUM%/g' %_WSL_WORK_PATH_%/www/html_%SITE_NUM%/app/config/database.php

echo * �ڵ� ���̼��� ����
curl http://localhost:888%SITE_NUM%/dev/setautoserial

echo * ��ġ�� �Ϸ�Ǿ����ϴ�. [http://localhost:888%SITE_NUM%] ���� Ȯ���غ�����.
pause
start /max http://localhost:888%SITE_NUM%