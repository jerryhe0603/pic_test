<?php
	
	



define('SYSTEM_NAME','後台系統');

// 這裡要改
define('DB_ADDR','localhost');
define('DB_NAME','map');
define('DB_USER','robot');
define('DB_PASSWORD','robot');

define('PAGE_SIZE','20');
define('PAGE_LIST','10,20,30,40,50,100');


define('MCRYPT','md5');

define('EasyUI_DATA_PATH','SQL/Data/EasyuiSQLData.php');


// define('BOT_ENABLE',true);
define('BOT_ENABLE',false);
define('BOT_PARTNER','Chatisfy');
// define('BOT_NAME','WiCARE_TW');
// define('BOT_NAME','CT-TEST');

// define('IOT_ENABLE',true);
define('IOT_ENABLE',false);
define('IOT_PARTNER','Gorbit');







// print_r($_REQUEST);

$default_lang = 'en';
// $default_lang = 'en';
// putenv('LANG=zh_TW');
// setlocale(LC_ALL, 'zh_TW');

$lang = isset($_REQUEST['lang']) ? $_REQUEST['lang'] : $default_lang;
$lang = isset($_SESSION['lang']) ? $_SESSION['lang'] : $lang;

if ('tw' == $lang) {
    putenv('LANG=zh_TW');
    setlocale(LC_ALL, 'zh_TW'); // bsd use zh_TW.UTF-8
} else if ('cn' == $lang) {
    putenv('LANG=zh_CN');
    setlocale(LC_ALL, 'zh_CN'); // bsd use zh_CN.UTF-8
} else if ('en' == $lang) {
   	putenv('LANG=en_US');
    setlocale(LC_ALL, 'en_US');
}

// echo 'lang:'.$lang;
// echo '   currentLocal:'.$currentLocal;

//用甚麼翻譯檔
define('PACKAGE', 'default');

// gettext setting// or $your_path/locale, ex: /var/www/test/locale
bindtextdomain(PACKAGE, 'locale'); 
bind_textdomain_codeset(PACKAGE, 'UTF-8'); 
textdomain(PACKAGE);


//專門集中記錄錯誤代碼用
$error_code_arr[1] = '帳號或密碼錯誤!';
$error_code_arr[2] = '重複,不允許儲存!';






?>