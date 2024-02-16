FROM alpine:latest

WORKDIR /srv

EXPOSE 80

COPY ./my-app .
COPY ./entrypoint.sh .

ENTRYPOINT /srv/entrypoint.sh
