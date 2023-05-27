@echo off
cls

set AHEADER=-H "Accept: application/json"
set CHEADER=-H "Content-Type: application/json"
set BASE_URL=https://localhost:12000
set JSON_DATA=--data {\"username\":\"demo@localhost.loc\",\"password\":\"1234\"}

curl -s -k -X POST %AHEADER% %CHEADER% %BASE_URL%/api/login %JSON_DATA% | jq -r ".token" > tmpFile
set /p TOKEN= < tmpFile 
set AUTH_HEADER=-H "Authorization: Bearer %TOKEN%"
::del tmpFile 


set APP_URL=%BASE_URL%/api/info
printf "%APP_URL%\n"
curl -s -k -X POST %AUTH_HEADER% %APP_URL% | jq
printf "\n"

set JSON_DATA=--data {\"email\":\"demo@localhost.loc\",\"password\":\"1234\"}
set APP_URL=%BASE_URL%/api/user/register
printf "%APP_URL%\n"
curl -s -k -X POST %APP_URL% %JSON_DATA% | jq
printf "\n"

set JSON_DATA=--data {\"email\":\"demo2@localhost.loc\",\"password\":\"1234\"}
set APP_URL=%BASE_URL%/api/user/register
printf "%APP_URL%\n"
curl -s -k -X POST %APP_URL% %JSON_DATA% | jq
printf "\n"