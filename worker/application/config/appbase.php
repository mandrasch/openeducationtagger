<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Appbase config using herokus getenv
$config['appbase_app_name'] = getenv("APPBASE_APP_NAME");
$config['appbase_auth_string_read'] = getenv("APPBASE_AUTH_STRING_READ");
// called admin api key in appbase:
$config['appbase_auth_string_write'] = getenv("APPBASE_AUTH_STRING_WRITE");
$config['appbase_api_url'] = getenv("APPBASE_API_URL");
