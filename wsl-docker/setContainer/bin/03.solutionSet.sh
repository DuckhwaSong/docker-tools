#!/bin/sh

script_dir=`dirname $0`
cd $script_dir
. ../.env

#cd ${_WSL_WORK_PATH_}/www
#rm -rf html_${1}/*
#git clone https://gitlab.gabiacns.com/gabiacnsDev/solution.git ./html_${1}

cd ${_WSL_WORK_PATH_}/www/html_${1}
mkdir -p _compile data/tmp excel_download ep 
chmod -R 777 _compile data excel_download ep

# db설정 복사
# > cp /www/dev~/app/config/database.php app/config/database.php 


# 심볼릭링크 처리
rm admincrm/skin/default/images selleradmin/skin/default/images
ln -s ../../../admin/skin/default/images admincrm/skin/default/images
ln -s ../../../admin/skin/default/images selleradmin/skin/default/images


sleep 5