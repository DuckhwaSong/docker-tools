@echo off

:: 사이트 번호 설정
:LOOP1
set /p SITE_NUM=* 설정할 사이트 번호 [0,1,2,3,4]를 선택하세요?
if /i "%SITE_NUM%" == "0" goto YES
if /i "%SITE_NUM%" == "1" goto YES
if /i "%SITE_NUM%" == "2" goto YES
if /i "%SITE_NUM%" == "3" goto YES
if /i "%SITE_NUM%" == "4" goto YES
goto LOOP1
:YES
:: set /p SITE_GIT=설정할 사이트 GIT 주소를 입력하세요(ex:https://gdev-gitlab.gabiacns.com/gdev-perform/[project].git)?
set SITE_GIT=https://gitlab.gabiacns.com/gabiacnsDev/solution.git
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

:: @echo on


echo * SSH 터널링을 위해 새 실행창이 열립니다. 계정 비밀번호를 정확히 입력 하시고 터널링 진행해 주세요
pause

echo mysql 포트 터널링 localhost:6033-> 192.168.197.230:3306
:: start cmd /c ssh -NfL 6033:localhost:3306 gs1902841@192.168.197.230
start cmd /c ssh -NfL 6033:localhost:3306 %_IDNUM_%@192.168.197.230

echo * 터널링 로그인이 되었다면 다음 순서를 진행하세요...
pause

:: DB 생성 + DB 덤프
echo * original_solution_arsc DB 덤프 실행중 입니다. 약 2분 소요됩니다.
time /t
:: mysqldump -h localhost -P 6033 -u firstmall -p'throxldtlfvo24^*' original_solution_arsc > ../config/fm_db1.sql
:: mysqldump -uroot -p'tyvldahftkdjqqn!#%&' original_solution_expa --ignore-table=original_solution_expa.fm_db_patch_manager > firstmall_expa.sql
:: sed -i 's/DEFINER=[^*]*\*/\*/g' firstmall_expa.sql
mysqldump -h localhost -P 6033 -u firstmall -pthroxldtlfvo24^^* original_solution_arsc > ../config/fm_db.sql
time /t
echo * DB 덤프+SQL 파일 생성이 완료 되었습니다. SSH 터널링 창은 종료해도 됩니다.
pause

:: DB 생성 + DB 덤프
echo * DB 사용자 생성 + 덤프파일을 import 합니다. 약 2분 소요됩니다. [Warning] 은 무시하세요.
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

:: path 삭제 후 재생성
echo * [%_WSL_WORK_PATH_%/www/html_%SITE_NUM%] 경로를 삭제 후 재생성 합니다.
wsl -d Ubuntu -- rm -rf %_WSL_WORK_PATH_%/www/html_%SITE_NUM%
wsl -d Ubuntu -- mkdir -p %_WSL_WORK_PATH_%/www/html_%SITE_NUM%


:: Git 체크아웃
echo * Git 체크아웃 약 5분 소요됩니다.
time /t
SET _WDRIVE_PATH_=%_WSL_WORK_PATH_:/=\%
W:
cd W:\%_WDRIVE_PATH_%\www\html_%SITE_NUM%
git config --global --add safe.directory *
git clone %SITE_GIT% .
git checkout -b develop remotes/origin/develop
time /t

:: # dir 생성 및 권한 조정
echo * dir 생성 및 권한 조정
wsl -d Ubuntu -- sh %_WSL_WORK_PATH_%/bin/03.solutionSet.sh %SITE_NUM%

:: # db설정 복사 > DB설정
echo * db설정 복사 + DB 계정 설정
:: # > cp /www/dev~/app/config/database.php app/config/database.php 
wsl -d Ubuntu -- cp -rf '%mnt%/../config/database.php' %_WSL_WORK_PATH_%/www/html_%SITE_NUM%/app/config/
wsl -d Ubuntu -- cp -rf '%mnt%/../config/dev.php' %_WSL_WORK_PATH_%/www/html_%SITE_NUM%/app/controllers/
wsl -d Ubuntu -- sed -i 's/fm00/fm0%SITE_NUM%/g' %_WSL_WORK_PATH_%/www/html_%SITE_NUM%/app/config/database.php

echo * 자동 라이선스 설정
curl http://localhost:888%SITE_NUM%/dev/setautoserial

echo * 설치가 완료되었습니다. [http://localhost:888%SITE_NUM%] 접속 확인해보세요.
pause
start /max http://localhost:888%SITE_NUM%