#!/bin/bash

#script_dir=`dirname $0`
#cd $script_dir
#source ../../.env

cd ${1}
pwd

docker-compose up -d

sleep 5

# mkdir -p _compile data excel_download ep
# > chmod -R 777 _compile data excel_download ep
# db설정 복사
# > cp /www/dev~/app/config/database.php app/config/database.php 
# 심볼릭링크 처리
# > rm admincrm/skin/default/images selleradmin/skin/default/images
# > ln -s ../../../admin/skin/default/images admincrm/skin/default/images
# > ln -s ../../../admin/skin/default/images selleradmin/skin/default/images

sleep 5