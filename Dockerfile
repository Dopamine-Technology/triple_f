FROM ubuntu:latest
LABEL authors="abdullahbasem"

ENTRYPOINT ["top", "-b"]

RUN apt-get -y update \
&& apt-get install -y libicu-dev \
&& docker-php-ext-configure intl \
&& docker-php-ext-install intl
