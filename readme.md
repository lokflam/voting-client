# Voting client
This project is the front-end client for https://github.com/lokflam/voting

## Development environment (optional)
Docker & Docker Compose
```
# docker
sudo apt-get update
sudo apt-get install -y apt-transport-https ca-certificates curl software-properties-common
curl -fsSL https://download.docker.com/linux/ubuntu/gpg | sudo apt-key add -
sudo add-apt-repository "deb [arch=amd64] https://download.docker.com/linux/ubuntu $(lsb_release -cs) stable"
sudo apt-get update
sudo apt-get install -y docker-ce
sudo usermod -a -G docker ${USER}

# docker-compose
sudo curl -L "https://github.com/docker/compose/releases/download/1.23.1/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose
sudo chmod +x /usr/local/bin/docker-compose

# login again
```
Refer to [Laradock](https://laradock.io/)
1. `git clone https://github.com/Laradock/laradock.git`
1. `cd laradock && cp env-example .env`
1. Edit `.env`
    1. Change MYSQL_VERSION to 5.7
    1. Change APP_CODE_PATH_HOST (optional)
1. `docker-compose up -d nginx mysql phpmyadmin workspace`
    1. Shut down: `docker-compose down`
1. `docker-compose exec workspace bash`

## Setup
Refer to [Laravel](https://laravel.com/)
1. `cd voting-client`
1. `composer install`
1. `cp env-example .env`
1. `php artisan key:generate`
1. Edit `.env`
    1. Change APP_URL and DB config (optional)
    1. Change VOTING_URL and BLOCKCHAIN_URL
    1. Change BLOCKCHAIN_KEY (optional)
1. Create database with the config in `.env`
1. `php artisan migrate:refresh --seed`
1. Setup cron job with `* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1`
1. Done, access with a browser
