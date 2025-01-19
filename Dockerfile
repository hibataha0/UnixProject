FROM ubuntu  

RUN apt-get update
RUN apt install apache2 -y
RUN apt install php -y 
RUN apt install libapache2-mod-php
RUN apt install php-mysql -y
WORKDIR /var/www/html
RUN rm *

EXPOSE 80
EXPOSE 8081
CMD ["apache2ctl", "-D", "FOREGROUND"]
