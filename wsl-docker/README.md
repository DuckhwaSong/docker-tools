# 퍼스트몰 윈도우 로컬 도커 개발 세팅

## 설명

로컬PC에 Docker컨테이너 방식의 세팅 방법 및 툴을 정리하였습니다.

```
1. 윈도우 업데이트 : wsl 사용 가능한 상태로 업데이트 win10/11 가능 > (경우에 따라 생략가능)
2. bat_win/00.prepare_for_admin.bat 관리자 권한으로 실행 : wsl 사용하기 윈한 환경 설정 + 재부팅
3. bat_win/01.wsl설치.bat 실행 : wsl 설치 + root 기본권한 + docker+portainer 설치 (설치 확인 > http://localhost:9000)
4. bat_win/02.run.bat 실행 : 컨테이너 실행 후 html dir 내용 확인 및 http://localhost:3380 에서 DB확인 (root/root)
```

***

## 준비 : .env 설정
- [ ] 윈도우 업데이트 상태를 확인하여 최신의 상태로 업데이트 한다. (이미 WSL을 사용하고 있는 경우 업데이트 하지 않아도 된다.)
- [ ] .env파일을 수정하여 설정을 진행한다.

```
_POTAINER_PORT_=9000
_WSL_WORK_PATH_=/work/local
_SLEEP_TIME_SECOND_=5
_LOCAL_PORT0_=8880
_LOCAL_PORT1_=8881
_LOCAL_PORT2_=8882
_LOCAL_PORT3_=8883
_LOCAL_PORT4_=8884
```

## 00. WSL 준비

윈도우 상태가 업데이트 되었다면, 00.prepare_for_admin.bat 파일을 마우스 우클릭을 통해 관리자 권한으로 실행한다.

## 01.wsl설치.bat (설치 + 세팅)

wsl에 기본 linux를 Ubuntu로 설정하고, 해당 wsl에 Docker와 portainer를 설치한다.
설치가 마무리 되면, http://localhost:9000 에서 확인 가능하며 초기 접속시 관리 비밀번호를 설정해야 한다.

## 02.run.bat

docker의 docker-compose 명령어로 초기 컨테이너 이미지를 빌드하고, 빌드된 이미지와 mysql, phpmyadnin 등의 컨테이너를 같은 네트워크에 묶어서 실행시킨다.
네트워크 대역은 172.80.0.0 이며, 서로간 host명으로 접속이 가능하다.
** PC를 재시작 했을때 02.run.bat을 실행하여 wsl과 작업 컨테이너를 활성화 해야 개발이 가능하다.


## *. 기타 설정
- [ ] DB 접속 : http://localhost:3380 ID/PW : root/root
- [ ] 컨테이너 linux 의 파일은 네트워크드라이브 설정 후 탐색기로 확인해 볼 수 있다. 기본 설치 bat파일에서 W드라이브(W:)로 설정되며, 탐색기로 리눅스의 파일시스템을 확인 할 수 있다.

