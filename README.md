# building a docker image from an ansible deployed container

## generate key pair
```
yes y | ssh-keygen -t rsa -b 4096  -f .ssh/id_rsa -N '' > /dev/null
```

## build image
```
docker build -t system_d .
```

## launch container as web server

```
# with docker 
docker run -d -P -p 2281:22 -p 8081:80 -p 441:443 -h web1 --name web1  \
--mount type=bind,source="$(pwd)"/var/www/html,target=/var/www/html \
--cap-add SYS_ADMIN -v /sys/fs/cgroup:/sys/fs/cgroup:ro system_d 
```

## enter container
```
# verify ssh connection
ssh -i .ssh/id_rsa root@localhost -p 2281
```

## ssl local generate
```
bin/mkcert -install
bin/mkcert localhost
mv localhost*.pem etc/ssl/certs/
```

## launch ansible playbook
```
ansible-playbook -i hosts playbook.yml 
```

## persist the container 
```
# when done export container as image
docker export web1 | gzip > web1.tar.gz

# delete image and container
docker rm --force web1
docker image rm system_d

# import container as image
zcat < web1.tar.gz | docker import - system_d

# push the image in registry
docker tag system_d:latest localhost:5000/system_d:latest
docker push localhost:5000/system_d:latest

# delete image and container
docker image rm localhost:5000/system_d:latest

# pull the image
docker pull localhost:5000/system_d:latest
```

## enter the container
```
docker-compose up -d
docker-compose exec web1 bash
```
