<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Appbase config using herokus getenv
$config['openeducationtagger_elasticsearch_url'] = getenv("ELASTICSEARCH_URL");
$config['openeducationtagger_elasticsearch_auth_string_read'] = getenv("ELASTICSEARCH_AUTH_STRING_READ");
// called admin api key in appbase:
$config['openeducationtagger_elasticsearch_auth_string_write'] = getenv("ELASTICSEARCH_AUTH_STRING_WRITE");

// worksheet url from gdrive as json
$config['openeducationtagger_spreadsheet_sheet_url_json'] = getenv("SPREADSHEET_JSON_URL");

// single value fields (fieldForElastic - columName in JSON, e.g. $entry['gsx$titel']['$t'] => 'title')
$config['openeducationtagger_single_value_fields'] = array(
  'title' => 'title',
  'url' => 'url',
  'description'=> 'description',
  'year'=>'year',
  'licenseurl'=>'licenseurl'
);

// multiple value fields
$config['openeducationtagger_multiple_value_fields'] = array(
  'subjectareas'=> 'subjectareas',
  'types'=>'types',
  'tags'=>'tags',
  //'fachgebiet-destatis'=>$entry['gsx$fachgebiet-nrdestatisoptional']['$t'],
  'languages'=>'languages'
);
