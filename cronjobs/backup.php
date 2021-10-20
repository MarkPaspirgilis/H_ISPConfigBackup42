<?php

define('CHOWN', 'web2:client1');
define('CHMOD', '775');
define('URL_HASH', '?' . time());
define('DIR_BACKUP', '/backup/');
define('DB_NOFILES', 'private/backup_nofiles');
define('DB_DATABASES', 'private/backup_db');
define('DB_PW', base64_decode(base64_decode('WVRKVGN5UTNRbVE9')));
define('SERVER_NAME', 'web13');

####

if (!is_dir(DIR_BACKUP)) {
    exec('mkdir ' . DIR_BACKUP);
}

$dir_root = '/var/www/clients/';
foreach (scandir($dir_root) as $client) {
    $dir_client = $dir_root . $client . '/';
    if (!in_array($client, array('.', '..'))) {
        foreach (scandir($dir_client) as $web) {
            $dir_web = $dir_client . $web . '/';
            $CHOWN = $web . ':' . $client;
            if (!in_array($web, array('.', '..')) && substr($web, 0, 3) == 'web' && !strstr($web, '.wkgisp.de')) {
                $dir_backup = $dir_web . 'backup/';
                $filepath_no_files_config = $dir_web . DB_NOFILES;
                $backup_files = !is_file($filepath_no_files_config);
                if (!is_dir($dir_backup)) {
                    exec('chattr -i ' . $dir_web);
                    exec('mkdir ' . $dir_backup);
                    exec('chown -R ' . $CHOWN . ' ' . $dir_backup);
                    exec('chmod -R ' . CHMOD . ' ' . $dir_backup);
                    exec('chattr +i ' . $dir_web);
                }
                rm_all_but_git($dir_backup);

                $dir_git = $dir_backup . '.git/';
                $dir_backup_intern = DIR_BACKUP . $client . '-' . $web . '/';
                if (!is_dir($dir_backup_intern))
                    exec('mkdir ' . $dir_backup_intern);
                $dir_backup_files = $dir_backup . 'files/';
                if (!is_dir($dir_backup_files) && $backup_files)
                    exec('mkdir ' . $dir_backup_files);
                $dir_backup_db = $dir_backup . 'db/';
                if (!is_dir($dir_backup_db))
                    exec('mkdir ' . $dir_backup_db);

                //Backup Files
                if ($backup_files) {
                    usleep(5000);
                    exec('cp -R ' . $dir_web . 'web/* ' . $dir_backup_files);
                }

                //Backup Database
                $databases = array();
                if (is_file($dir_web . DB_DATABASES)) {
                    $databases_plain = trim(file_get_contents($dir_web . DB_DATABASES));
                    if (!empty($databases_plain)) {
                        $databases = explode("\n", $databases_plain);
                        array_walk($databases, 'trim');
                    }
                }
                if (empty($databases)) {
                    file_put_contents($dir_backup_db . '.gitkeep', '');
                } else {
                    foreach ($databases as $database_name) {
                        $dump_filename = $dir_backup_db . 'database__' . SERVER_NAME . '__' . $database_name . '.sql';
                        $dump_command = 'mysqldump --default-character-set=utf8mb4 -u root -p\'' . DB_PW . '\' ' . $database_name . ' > ' . $dump_filename;
                        exec($dump_command);
                        sleep(0.2);
                    }
                }

                //Timestamp + AccessRights
                file_put_contents($dir_backup . 'backup_timestamp', time());
                exec('chown -R ' . $CHOWN . ' ' . $dir_backup);
                exec('chmod -R ' . CHMOD . ' ' . $dir_backup);

                //Save via GIT
                if (!is_dir($dir_backup_intern) || !is_dir($dir_backup_intern . '.git')) {
                    chdir($dir_backup_intern);
                    exec('cd ' . $dir_backup_intern . ' && git init && git config receive.denyCurrentBranch ignore && cd ' . $dir_backup);
                    usleep(50000);
                }
                chdir($dir_backup);
                exec('cd ' . $dir_backup);
                if (!is_dir($dir_git)) {
                    exec('git init');
                    sleep(0.5);
                    exec('git remote add backup-intern ' . $dir_backup_intern);
                    usleep(5000);
                    exec('git add -f . && git commit -am "Backup Initiation"');
                    usleep(5000);
                    exec('git push --set-upstream backup-intern master');
                } else {
                    exec('git add -f . && git commit -am "Backup ' . date('Y-m-d H:i') . '"');
                    usleep(5000);
                    exec('git push backup-intern master');
                }
                usleep(5000);
                chdir($dir_backup_intern);
                exec('cd ' . $dir_backup_intern . ' && git checkout master .');
            }
        }
    }
}

function rm_all_but_git($dir) {
    $dir = trim($dir);
    if (substr($dir, -1) != '/') {
        $dir .= '/';
    }
    if (is_dir($dir)) {
        foreach (scandir($dir) as $dir_item) {
            if (!in_array($dir_item, array('.', '..', '.git'))) {
                exec('rm -rf ' . $dir . $dir_item);
            }
        }
    }
    usleep(5000);
}
