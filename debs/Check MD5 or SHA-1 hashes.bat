@echo off
REM You have to have FCIV installed for this to work.
REM http://www.microsoft.com/en-us/download/details.aspx?id=11533
REM (only for windows)
set /p filepath="Enter Filepath with no quotes: " %=%
FCIV -md5 -sha1 "%filepath%"
pause