:: 도커가 설치되어 있어야합니다.

:: 1. 도커 컨테이너 자동생성
:: docker run -d -i -t --name screw_linux001 ubuntu /bin/bash
:: docker run -d -i -t --name screw_linux001 ubuntu:16.04 /bin/sh
docker run -d -i -t --name screw_linux001 alpine:3.8 /bin/sh

:: 2. 인코딩파일 복사
docker cp screw screw_linux001:/root/

:: 3. 인코딩파일 php 소스폴더 복사
docker cp src screw_linux001:/root/src/
:: (복사파일확인) docker exec screw_linux001 cat /root/src/test.php 

:: 4. 인코딩실행 > 알파인에서 gcc 컴파일된 파일 실행을 위해 gcompat 설치 필요
:: docker exec screw_linux001 apk update
docker exec screw_linux001 apk add gcompat
docker exec screw_linux001 /root/screw /root/src/

:: 5. 인코딩된 파일 로컬로 복사
docker cp screw_linux001:/root/src ./crypt

:: 6. 컨테이너 종료+삭제
docker stop screw_linux001 && docker rm screw_linux001
