:: 현재 폴더를 확인하여 %projectDir% 변수 사용
@echo off


:: 사용하지 않는 모든 볼륨 (컨테이너가없는 볼륨 포함)를 제거
docker system prune

:: 중지된 모든 컨테이너를 삭제
docker container prune

:: 이름 없는 모든 이미지를 삭제
docker image prune

:: 사용하지 않는 도커 네트워크를 모두 삭제
docker network prune
