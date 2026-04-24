@echo off
REM ============================================================================
REM SISTEMA DE COPIAS DE SEGURIDAD - ApBarcelona
REM ============================================================================
setlocal enabledelayedexpansion

set "PROJECT_DIR=C:\xampp\htdocs\ProyectoIntermodular"
set "BACKUP_DIR=C:\backup"
set "LOG_FILE=%BACKUP_DIR%\logs\backup.log"
set "MYSQL_BIN=C:\xampp\mysql\bin"
set "DB_NAME=apbarcelona"
set "DB_USER=root"

REM Crear carpetas si no existen
if not exist "%BACKUP_DIR%\logs" mkdir "%BACKUP_DIR%\logs"
if not exist "%BACKUP_DIR%\db" mkdir "%BACKUP_DIR%\db"
if not exist "%BACKUP_DIR%\files" mkdir "%BACKUP_DIR%\files"

REM Generar nombre con fecha y hora
for /f "tokens=1-4 delims=/ " %%a in ('date /t') do (
    set "FECHA=%%a-%%b-%%c"
)
for /f "tokens=1-2 delims=: " %%a in ('time /t') do (
    set "HORA=%%a-%%b"
)
set "TIMESTAMP=%FECHA%_%HORA%"

echo. >> "%LOG_FILE%"
echo ============================================ >> "%LOG_FILE%"
echo INICIO DE COPIA: %date% %time% >> "%LOG_FILE%"
echo ============================================ >> "%LOG_FILE%"

REM ============================================================================
REM 1. COPIA DE BASE DE DATOS
REM ============================================================================
echo [1] Ejecutando mysqldump...
"%MYSQL_BIN%\mysqldump.exe" -u %DB_USER% %DB_NAME% > "%BACKUP_DIR%\db\db_%TIMESTAMP%.sql" 2>> "%LOG_FILE%"

if %errorlevel%==0 (
    echo [OK] Base de datos guardada: db_%TIMESTAMP%.sql >> "%LOG_FILE%"
) else (
    echo [ERROR] Fallo en mysqldump >> "%LOG_FILE%"
    echo Error en copia de base de datos - revisar log
    exit /b 1
)

REM ============================================================================
REM 2. COMPRESION DE ARCHIVOS DEL PROYECTO
REM ============================================================================
echo [2] Comprimiendo archivos del proyecto...
powershell -Command "Compress-Archive -Path '%PROJECT_DIR%\*' -DestinationPath '%BACKUP_DIR%\files\web_%TIMESTAMP%.zip' -Force"

if %errorlevel%==0 (
    echo [OK] Archivos comprimidos: web_%TIMESTAMP%.zip >> "%LOG_FILE%"
) else (
    echo [ERROR] Fallo en compresion >> "%LOG_FILE%"
    exit /b 1
)

REM ============================================================================
REM 3. VERIFICACION DE INTEGRIDAD (HASH)
REM ============================================================================
echo [3] Generando hashes de verificacion...
powershell -Command "Get-FileHash '%BACKUP_DIR%\db\db_%TIMESTAMP%.sql' -Algorithm MD5 | Select-Object -ExpandProperty Hash" > "%BACKUP_DIR%\db\db_%TIMESTAMP%.md5"
powershell -Command "Get-FileHash '%BACKUP_DIR%\files\web_%TIMESTAMP%.zip' -Algorithm MD5 | Select-Object -ExpandProperty Hash" > "%BACKUP_DIR%\files\web_%TIMESTAMP%.md5"

echo [OK] Hashes guardados >> "%LOG_FILE%"

REM ============================================================================
REM 4. LIMPIEZA DE COPIAS ANTIGUAS (mas de 7 dias)
REM ============================================================================
echo [4] Limpiando copias antiguas...
forfiles /p "%BACKUP_DIR%\db" /s /m *.sql /d -7 /c "cmd /c del @path" 2>nul
forfiles /p "%BACKUP_DIR%\files" /s /m *.zip /d -7 /c "cmd /c del @path" 2>nul

echo [OK] Limpieza completada >> "%LOG_FILE%"

echo ============================================ >> "%LOG_FILE%"
echo FIN DE COPIA: %date% %time% >> "%LOG_FILE%"
echo ============================================ >> "%LOG_FILE%"
echo COPIA COMPLETADA CORRECTAMENTE >> "%LOG_FILE%"
echo. >> "%LOG_FILE%"

echo.
echo ============================================
echo COPIA COMPLETADA CORRECTAMENTE
echo.Archivos guardados en: %BACKUP_DIR%
echo ============================================
exit /b 0