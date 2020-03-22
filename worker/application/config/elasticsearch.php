<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Appbase config using herokus getenv
$config['elasticsearch_url'] = getenv("ELASTICSEARCH_URL");
$config['elasticsearch_auth_string_read'] = getenv("ELASTICSEARCH_AUTH_STRING_READ");
// called admin api key in appbase:
$config['elasticsearch_auth_string_write'] = getenv("ELASTICSEARCH_AUTH_STRING_WRITE");

// worksheet url from gdrive as json
$config['worksheet_url_json'] = getenv("WORKSHEET_JSON_URL");
