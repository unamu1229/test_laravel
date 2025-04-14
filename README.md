```
docker image build -t test_laravel .
docker run --rm -it -v $(pwd):/var/www/ test_laravel /bin/bash
# コンテナ内
cd ..
rm -d laravel
composer global require laravel/installer
~/.composer/vendor/bin/laravel new laravel
```