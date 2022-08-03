#!/bin/bash

# If first argument is given we dont want to deploy non feature branches
if [[ -n "$1" && "$1" != "master" && ! "$1" =~ ^feature/.+$ && ! "$1" =~ ^fix/.+$ && ! "$1" =~ ^hotfix/.+$ ]]; then
    echo "First argument is not a feature branch: $1" >&2
    exit 1
fi

# Load, parse and prepare environment variables
if [[ ! -f .env ]]; then
    echo "Missing .env file." >&2
    exit 1
fi

source .env

if [[ -z "$SERVICE_NAME" ]]; then
    echo "Environment variable SERVICE_NAME not set or is empty." >&2
    exit 1
fi

if [[ -z "$ENV" ]]; then
    if [[ -z "$APP_ENV" ]]; then
        echo "Environment variable APP_ENV not set or is empty." >&2
        exit 1
    fi

    ENV="$APP_ENV"
fi

# Load, parse and checkout git branch and related docker image.
if [[ -z "$1" ]]; then
    if ! CURRENT_BRANCH=$(git rev-parse --abbrev-ref HEAD) ; then
        echo "Could not determine branch name." >&2
        exit 1
    fi
else
    CURRENT_BRANCH=$1
fi

# Get platform. Needed for special handling of mac users.
unameOut="$(uname -s)"
case "${unameOut}" in
    Linux*)     PLATFORM=Linux;;
    Darwin*)    PLATFORM=Mac;;
    CYGWIN*)    PLATFORM=Cygwin;;
    MINGW*)     PLATFORM=MinGw;;
    *)          PLATFORM="UNKNOWN:${unameOut}"
esac

# Prepare generated env variables.
IMAGE_NAME=$(echo "${CURRENT_BRANCH}" | tr '[:upper:]' '[:lower:]' | sed 's/[^[:alpha:][:digit:]]/-/g')

BRANCH_URL=""
if [[ "${IMAGE_NAME}" != "master" ]]; then
    BRANCH_URL="${IMAGE_NAME}."
fi

if [[ "$ENV" = "local" ]] ; then
    IMAGE_NAME="dev-${IMAGE_NAME}"
fi

CONTAINER_NAME="${SERVICE_NAME}-${ENV}"
CONTAINER_HOSTNAME="${HOSTNAME}"
XDEBUG_CONNECT_BACK=1
XDEBUG_REMOTE_HOST="localhost"
if [[ "$PLATFORM" = "Mac" ]] ; then
    XDEBUG_CONNECT_BACK=0
    XDEBUG_REMOTE_HOST="docker.for.mac.localhost"
fi

export BRANCH_URL
export IMAGE_NAME
export CONTAINER_NAME
export CONTAINER_HOSTNAME
export XDEBUG_CONNECT_BACK
export XDEBUG_REMOTE_HOST
export CURRENT_BRANCH


if [[ "${ENV}" == "local" ]]; then
    DOCKER_BUILDKIT=1 docker-compose -f docker-compose."${ENV}".yml build
else
    docker-compose -f docker-compose."${ENV}".yml pull
fi

docker-compose -p "${IMAGE_NAME}" -f docker-compose."${ENV}".yml up -d --build
echo docker-compose -p "${IMAGE_NAME}" -f docker-compose."${ENV}".yml up -d
