<?php
// replace index.php with this file to check config envs
/* test if heroku config is load for local usage */
var_dump(getenv("APPBASE_APP_NAME"));
echo "getenv:".getenv("APPBASE_APP_NAME");
