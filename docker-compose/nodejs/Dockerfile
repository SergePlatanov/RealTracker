FROM node:22

# UID/GID пользователя на хосте (владельца bind-маунта)
ARG HOST_UID=1000
ARG HOST_GID=1000

# Install dependencies
RUN apt-get update && apt-get install -y \
    vim \
    zip \
    unzip \
    curl && \
    apt-get clean && rm -rf /var/lib/apt/lists/*

# Перекидываем стандартного пользователя node на uid/gid хоста,
# чтобы node_modules в bind-маунте не принадлежали root
RUN groupmod -o -g ${HOST_GID} node && usermod -o -u ${HOST_UID} -g ${HOST_GID} node

USER node
WORKDIR /var/www
