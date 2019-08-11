# ansible in docker containers

## build image
```
docker build -t sytem_d .
```

## launch container as web server

```
docker run -d -P -p 29:22 -p 89:80 --name system_d_2  \
--cap-add SYS_ADMIN -v /sys/fs/cgroup:/sys/fs/cgroup:ro sytem_d
```

## launch ansible playbook
```
ansible-playbook -i hosts playbook.yml 
```

## go in docker containers and launch sshd
```
docker ps -a
docker exec -it f88baf1019b bash
```

## go in docker via ssh 
```
ssh -i .ssh/id_rsa root@localhost -p 29
```