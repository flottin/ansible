#!/bin/sh

if [ -z "$1" ]
then
      iterate=1
else
      iterate=$1
fi

for i in `seq 1 $iterate`
do
 echo $i;
 docker run -d -P -p 228$i:22 -p 808$i:80 -p 44$i:443 -h web$i --name web$i  \
--mount type=bind,source="$(pwd)"/var/www/html,target=/var/www/html \
--cap-add SYS_ADMIN -v /sys/fs/cgroup:/sys/fs/cgroup:ro system_d

done