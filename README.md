
~~~
composer docker-run
~~~

~~~
composer dev-install
~~~

~~~
composer migrate-up
~~~

### For production

# Usage

Run container:

~~~
composer docker-run
~~~

Run tests:

~~~
composer test
~~~

Check coverage:

~~~
/console/runtime/output/coverage/index.html
~~~

Frontend:

~~~
http://localhost:20083/
~~~

DB (user - root):

~~~
http://localhost:9003/
~~~

Backend:

~~~
http://localhost:21083/
~~~

Cron example:

~~~
docker exec -i study_cards-frontend-1 php yii_test cron/test
~~~
