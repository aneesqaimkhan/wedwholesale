@echo off
echo Setting up local domains for MedWholesale multi-tenant application...
echo.

echo Adding entries to Windows hosts file...
echo You may need to run this as Administrator.

echo 127.0.0.1 medwholesale.local >> C:\Windows\System32\drivers\etc\hosts
echo 127.0.0.1 demo.medwholesale.local >> C:\Windows\System32\drivers\etc\hosts
echo 127.0.0.1 test.medwholesale.local >> C:\Windows\System32\drivers\etc\hosts

echo.
echo Local domains configured successfully!
echo.
echo You can now access the application using:
echo - http://demo.medwholesale.local:8000 (Demo tenant)
echo - http://test.medwholesale.local:8000 (Test tenant)
echo - http://medwholesale.local:8000 (Main site)
echo.
echo Or continue using the development mode with tenant selection at:
echo - http://127.0.0.1:8000
echo.
pause

