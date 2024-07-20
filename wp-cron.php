<?php
chdir('/home/u871247528/domains/dev.codelandcs.com/public_html'); // cd /path-to-your-project
exec('php artisan schedule:run >> /dev/null 2>&1'); // execute php artisan schedule:run >> /dev/null 2>&1 in powershell