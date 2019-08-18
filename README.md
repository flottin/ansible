# ansible in docker containers

## build image
```
docker build -t system_d .
```

## launch container as web server

```
docker run -h web2 -d -P -p 22:22 -p 82:80 --name web2  \
--mount type=bind,source="$(pwd)"/var/www/html,target=/var/www/html \
--cap-add SYS_ADMIN -v /sys/fs/cgroup:/sys/fs/cgroup:ro system_d 

docker exec -it web2 systemctl start sshd
 
```

## launch ansible playbook
```
ansible-playbook -i hosts playbook.yml 
```


## go in docker via ssh 
```
ssh -i .ssh/id_rsa root@localhost -p 22
```