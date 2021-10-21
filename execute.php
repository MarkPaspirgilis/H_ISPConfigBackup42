<?php

ini_set('short_open_tag', 1);
ini_set('magic_quotes_gpc', 1);
ini_set("memory_limit", "512M");

@session_start();

include 'lib/init.php';

$GLOBALS['pagecontent'] = '404 - File not found';
#
$requested = trim(Request::$requested_clean_path);
if(empty($requested)) {
    Utilities::redirect(BASEURL . 'index'); 
}
$content_filename = str_replace('/', '_', empty($requested) ? 'index' : $requested);
$File_content = File::instance_of_first_existing_file(File::_create_try_list($content_filename, array('php', 'html'), 'pages/'));
if($File_content->exists) {
    $GLOBALS['pagecontent'] = $File_content->get_content();
}

include 'templates/base.php';
