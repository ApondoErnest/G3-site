#!/bin/sh
set -eu

cd "$(dirname "$0")/.."

if [ ! -f .env.docker ]; then
    echo ".env.docker is missing" >&2
    exit 1
fi

docker compose build app

if [ "${1:-}" != "--up" ]; then
    echo "image built; pass --up to start the stack"
    exit 0
fi

if docker network inspect g3 >/dev/null 2>&1; then
    project=$(docker network inspect g3 --format '{{ index .Labels "com.docker.compose.project" }}')
    if [ -n "$project" ] && [ "$project" != "g3-control" ]; then
        echo "docker network g3 belongs to ${project}" >&2
        exit 1
    fi
fi

if docker volume inspect g3-control_public-files >/dev/null 2>&1; then
    docker run --rm --user root --entrypoint sh \
        -v g3-control_public-files:/public-files \
        g3-control:runtime \
        -c 'rm -rf /public-files/build && cp -a /var/www/html/public/. /public-files/'
fi

docker compose up -d
