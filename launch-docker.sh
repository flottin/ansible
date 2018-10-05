docker build -t debian .
docker run -it -d -P -p 22:22 debian /bin/bash
docker container ls
docker exec -it fervent_stallman /bin/bash