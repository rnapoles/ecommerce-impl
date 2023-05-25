::php bin\console doctrine:schema:update --force
::pause
::START /B CMD /C CALL startSolr.bat 
title ecomerce-backend
call set-php-version.bat
symfony serve --port=12000
