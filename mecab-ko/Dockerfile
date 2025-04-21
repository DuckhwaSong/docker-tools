FROM ubuntu:16.04

#ENV JAVA_HOME /usr/lib/jvm/java-1.7-openjdk/jre
ENV LD_LIBRARY_PATH /usr/local/lib:$LD_LIBRARY_PATH
apt update
apt install -y wget gcc g++ make

WORKDIR /root
RUN wget https://bitbucket.org/eunjeon/mecab-ko/downloads/mecab-0.996-ko-0.9.2.tar.gz
RUN tar xvfz mecab-0.996-ko-0.9.2.tar.gz
RUN cd mecab-0.996-ko-0.9.2
RUN ./configure
RUN make && make check && make install && mecab --version
#export LD_LIBRARY_PATH='/usr/local/lib'


WORKDIR /root
RUN wget https://bitbucket.org/eunjeon/mecab-ko-dic/downloads/mecab-ko-dic-2.1.1-20180720.tar.gz
RUN tar xvfz mecab-ko-dic-2.1.1-20180720.tar.gz
RUN cd mecab-ko-dic-2.1.1-20180720
RUN ./configure
RUN make && make install

