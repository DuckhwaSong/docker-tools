:: ��Ŀ�� ��ġ�Ǿ� �־���մϴ�.

:: 0. ��Ŀ �����̳� redis-net ��Ʈ��ũ����
:: docker network create redis-net


:: 1. ��Ŀ �����̳� �ڵ�����
:: docker run --name my_redis -p 6379:6379 --network redis-net -v data:/data -d redis:alpine redis-server --appendonly yes /usr/local/etc/redis/redis.conf
:: docker run --name my_redis -p 6379:6379 -v conf:/etc/redis -v data:/data -d redis:alpine redis-server --appendonly yes 
docker run -p 8080:8080 -p 50000:50000 jenkins/jenkins:lts