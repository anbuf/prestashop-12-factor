<?php

$db = array_merge(['port' => 3306], parse_url(getenv('JAWSDB_URL') ?: getenv('CLEARDB_DATABASE_URL')));
define('DB_NAME', substr($db['path'], 1));
define('DB_USER', $db['user']);
define('DB_PASSWORD', $db['pass']);
define('DB_SERVER', $db['host'].':'.$db['port']);
define('DB_CHARSET', 'utf8');

define('AWS_ACCESS_KEY_ID', getenv('AWS_ACCESS_KEY_ID') ?: getenv('BUCKETEER_AWS_ACCESS_KEY_ID'));
define('AWS_SECRET_ACCESS_KEY', getenv('AWS_SECRET_ACCESS_KEY') ?: getenv('BUCKETEER_AWS_SECRET_ACCESS_KEY'));
define('AS3CF_BUCKET', getenv('S3_BUCKET') ?: getenv('BUCKETEER_BUCKET_NAME'));
if (getenv('S3_REGION')) {
    define('AS3CF_REGION', getenv('S3_REGION'));
}

define('SENDGRID_AUTH_METHOD', 'credentials');
define('SENDGRID_USERNAME', getenv('SENDGRID_USERNAME'));
define('SENDGRID_PASSWORD', getenv('SENDGRID_PASSWORD'));
define('SENDGRID_SEND_METHOD', 'api');

if (!defined('ABSPATH')) {
    define('ABSPATH', dirname(__FILE__).'/store/config/');
} // should not be necessary

require_once ABSPATH.'settings.inc.php';

// installs using a Heroku button do not know the URL, so they use example.com as the site URL, which we need to fix
if (function_exists('get_option') && get_option('siteurl') == 'http://example.herokuapp.com') {
    update_option('siteurl', set_url_scheme($url = 'http://'.$_SERVER['HTTP_HOST']));
    header("Location: $url".$_SERVER['REQUEST_URI']);
    exit;
}
