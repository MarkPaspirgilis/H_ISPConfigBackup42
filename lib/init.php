<?php

//CLI-Special
foreach (array(
'REQUEST_URI' => '',
 'HTTPS' => 'off',
 'SERVER_PORT' => 0,
 'SERVER_NAME' => 'localhost',
) as $_SERVER_KEY => $_SERVER_VALUE) {
    if (!isset($_SERVER[$_SERVER_KEY])) {
        $_SERVER[$_SERVER_KEY] = $_SERVER_VALUE;
    }
}
define('IS_CLI', ($_SERVER['REQUEST_URI'] === '' && $_SERVER['SERVER_NAME'] === 'localhost' && $_SERVER['SERVER_PORT'] === 0));


$dir = str_replace('\\', '/', __DIR__) . '/';

define('DIR_LIB', $dir);
define('DIR_ROOT', str_replace('lib/', '', DIR_LIB));
define('DIR_TEMPLATES', DIR_ROOT . 'templates/');
define('DIR_CLASSES', DIR_ROOT . 'classes/');


define('HOUR', 3600);
define('DAY', HOUR * 24);
define('WEEK', DAY * 7);

define('FILE_PW_DB', DIR_PROJECT . 'PW_DB');
if(!is_file(FILE_PW_DB)) {
    file_put_contents(FILE_PW_DB, '');
}
define('PW_DB', trim(file_get_contents(FILE_PW_DB)));

define('FILE_ENVIRONMENT', DIR_PROJECT . 'environment');
define('ENV', is_file(FILE_ENVIRONMENT) ? strtolower(trim(file_get_contents(FILE_ENVIRONMENT))) : 'live');

include DIR_LIB . 'ensure_functions.php';

include DIR_CLASSES . 'request.class.php';
Request::init();
define('BASEURL', 'http' . (is_https() ? 's' : '') . '://' . $_SERVER['SERVER_NAME'] . '/' . Request::$url_path_to_script);

include DIR_CLASSES . 'curl.class.php';
include DIR_CLASSES . 'file.class.php';
include DIR_CLASSES . 'utilities.class.php';
include DIR_CLASSES . 'validate.class.php';
include DIR_CLASSES . 'response.class.php';
include DIR_CLASSES . 'mysql.class.php';

include DIR_LIB . 'db_start.php';

