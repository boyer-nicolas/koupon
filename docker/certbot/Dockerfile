FROM alpine:latest

RUN apk add --no-cache openssl

RUN mkdir -p $HOME/ssl/certs && mkdir -p $HOME/ssl/private

ADD docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh
ENTRYPOINT ["docker-entrypoint.sh"]

