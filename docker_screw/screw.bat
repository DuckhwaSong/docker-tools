:: ��Ŀ�� ��ġ�Ǿ� �־���մϴ�.

:: 1. ��Ŀ �����̳� �ڵ�����
:: docker run -d -i -t --name screw_linux001 ubuntu /bin/bash
:: docker run -d -i -t --name screw_linux001 ubuntu:16.04 /bin/sh
docker run -d -i -t --name screw_linux001 alpine:3.8 /bin/sh

:: 2. ���ڵ����� ����
docker cp screw screw_linux001:/root/

:: 3. ���ڵ����� php �ҽ����� ����
docker cp src screw_linux001:/root/src/
:: (��������Ȯ��) docker exec screw_linux001 cat /root/src/test.php 

:: 4. ���ڵ����� > �����ο��� gcc �����ϵ� ���� ������ ���� gcompat ��ġ �ʿ�
:: docker exec screw_linux001 apk update
docker exec screw_linux001 apk add gcompat
docker exec screw_linux001 /root/screw /root/src/

:: 5. ���ڵ��� ���� ���÷� ����
docker cp screw_linux001:/root/src ./crypt

:: 6. �����̳� ����+����
docker stop screw_linux001 && docker rm screw_linux001
