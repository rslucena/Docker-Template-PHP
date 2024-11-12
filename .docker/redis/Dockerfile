FROM redis/redis-stack-server:latest

RUN apt-get update && apt-get install -y redis-tools

COPY redis.conf /usr/local/etc/redis.conf

CMD [ "redis-server", "/usr/local/etc/redis.conf" ]