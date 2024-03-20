:: 도커가 설치되어 있어야합니다.

:: 0. 도커 컨테이너 redis-net 네트워크설정
:: docker network create redis-net


:: 1. 도커 컨테이너 자동생성
:: docker run --name my_redis -p 6379:6379 --network redis-net -v data:/data -d redis:alpine redis-server --appendonly yes /usr/local/etc/redis/redis.conf
:: docker run --name my_redis -p 6379:6379 -v conf:/etc/redis -v data:/data -d redis:alpine redis-server --appendonly yes 
docker run -p 8080:8080 -p 50000:50000 jenkins/jenkins:lts