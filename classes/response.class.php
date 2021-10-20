<?php

/**
 * Description of response
 *
 * @author Paspirgilis
 */
class Response {

    public static function header($set = null, $status = null) {
        if (is_string($set)) {
            $set = trim($set);
            if (!headers_sent()) {
                if (is_int($status)) {
                    header($set, true, $status);
                } else {
                    header($set);
                }
            }
        }
    }

    public static function deliver($content) {
        $current_output = trim(ob_get_clean());
        if (strlen($current_output) > 0) {
            $content = $current_output . $content;
        }
        
        if (ENV != 'dev') {
            self::header('Cache-Control: public');
            self::header('Pragma: cache');
            self::header('Expires: '.gmdate('D, d M Y H:i:s \G\M\T', time() + (HOUR * 6)));
        }

        self::header('Content-length: ' . strlen($content));
        self::header('Content-Type: ' . App::$config['mime'] . '; charset=' . App::$config['encoding'], 200);

        echo $content;
    }

}
