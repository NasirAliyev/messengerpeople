# Installation guide for Interpolator

Firstly, you need install on local machine these soft :

##### 1. git bash
* Git: https://git-scm.com/downloads

##### 2. docker
* Win/Mac: https://www.docker.com/products/docker-desktop
* Linux: https://docs.docker.com/install/linux/docker-ce/ubuntu/

##### 3. docker-compose
* installation : https://docs.docker.com/compose/install/

## After installing all soft you can start.

**Clone files from giving repository.**

**Open CMD/Terminal and run:**
```
docker-compose build
``` 
_Note : First time it will take more time, you should not run it every time before starting work._

**Then run following command:**
```
docker-compose up -d
```

**Check all containers by the command:**
```
docker-compose ps
```

If every thing is okay you should see all container`s statuses *Up* :
```
NAME                     SERVICE             STATUS              PORTS
messengerpeople_main_1   main                running             9000/tcp
```

**Now you should run installation dependencies from main container:**
```
docker-compose exec main composer install
```

### We have finished installation :)

## Tests

Run all tests from command :
```
docker-compose exec main ./vendor/bin/phpunit
```
You will get result like this:

```
PHPUnit 9.5.17

.............    13 / 13 (100%)

Time: 00:00.021, Memory: 6.00 MB

OK (13 tests, 20 assertions)

```