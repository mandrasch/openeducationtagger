
⚠️ Work in progress. Early alpha, no professional product

⚠️ Documentation will be updated in the next days 

See also: 

- 👉 current project from german hackathon: ["DigiCampus"](https://twitter.com/FloRa_Education/status/1242056840671879168)
- 👉 Steffen Rörtgen [oerhoernchen20-docker](https://github.com/sroertgen/oerhoernchen20_docker/tree/master/docker_hoernchen)
- 👉 professional: [https://gitlab.com/oersi](gitlab.com/oersi), [x5gon](https://www.x5gon.org/), [oasis.geneseo.edu/](https://oasis.geneseo.edu/)
- 👉 💛💛 upcoming: Social network approach with federated instances [moodle.net/](https://moodle.net/) 💛💛


# OpenEducationTagger 👩‍💻🔎

**Build your own little educational search engine**

Collect freely accessible teaching/learning resource together and build your own little search engine with a nice user interface? Sounds great? The best thing: no nerdy command line usage necessary.

### Here is what you need to do:

1. 📝 **Collect educational resources together**<br>Duplicate this [Google Drive Spreadsheet (Template)](https://docs.google.com/spreadsheets/d/1gqRt0UxtcTNGKduQnTlV1MR3U5ByBkzCyTMkWE6wb04/edit?usp=sharing), collect resources with friends and colleagues, publish the file afterwards via "File -> Publish in the web" (see instructions below).

2. 🗂 **Setup an elasticsearch index**<br>For example via [appbase.io](https://appbase.io/), [bonsai.io](https://bonsai.io/), [stackhero.io](https://stackhero.io/), [qbox.io](https://qbox.io/), [cloud.elastic.co](https://cloud.elastic.co/) etc.


3. 📝➡️🗂 **Send spreadsheet data to elasticsearch index:** ["spreadsheet2index"](https://programmieraffe.gitlab.io/openeducationtagger-spreadsheet2index)<br>Just use the OpenEducationTagger online-tool ["spreadsheet2index"](https://programmieraffe.gitlab.io/openeducationtagger-spreadsheet2index), no command line usage necessary, just a modern webbrowser. (For advanced users: [setup a nodejs cron job on heroku.com](https://gitlab.com/programmieraffe/openeducationtagger-herokuworker)).

3. 🔎 **Setup a nice-looking search interface:** [Live-Demo](https://programmieraffe.gitlab.io/openeducationtagger-frontend/index.html)<br>Fork [this](https://gitlab.com/programmieraffe/openeducationtagger-frontend)  gitlab.com-repository, add some config values via web browser and generate a nice and friendly powered by reactive-search. (Currently work-in-progress)


## Related repositories

- [https://gitlab.com/programmieraffe/openeducationtagger-spreadsheet2index](https://gitlab.com/programmieraffe/openeducationtagger-spreadsheet2index)
- [https://gitlab.com/programmieraffe/openeducationtagger-frontend](https://gitlab.com/programmieraffe/openeducationtagger-frontend)
- Advanced: [https://gitlab.com/programmieraffe/openeducationtagger-herokuworker](https://gitlab.com/programmieraffe/openeducationtagger-herokuworker)

### Inspired by

- [https://timeline.knightlab.com/](https://timeline.knightlab.com/)
- [https://dejavu.appbase.io/](https://dejavu.appbase.io/)

## Built with

- reactive-search (Open Source)
- Gitlab.com CI
- Tutorials provided by people from all over the world <3

## 🔨 Set it up yourself - Screencast (coming soon)


## 📝 Demo Playground (coming soon)

1. Add/change something: SPREADSHEET-PLAYGROUND
2. Every 10 minutes changes will be synced
3. Browse through data: SEARCH-FRONTEND-PLAYGROUND



## Why?

- an online spreadsheet is the fastest way to collect resources together, forms are annoying
- everyone should be able to collect & provide current resources in a nice and modern way

### Goals?

- Setup should also be possible with browser only
- First setup should be possible with zero budget


### 2DO:

- convert cc license url string
- catch appbase.io 429 rate limit exceeded (curl{"status":429,"message":"Rate limit exceeded."})
- better curl response / error handling
- add very simple storage on heroku and save "lastupdated"-information from spreadsheet?
- use webhook for sync (via zapier, etc.?), heroku allows webhooks
- frontend needs to be responsive for mobile usage

## 🔨 Set it up yourself (coming soon)

### 1. Google Spreadsheet for URL collection

[Google Drive Spreadsheet (Template)](https://docs.google.com/spreadsheets/d/1gqRt0UxtcTNGKduQnTlV1MR3U5ByBkzCyTMkWE6wb04/edit?usp=sharing)

1. Copy this (File -> Copy)
2. Make it publicly available
3. Get ID for spreadsheet + worksheet: [https://medium.com/@scottcents/how-to-convert-google-sheets-to-json-in-just-3-steps-228fe2c24e6](https://medium.com/@scottcents/how-to-convert-google-sheets-to-json-in-just-3-steps-228fe2c24e6)
4. Test if URL is correct in browser and returns json
5. Save this URL, you'll need it in step 3

⚠️ **Important notes:** Don't change the column title names unless you're a web developer. If you change the column names in the spreadsheet, you need to customize 'config/openeducationtagger.php' as well as the search interface data model in the frontend.

### 2. Setup elasticsearch (flexible database)

Providers with easy instance setup:

- [appbase.io](https://openwashing.org/)
- [bonsai.io](https://openwashing.org/) (read-only-key only on paid accounts)
- [stackhero.io](https://stackhero.io/) (no read-only-key, but they're working on it, EU-hosting)
- qbox.io - no free account


### (OLD/optional: 3. Setup php sync script (heroku))

2DO: Setup via browser only

⚠️ 2DO: Use "EU" --region=region? https://devcenter.heroku.com/articles/heroku-cli-commands#heroku-apps-create-app
`heroku apps:create --region eu`

Steps needed before:

- Install heroku cli
- Create an elasticsearch instance at [appbase.io](https://appbase.io), [bonsai.io](https://bonsaio.io), stackheore 
- Prepare the spreadsheet (Clone, publish and note URL - 2DO: provide better infos)

(Note for bonsai.io: You need to manually create an index and append it to the url, auto-index is disabled + no read-only API keys available in bonsai free)

Steps for installation of sync worker (spreadsheet >>> elasticsearch index)

1. `git clone https://github.com/programmieraffe/open-education-tagger.git`
2. Create heroku app: `heroku apps:create YOURAPPNAME --region eu` or `heroku apps:create --region eu`
2. `cp .env.example .env`
3. Edit config vars for elasticsearch API with read-write-API-key
4. set config values on heroku.com as well:
`sed 's/#[^("|'')]*$//;s/^#.*$//' .env | \
  xargs heroku config:set`
5. (Test locally with `heroku local worker`)
6. (If you changed source code, commit them via git)
7. Deploy & push to heroku `git push heroku master` (pushing to Github will not be enough!)
8. Start the web worker: `heroku ps:scale worker=1` (Stop it with `heroku ps:scale worker=0`)
9. Check logs for errors: `heroku logs --tail --ps worker`
10. Check frontend/elasticsearch-UI to see if sync worked

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

#### 2DO:

- ⚠️ catch curl 429 rate limit exceeded for appbase.io

#### 3.1 Heroku scheduler (cron)

`heroku addons:create scheduler:standard`

Test command:
`php worker/index.php coronacampus/cli syncdatafromworksheet`

`heroku addons:open scheduler`

Create job with the above command


#### 3.2 Test locally

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


https://www.fomfus.com/articles/how-to-set-heroku-environmental-variables-from-dotenv-env-files

```
sed 's/#[^("|'')]*$//;s/^#.*$//' .env | \
  xargs heroku config:set
```

### 4. Frontend: reactive-search






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

