@echo off
echo ================================
echo SEA Catering Backend Setup
echo ================================
echo.

echo [1/4] Installing dependencies...
call npm install
if %errorlevel% neq 0 (
    echo Error: Failed to install dependencies
    pause
    exit /b 1
)

echo.
echo [2/4] Building TypeScript...
call npm run build
if %errorlevel% neq 0 (
    echo Error: Failed to build TypeScript
    pause
    exit /b 1
)

echo.
echo [3/4] Database will be created automatically...
echo SQLite database will be created at: ./database.sqlite

echo.
echo [4/4] Starting backend server...
echo Backend will run on: http://localhost:3001
echo API endpoints: http://localhost:3001/api
echo.

call npm run dev
