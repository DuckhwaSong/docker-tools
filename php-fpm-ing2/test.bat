:: 현재 폴더를 확인하여 %projectDir% 변수 사용
@echo off
SET var=%CD%
:: FOR /f "tokens=4" %%G IN ("%var:\= %") DO SET projectDir=%%G
FOR %%A IN (%var:\= %) DO SET projectDir=%%A
:: echo %projectDir%
@echo on

@echo off
for /L %%p in (8080,1,8081) do (
echo %%p

SET R=0

for /f "tokens=5 delims= " %%T IN ('netstat -ano ^| findstr %%p ') DO (
SET R=%%T
if %%R GEQ 0 SET Q=%%R
)


)
echo %Q%
@echo on


echo %1



echo %Q%

pause