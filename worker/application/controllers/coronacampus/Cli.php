<?php
defined('BASEPATH') or exit('No direct script access allowed');

// cd into the codeignter-cli folder, e.g.:
// cd /Users/admin/webserver/coronacampus-reactivesearch/codeigniter-cli

// Try loading sample data into test-index
// /Applications/MAMP/bin/php/php7.2.1/bin/php index.php coronacampus/cli loadspreadsheet https%3A%2F%2Fspreadsheets.google.com%2Ffeeds%2Flist%2F1kntJWO9iP6rL6WFqKXNsINoa923LjoDfEz38_NA4-ao%2Fod6%2Fpublic%2Fvalues%3Falt%3Djson

// 2DO: THIS NEEDS TO BE UPDATED
// TEST CRAWLING (MAMP OSX)
// 1. FLUSH crawltest (2DO: use coronacampus commands)
// /Applications/MAMP/bin/php/php7.2.1/bin/php index.php coronacampus/cli flush_testindex
// 2. TRY loading sample data


// Pro tipp for mac - use caffeinate command so that MAMP won't shutdown
// caffeinate

//require_once APPPATH . 'third_party/simple_dom_parser/simple_dom_parser.php';

class Cli extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        if (!is_cli()) {
            show_error("Should only be access from CLI");
        }


    }

		/* takes public google spreadsheet url (json), see https://medium.com/@scottcents/how-to-convert-google-sheets-to-json-in-just-3-steps-228fe2c24e6
		* @$spreadsheetUrlEncoded - utf8 encoded URL
		*/
    public function loadspreadsheet($spreadsheetUrlEncoded, $publishToProduction = false)
    {

				$spreadsheetUrl = urldecode($spreadsheetUrlEncoded);

        $this->load->library('coronacampus/appbase');

        // publish to production removed by now, just set other values in config/appbase.php if you want to use a test instance
        /*if (!$publishToProduction) {
            // 2DO: flush the whole index via API
            $appbaseIndex = "coronacampus-test";
        } else {
            $appbaseIndex = "coronacampus";
        }*/

        custom_log_message("Start loadspreadsheet");
        custom_log_message("Testing heroku config - APPBASE_APP_NAME: ".print_r(getenv("APPBASE_APP_NAME"),true));

				// $spreadsheetUrl = 'https://spreadsheets.google.com/feeds/list/1kntJWO9iP6rL6WFqKXNsINoa923LjoDfEz38_NA4-ao/od6/public/values?alt=json';
        custom_log_message("Curling url: ".print_r($spreadsheetUrl,true));

        $ch = curl_init();
        $timeout = 30;
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_URL, $spreadsheetUrl);

        // Get URL content
        $response = curl_exec($ch);
        // close handle to release resources
        curl_close($ch);

				// 2DO: verify response

        // 2DO: validate json
        // parse json as associative array
        $responseJson = json_decode($response, true);

        custom_log_message("JSON: ".print_r($responseJson, true));

				// 2DO: show how many items should be parsed

				foreach($responseJson['feed']['entry'] as $entry){

					// 2DO: convert license url to data entry   [gsx$lizenz-urloptional]


          // 2DO: SANITIZE filter_var($projectkey, FILTER_SANITIZE_STRING);

					custom_log_message("Entry: ".$entry['gsx$titel']['$t']);

          // 2DO: define fields which can have multiple values (comma separated)
          $fields_single = array(
            'Titel' => $entry['gsx$titel']['$t'],
            'url' => $entry['gsx$url']['$t'],
            'beschreibung'=>$entry['gsx$beschreibung']['$t'],
            'jahr'=>$entry['gsx$jahroptional']['$t']
          );

          $fields_multiple = array(
            'fachgebiet'=> $entry['gsx$fachgebiet']['$t'],
            'art'=>$entry['gsx$art']['$t'],
            'tags'=>$entry['gsx$tags']['$t'],
            'fachgebiet-destatis'=>$entry['gsx$fachgebiet-nrdestatisoptional']['$t'],
            'sprache'=>$entry['gsx$sprache']['$t'],
          );

          $sanitizedObjectData = array();

          foreach($fields_single as $fieldName => $fieldValueString){
            // check if empty

            $fieldValueSanitized = filter_var($fieldValueString, FILTER_SANITIZE_STRING);

            $sanitizedObjectData["".$fieldName.""] = $fieldValueSanitized;
          }

          foreach($fields_multiple as $fieldName => $fieldValueString){
            // check if empty

            if(strpos($fieldValueString,",")!=false){
              $fieldValueArray = explode(",", $fieldValueString);
              $fieldValueSanitized = filter_var_array($fieldValueArray,FILTER_SANITIZE_STRING);
            }else{
              $fieldValueSanitized = array(filter_var($fieldValueString, FILTER_SANITIZE_STRING));
            }

            $sanitizedObjectData["".$fieldName.""] = $fieldValueSanitized;
          }

            /*custom_log_message("Sanizited entry: ".print_r($fieldValueSanitized,true));*/

          // 2DO: rename to array
          custom_log_message("Sanizited object: ".print_r($sanitizedObjectData,true));

          // 2DO: populate these fields


          // supports only one index right now:
					custom_log_message("Trying to publish to index, see /application/logs/ for these logs (Log threshold must be set to 4)");
					$resultElasticId = $this->appbase->publish_to_index($sanitizedObjectData);

          custom_log_message("Elastic success id: ".$resultElasticId);

				}

        custom_log_message("Done :)");

        return;
    }

    public function flush_testindex()
    {
        $this->load->library('coronacampus/appbase');
        custom_log_message("Flush the test index");
        $this->appbase->flush_testindex();
    }

    // tmp_function,
    /*public function import_json()
    {
        $json = file_get_contents(APPPATH.'/data/data_oerhoernchen20-highereducation__doc_0_1283.json');
        $entries = json_decode($json);
        foreach ($entries as $entry) {
            $this->db->where('main_url_hash', md5($entry->main_url));
            $this->db->from('oerh_log_submitted_entries');
            $count = $this->db->count_all_results();

            if ($count == 0) {
                custom_log_message('Not in index, inserting url ... '.$entry->main_url);
                $array = array(
                        'main_url'=>$entry->main_url,
                        'main_url_hash'=>md5($entry->main_url),
                        'oerhoernchen_id'=>'18411888225d5c2bd259c3c2.24188144',
                        'json_object'=>json_encode($entry)
                    );
                $this->db->set($array);
                $this->db->insert('oerh_log_submitted_entries');
            } else {
                custom_log_message('Url '.$entry->main_url.' is already in index');
            }
        }
    }*/

}
