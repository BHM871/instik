FROM tomsik68/xampp

CMD ["chmod", "777", "-R", "/opt/lampp/htdocs"]

EXPOSE 22
EXPOSE 80
EXPOSE 3306

ENTRYPOINT ["/usr/bin/supervisord", "-n"]