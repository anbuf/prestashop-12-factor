<?php
define('_DB_SERVER_', getenv('PRESTASHOP_DATABASE_SERVER'));
define('_DB_NAME_', getenv('PRESTASHOP_DATABASE_NAME'));
define('_DB_USER_', getenv('PRESTASHOP_DATABASE_USER'));
define('_DB_PASSWD_', getenv('PRESTASHOP_DATABASE_PASSWORD'));
define('_DB_PREFIX_', 'ps_');
define('_MYSQL_ENGINE_', 'InnoDB');
define('_PS_CACHING_SYSTEM_', 'CacheMemcache');
define('_PS_CACHE_ENABLED_', '0');
define('_COOKIE_KEY_', 'v9Y4d3dwg7nGpLrmp6vSzwjcA5YacUDXb9prcZKWa1YLLWRe5CLyMz8t');
define('_COOKIE_IV_', '1KKy5MsS');
define('_PS_CREATION_DATE_', '2016-07-04');
if (!defined('_PS_VERSION_'))
	define('_PS_VERSION_', '1.6.1.4');
define('_RIJNDAEL_KEY_', '0t2jUwfqIEvB3QuPYnwadN8UiiIfOvOP');
define('_RIJNDAEL_IV_', 'QyODUKGrMbw0lok8vsAd8A==');
