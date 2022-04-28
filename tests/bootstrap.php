<?php

use Symfony\Component\Dotenv\Dotenv;

require dirname(__DIR__).'/vendor/autoload.php';

if (file_exists(dirname(__DIR__).'/config/bootstrap.php')) {
    require dirname(__DIR__).'/config/bootstrap.php';
} elseif (method_exists(Dotenv::class, 'bootEnv')) {
    (new Dotenv())->bootEnv(dirname(__DIR__).'/.env');
}

$appEnv = $_ENV['APP_ENV'] ?? 'test';

passthru('echo doctrine:database:drop');
passthru(sprintf('php "%s/../bin/console" doctrine:database:drop --force --env=%s', __DIR__, $appEnv));

passthru('echo doctrine:database:create');
passthru(sprintf('php "%s/../bin/console" doctrine:database:create --env=%s', __DIR__, $appEnv));

passthru('echo doctrine:schema:create');
passthru(sprintf('php "%s/../bin/console" doctrine:schema:create --env=%s', __DIR__, $appEnv));

passthru('echo doctrine:fixtures:load');
passthru(sprintf('php "%s/../bin/console" doctrine:fixtures:load -n --env=%s', __DIR__, $appEnv));
