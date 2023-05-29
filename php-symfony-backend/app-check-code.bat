@echo off
:set "uniqueFileName=%tmp%\%RANDOM%.tmp"
set "uniqueFileName=%tmp%\check-code.tmp"
set BASE_DIR=src\
FindAndExec.exe %BASE_DIR% ".php$" php.bat "-l {}" > %uniqueFileName%
cat %uniqueFileName% | grep -v "No syntax errors detected in"
beep.bat