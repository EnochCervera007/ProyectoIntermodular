@echo off
REM ============================================================================
REM SISTEMA DE RESTAURACION - ApBarcelona
REM ============================================================================
setlocal enabledelayedexpansion

set "PROJECT_DIR=C:\xampp\htdocs\ProyectoIntermodular"
set "BACKUP_DIR=C:\backup"
set "LOG_FILE=%BACKUP_DIR%\logs\restauracion.log"
set "MYSQL_BIN=C:\xampp\mysql\bin"

echo. > "%LOG_FILE%"
echo ============================================ >> "%LOG_FILE%"
echo INICIO DE RESTAURACION: %date% %time% >> "%LOG_FILE%"
echo ============================================ >> "%LOG_FILE%"

REM ============================================================================
REM SELECCIONAR COPIA DE BASE DE DATOS
REM ============================================================================
echo.
echo ============================================
echo LISTA DE COPIAS DE BASE DE DATOS DISPONIBLES:
echo ============================================
dir /b /o-d "%BACKUP_DIR%\db\*.sql" 2>nul
echo.

set /p DB_FILE="Introduce el nombre del archivo .sql (o deja vazio para el ultimo): "
if "%DB_FILE%"=="" (
    for /f "delims=|" %%a in ('dir /b /o-d "%BACKUP_DIR%\db\*.sql" 2^>nul ^| findstr /v ".md5" ^| findstr /v ".log"') do set "DB_FILE=%%a" & goto :found_db
    :found_db
)

if not exist "%BACKUP_DIR%\db\%DB_FILE%" (
    echo [ERROR] Archivo no encontrado >> "%LOG_FILE%"
    echo ERROR: Archivo de base de datos no encontrado
    exit /b 1
)

set /p ZIP_FILE="Introduce el nombre del archivo .zip (o deja vazio para el ultimo): "
if "%ZIP_FILE%"=="" (
    for /f "delims=|" %%a in ('dir /b /o-d "%BACKUP_DIR%\files\*.zip" 2^>nul') do set "ZIP_FILE=%%a" & goto :found_zip
    :found_zip
)

if not exist "%BACKUP_DIR%\files\%ZIP_FILE%" (
    echo [ERROR] Archivo no encontrado >> "%LOG_FILE%"
    echo ERROR: Archivo ZIP no encontrado
    exit /b 1
)

echo.
echo Restaurando desde:
echo - Base de datos: %DB_FILE%
echo - Archivos: %ZIP_FILE%
echo.

REM ============================================================================
REM 1. RESTAURAR BASE DE DATOS
REM ============================================================================
echo [1] Restaurando base de datos...
echo [1] Restaurando base de datos... >> "%LOG_FILE%"

"%MYSQL_BIN%\mysql.exe" -u root -e "DROP DATABASE IF EXISTS apbarcelona;" 2>> "%LOG_FILE%"
"%MYSQL_BIN%\mysql.exe" -u root -e "CREATE DATABASE apbarcelona CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;" 2>> "%LOG_FILE%"
"%MYSQL_BIN%\mysql.exe" -u root apbarcelona < "%BACKUP_DIR%\db\%DB_FILE%" 2>> "%LOG_FILE%"

if %errorlevel%==0 (
    echo [OK] Base de datos restaurada >> "%LOG_FILE%"
    echo [OK] Base de datos restaurada
) else (
    echo [ERROR] Fallo al restaurar base de datos >> "%LOG_FILE%"
    echo [ERROR] Fallo al restaurar base de datos
    exit /b 1
)

REM ============================================================================
REM 2. RESTAURAR ARCHIVOS
REM ============================================================================
echo [2] Restaurando archivos...
echo [2] Restaurando archivos... >> "%LOG_FILE%"

if exist "%PROJECT_DIR%" (
    rd /s /q "%PROJECT_DIR%"
)
mkdir "%PROJECT_DIR%"

powershell -Command "Expand-Archive -Path '%BACKUP_DIR%\files\%ZIP_FILE%' -DestinationPath '%PROJECT_DIR%' -Force"

if %errorlevel%==0 (
    echo [OK] Archivos restaurados >> "%LOG_FILE%"
    echo [OK] Archivos restaurados
) else (
    echo [ERROR] Fallo al restaurar archivos >> "%LOG_FILE%"
    echo [ERROR] Fallo al restaurar archivos
    exit /b 1
)

echo ============================================ >> "%LOG_FILE%"
echo FIN DE RESTAURACION: %date% %time% >> "%LOG_FILE%"
echo ============================================ >> "%LOG_FILE%"
echo RESTAURACION COMPLETADA >> "%LOG_FILE%"

echo.
echo ============================================
echo RESTAURACION COMPLETADA CORRECTAMENTE
echo ============================================
echo.
echo Verifica en: http://localhost/ProyectoIntermodular/
exit /b 0