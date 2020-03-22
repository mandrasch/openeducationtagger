
⚠️ Work in progress, documentation will be updated in the next days ⚠️


# OpenEducationTagger


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

## Setup heroku

Steps needed before:

- Install heroku cli
- Create an elasticsearch instance at [appbase.io](https://appbase.io), [bonsai.io](https://bonsaio.io), stackheore 
- Prepare the spreadsheet (Clone, publish and note URL - 2DO: provide better infos)

(Note for bonsai.io: You need to manually create an index and append it to the url, auto-index is disabled + no read-only API keys available in bonsai free)

Steps for installation of sync worker (spreadsheet >>> elasticsearch index)

1. Fork this repo
2. Set it up locally
3. Create heroku app with `heroku create` (This will add a git:remote source) / or if it already exists `heroku git:remote -a APPNAME`
4. Add config values to heroku.com dashboard
5. Add .env config values for local testing
4. Test locally with `heroku local worker`
5. If you had any changes, commit them via git
5. If it works, push to heroku `git push heroku master` (pushing to Github will not be enough)
6. Start the web worker: `heroku ps:scale worker=1` (Stop it with `heroku ps:scale worker=0`)
7. Check logs for errors: `heroku logs --tail --ps worker`


2OD: 
- use rabbitmq queue and redis as well to trigger worker? (see: https://devcenter.heroku.com/articles/php-workers#defining-process-types)
- set env var for spreadsheet encoded URL (or use multiple spreadsheets?)
- only update if updateTime in json changed?
- check for 

```
Curl response stdClass Object
2020-03-22T12:41:19.552931+00:00 app[worker.1]: (
 [status] => 429
 [message] => Rate limit exceeded.
 )
```

## Scheduler

`heroku addons:create scheduler:standard`

Test command:
`php worker/index.php coronacampus/cli syncdatafromworksheet`

`heroku addons:open scheduler`

Create job with the above command


## Test locally

The script can be tested locally with

`heroku local worker`

https://devcenter.heroku.com/articles/heroku-local

This will read enviroment variables by default from file `.env` (add this to .gitignore)

Create the `.env`file:

```
APPBASE_API_URL=https://scalr.api.appbase.io
APPBASE_APP_NAME=coronacampus-heroku
APPBASE_AUTH_STRING_READ=XZXXXXX:1XXXXX-XXXX-446c-bf7c-4d327bd203fc
APPBASE_AUTH_STRING_WRITE=OsWXXXXXX:0fad4509-6da1-XXXXXXXXX-XXXX
```

(Shorcut: Use `heroku config` on command line and just copy & paste the values if you already have it setup on heroku.com dashboard, but remove the whitespace + replace ":" with "=")

After this `heroku local worker` should output:
`[OKAY] Loaded ENV .env File as KEY=VALUE Format`





## Personal Notes

`heroku create`

> this will add the git heroku repository to the remote list, but it wont be automatically deployed (Github repositories can be connected to automatically transmit to heroku)

check remotes:

`git remote -v`

Deploy via:

`git push heroku master`

Add `composer.json` otherwise git push heroku master won't work (error)

```
{
  "require-dev": {
    "heroku/heroku-buildpack-php": "*"
  }
}
```

Add `Procfile` in root directory, this is config for web dynos:

```
web: vendor/bin/heroku-php-apache2 web/
```

Run web dyno with apache:

`heroku ps:scale web=1`

But we don't need web, we want a worker:

Procfile

```
worker: php worker/index.php coronacampus/cli loadspreadsheet https%3A%2F%2Fspreadsheets.google.com%2Ffeeds%2Flist%2F1kntJWO9iP6rL6WFqKXNsINoa923LjoDfEz38_NA4-ao%2Fod6%2Fpublic%2Fvalues%3Falt%3Djson
```

Run the worker:

`heroku ps:scale worker=1`

Logs:

`heroku logs --tail --ps worker`

Stop the worker again:

`heroku ps:scale worker=1`

Set up config vars as heroku config (example values used):

```
APPBASE_API_URL:           https://scalr.api.appbase.io
APPBASE_APP_NAME:          coronacampus-heroku
APPBASE_AUTH_STRING_READ:  XZXXXXX:1XXXXX-XXXX-446c-bf7c-4d327bd203fc
APPBASE_AUTH_STRING_WRITE: OsWXXXXXX:0fad4509-6da1-XXXXXXXXX-XXXX
```



## React docu

[https://docs.appbase.io/docs/reactivesearch/v3/overview/quickstart/](https://docs.appbase.io/docs/reactivesearch/v3/overview/quickstart/)

```
npx create-react-app coroncampuswebapp
cd coronacampuswebapp
npm install @appbaseio/reactivesearch
```

Replace index.js, use `process.env.` to access .env vars:
```
import React, { Component } from 'react';
import { ReactiveBase } from '@appbaseio/reactivesearch';

class App extends Component {
	render() {
		return (
			<ReactiveBase
				app="carstore-dataset"
				credentials="process.env.ELASTICSEARCH_AUTH_STRING_READ"
			>
				// other components will go here.
				<div>Hello ReactiveSearch!</div>
			</ReactiveBase>
		);
	}
}
```

Run it: `heroku local coronacampuswebapp`

