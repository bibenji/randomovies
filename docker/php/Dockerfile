FROM php:7.2-fpm

RUN apt-get update
RUN apt-get install -y libwebp-dev libjpeg62-turbo-dev libpng-dev \
    libxpm-dev \
    libfreetype6-dev

RUN docker-php-ext-configure gd --with-gd --with-webp-dir --with-jpeg-dir \
    --with-png-dir --with-zlib-dir --with-xpm-dir \
    --enable-gd-native-ttf \
    --with-freetype-dir

RUN docker-php-ext-install pdo pdo_mysql gd zip

WORKDIR /opt/project/

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" php -r "if (hash_file('SHA384', 'composer-setup.php') === '669656bab3166a7aff8a7506b8cb2d1c292f042046c5a994c43155c0be6190fa0355160742ab2e1c88d40d5be660b410') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
RUN php composer-setup.php --install-dir=. --filename=composer
RUN mv composer /usr/local/bin/composer

RUN apt-get install git -y
RUN apt-get install zip -y
RUN apt-get install unzip -y