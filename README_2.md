2DO: move this notes

⚠️ Work in progress

# coronacampus-reactivesearch

Collect freely accessible teaching/learning resources with a simple google drive spreadsheet and display it with a nice search interface for educators and learners (no webserver needed, you can just use GitHub pages).

- Collect data together: [Google Drive Spreadsheet (Template)](https://docs.google.com/spreadsheets/d/1kntJWO9iP6rL6WFqKXNsINoa923LjoDfEz38_NA4-ao/edit?usp=sharing)
- Search interface hosted on github pages: [https://programmieraffe.github.io/coronacampus-reactivesearch/](https://programmieraffe.github.io/coronacampus-reactivesearch/) ⚠️ Work in progress, does not work correctly by now

Beware: quick & dirty solution, no warranty, not a professional product

## Important notes

If you change the column names in the spreadsheet, you need to customize `controllers/coronacampus/Cli.php` as well as the search interface data model in `frontend-reactive-search` (see foreach-loop for entries).

## Set it up yourself

### 1. Google Spreadsheet for URL collection

[Google Drive Spreadsheet (Template)](https://docs.google.com/spreadsheets/d/1kntJWO9iP6rL6WFqKXNsINoa923LjoDfEz38_NA4-ao/edit?usp=sharing)

1. Copy this
2. Make it publicly available
3. Get ID for spreadsheet + worksheet
4. Test if URL is correct

### 2. Create appbase.io elasticsearch apps/databases

1. Create two elastic search indexes:
a) coronocampus
b) coronacampus-test

2. Create config files for API access:

/config/development/appbase.php

```
// production database
$config['appbase_auth_string_read_coronacampus'] = "zvF51XXXb:cf198494-177dXXXX";
$config['appbase_auth_string_write_coronacampus'] = "nBYXX4sq:889738e4-56b5-XXXX";
$config['appbase_app_name_coronacampus'] = 'coronacampus';
$config['appbase_api_url_coronacampus'] = 'https://scalr.api.appbase.io';
// test database
$config['appbase_auth_string_read_coronacampus-test'] = "zvFXXb:cf198494-177dXXXX";
$config['appbase_auth_string_write_coronacampus-test'] = "nBY9864sq:889738e4-56b5-XXXX";
$config['appbase_app_name_coronacampus-test'] = 'coronacampus-test';
$config['appbase_api_url_coronacampus-test'] = 'https://scalr.api.appbase.io';
```

3. Add config (read-api) for frontend-reactivesearch/public/index.html

```
2DO: JAVASCRIPT VARS in index.html
```


### 3. PHP/CLI: Import Google Spreadsheets data (as JSON) into elasticsearch/appbase.io

To import the data collected in the spreadsheet you'll need access to a php commandline, no full webserver needed.

The importer is based on codeigniter & it's CLI capabilities:

```
cd codeigniter-cli/
php index.php [controller] [method]
```

Basically two files are important (as well as config files):
- controllers/coronacampus/Cli.php
- libraries/coronacampus/Appbase.php


3. Try to insert sample data into your test index:

- Spreadsheet template > copy (leave columns intact, otherwise you need to change it in /application/controllers/coronacampus/cli.php)
- publish spreadsheet
- change url for spreadsheet, encode url for command line

Run php codeigniter cli on command line (example for Mac OSX & MAMP):

` /Applications/MAMP/bin/php/php7.2.1/bin/php index.php coronacampus/cli loadspreadsheet https%3A%2F%2Fspreadsheets.google.com%2Ffeeds%2Flist%2F1kntJWO9iP6rL6WFqKXNsINoa923LjoDfEz38_NA4-ao%2Fod6%2Fpublic%2Fvalues%3Falt%3Djson`

(For Appbase API actions: the logs will be in /application/logs
use tail -f, everything before that will be outputted to command line with custom_log_message)

4. Check data in appbase dashboard (Develop > Browse data)

5. If everything is working, push to production, append 1 as parameter for cli call:

```
/Applications/MAMP/bin/php/php7.2.1/bin/php index.php coronacampus/cli loadspreadsheet https%3A%2F%2Fspreadsheets.google.com%2Ffeeds%2Flist%2F1kntJWO9iP6rL6WFqKXNsINoa923LjoDfEz38_NA4-ao%2Fod6%2Fpublic%2Fvalues%3Falt%3Djson 1
```

5. Provide end user access
(e.g. via Github Project reactive-search)

Alternatives for ElasticSearch hosting appbase.io:
https://www.stackhero.io/

### 4. Frontend: Access data with reactivesearch interface (Github Pages docs/)

Check out `frontend-reactive-search` folder, the interface will be built using `npm run build` into to the docs/ folder, which is the base for Github Pages generation.

1. Add the read-API values from appbase
2. npm install
3. npm build (will create files in docs/ folder on root level)
4. push to github
5. activate Github pages
6. select option "use docs/ folder" for github pages
