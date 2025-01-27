FROM tomsik68/xampp

COPY . /opt/lampp/htdocs

CMD chmod -R 777 /opt/lampp/htdocs

EXPOSE 22
EXPOSE 80
EXPOSE 3306

ENTRYPOINT /usr/bin/supervisord -n