# phpunit実行

```
docker run --rm -it -v $(pwd):/var/www/ test_laravel vendor/bin/phpunit tests/Unit/MessageTest.php
```

# 初回設定
```
docker image build -t test_laravel .
docker run --rm -it -v $(pwd):/var/www/ test_laravel /bin/bash
# コンテナ内
cd ..
rm -d laravel
composer global require laravel/installer
~/.composer/vendor/bin/laravel new laravel
```

## PhpStorm設定
Settings > PHP > Composer の path to composer.json で laravelの composer.json を指定。    
これをしないと、autoloadを正しく読んでコード補完をしてくれない。