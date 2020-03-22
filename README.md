heroku create

> this will add the git heroku repository to the remote list, so pushing to github will automatically push it to heroku as well

check remotes:

git remote -v


Add Procfile:

web: vendor/bin/heroku-php-apache2 web/


Run web dyno with apache:

heroku ps:scale web=1