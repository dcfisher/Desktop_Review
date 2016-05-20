@ECHO Off

:; Clear the screen and turn echo off (above) to keep it clean 
CLS  

:; Get Value from 'VER' command output 
FOR /F "tokens=*" %%i in ('VER') do SET WinVer=%%i 
FOR /F "tokens=1-3 delims=]-" %%A IN ("%WinVer%" ) DO ( 
SET Var1=%%A 
) 

:; Get version number only so drop off Microsoft Windows Version 
FOR /F "tokens=1-9 delims=n" %%A IN ("%Var1%" ) DO ( 
SET WinVer=%%C 
Rem echo %WinVer% 
) 

:; Separate version numbers 
FOR /F "tokens=1-8 delims=.-" %%A IN ("%WinVer%" ) DO ( 
SET WinMajor=%%A 
SET WinMinor=%%B 
SET WinBuild=%%C 
) 

:; Fix the extra space left over in the Major 
FOR /F "tokens=1 delims= " %%A IN ("%WinMajor%" ) DO ( 
SET WinMajor=%%A 
) 


Rem :; Display Results	
Rem ECHO WinVer = %WinVer% 
Rem ECHO WinMajor = %WinMajor% 
Rem ECHO WinMinor = %WinMinor% 
Rem ECHO WinBuild = %WinBuild%
SET part_name=%WinVer:~1,3%
Rem echo %part_name%


if %part_name% == 10.0 echo Windows 10 Data File > C:\review.txt
if %part_name% == 6.3 echo Windows 8 Data File > C:\review.txt
if %part_name% == 6.2 echo Windows 8 Data File > C:\review.txt
if %part_name% == 6.1 echo Windows 7 Data File > C:\review.txt
if %part_name% == 6.0 echo Windows Vista Data File > C:\review.txt
if %part_name% == 5.1 echo Windows XP Data File > C:\review.txt

ver > C:\review.txt
echo. >> C:\review.txt

cscript "%~dp0\ComputerInfo.vbs" > C:\temp.txt
more C:\temp.txt >> C:\review.txt
echo. >> C:\review.txt

PATH %PATH%;%JAVA_HOME%\bin\
for /f tokens^=2-5^ delims^=-^" %%j in ('java -fullversion 2^>^&1') do set "jver=%%j%%k%%l%%m"
echo Java Version: %jver% >> C:\review.txt

echo "Running Windows Updates"
wuapp.exe

echo "Opening SEP. Update SEP and run an active scan"
cd c:\Program Files (x86)\Symantec\Symantec Endpoint Protection\12*\Bin\
If Not Exist SymCorpUI.exe (goto 64bit)
SymCorpUI.exe
goto end64
:64bit
echo "64 bit detected."
cd c:\Program Files\Symantec\Symantec Endpoint Protection\12*\Bin\
If Not Exist SymCorpUI.exe (
Echo "Error!"
echo "Symantec Endpoint Protection not found."
Echo "Please install or update Symantec and run a scan"
pause)
SymCorpUI.exe
:end64
echo "Please Press a Key to Continue After SEP is Done Scanning"
pause

Pause