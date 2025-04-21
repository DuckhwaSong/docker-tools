FROM python:3

ENV JAVA_HOME /usr/lib/jvm/java-1.7-openjdk/jre
RUN apt-get update && apt-get install -y g++ default-jdk
#RUN apt-get install g++ openjdk-8-jdk python3-dev python3-pip curl
RUN python3 -m pip install --upgrade pip
RUN pip install konlpy
RUN bash <(curl -s https://raw.githubusercontent.com/konlpy/konlpy/master/scripts/mecab.sh)
