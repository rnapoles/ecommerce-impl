@echo off
curl -s -k -X POST -H "Content-Type: application/json" -H "Content-Type: application/json" https://localhost:12000/api/login --data {\"username\":\"demo@localhost.loc\",\"password\":\"1234\"} | jq -r ".token" > tmpFile
set /p TOKEN= < tmpFile 
del tmpFile 
curl -k -H "Accept: application/json" -H "Authorization: Bearer %TOKEN%" https://localhost:12000/api/info