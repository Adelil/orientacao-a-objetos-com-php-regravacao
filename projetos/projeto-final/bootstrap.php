<?php
require __DIR__ . '/vendor/autoload.php';

define('HOME', 'http://localhost:3030');
define('VIEWS_PATH', __DIR__ . '/views/');
define('UPLOAD_PATH', __DIR__ . '/public/uploads/');
define('APP_DEBUG', true);

/**
 * Database config
 */
define('DB_NAME', 'projeto_final_loja');
define('DB_HOST', '127.0.0.1');
define('DB_USER', 'root');
define('DB_PASSWORD', '');
define('DB_CHARSET', 'UTF8');


/**
 * PagSeguro Enviroments
 */
putenv('PAGSEGURO_ENV=sandbox');
putenv('PAGSEGURO_EMAIL=nandokstro@gmail.com');
putenv('PAGSEGURO_TOKEN_SANDBOX=74AC9F13254844E592C46F81A546A41B');
putenv('PAGSEGURO_CHARSET=UTF-8');

\PagSeguro\Library::initialize();
\PagSeguro\Library::cmsVersion()->setName("Nome")->setRelease("1.0.0");
\PagSeguro\Library::moduleVersion()->setName("Nome")->setRelease("1.0.0");