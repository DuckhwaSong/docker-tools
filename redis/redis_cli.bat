:: 도커가 설치되어 있어야합니다.

:: 1. 도커 컨테이너 redis-cli 실행
:: docker run -it --network redis-net --rm redis:alpine redis-cli -h my_redis
docker exec -it my_redis redis-cli
:: docker exec -it my_redis /bin/sh