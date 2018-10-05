FROM debian:latest
RUN apt-get update -y
RUN apt-get install vim -y
RUN apt-get install openssh-server -y
RUN apt-get install python -y
RUN apt-get install sudo -y

COPY config /root

EXPOSE 22
