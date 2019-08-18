# ansible in docker containers

## build image
```
docker build -t system_d .
```

## launch container as web server

```
docker run -h web1 -d -P -p 2281:22 -p 81:80 --name web1  \
--mount type=bind,source="$(pwd)"/var/www/html,target=/var/www/html \
--cap-add SYS_ADMIN -v /sys/fs/cgroup:/sys/fs/cgroup:ro system_d 

docker exec -it web1 systemctl start sshd
 
```

## launch ansible playbook
```
ansible-playbook -i hosts playbook.yml 
```


## go in docker via ssh 
```
ssh -i .ssh/id_rsa root@localhost -p 2281
```