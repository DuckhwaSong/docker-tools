:: # 1. Linux�� Windows ���� �ý��� ��� ���� ON
dism.exe /online /enable-feature /featurename:Microsoft-Windows-Subsystem-Linux /all /norestart
 
:: # 2. Virtual Machine ��� ��� ���� ON
dism.exe /online /enable-feature /featurename:VirtualMachinePlatform /all /norestart

:: # 3. wsl ������Ʈ > Error: 0x800701bc ���� ���
wsl --update

:: # 4. WSL2�� �⺻ �������� ����
wsl --set-default-version 2

:: # 5. windows �����
:: shutdown /r