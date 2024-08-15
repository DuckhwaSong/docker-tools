:: # 1. Linux용 Windows 하위 시스템 사용 설정 ON
dism.exe /online /enable-feature /featurename:Microsoft-Windows-Subsystem-Linux /all /norestart
 
:: # 2. Virtual Machine 기능 사용 설정 ON
dism.exe /online /enable-feature /featurename:VirtualMachinePlatform /all /norestart

:: # 3. wsl 업데이트 > Error: 0x800701bc 오류 대안
wsl --update

:: # 4. WSL2를 기본 버전으로 변경
wsl --set-default-version 2

:: # 5. windows 재부팅
:: shutdown /r