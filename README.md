Blog Api
========================

How to run?
--------------

1. Composer install.
   Add database parameters and other parameters that will be prompted
2. php app/console doctrine:database:create
3. php app/console doctrine:migrations:migrate
4. php app/console doctrine:fixtures:load
5. try user with username 'john_doe' and pass 'test123'
6. get access token with a GET 
/oauth/v2/token?grant_type=password&client_id=xxx&client_secret=xxx&username=john_doe&password=test123"
7. with the returned acces_token you can access the secure areas of the api
8. if access token expires you can refresh it with refresh token. using grant_type=refresh_token