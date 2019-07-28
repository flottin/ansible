# rsa create


docker build -t eg_sshd .

docker run -d -P -p 22:22 -p 80:80 --name test_sshd_80 eg_sshd
docker run -d -P -p 23:22 -p 81:80 --name test_sshd_81 eg_sshd
docker run -d -P -p 3306:3306 --name test_sshd_3306 eg_sshd