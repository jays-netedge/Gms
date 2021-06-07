<?php


namespace App\Manager;


class CommonMgr
{
    public function headerDetails()
    {
        $headers = getallheaders();
        $headers['ip_address'] = request()->ip();
        $headers = array_change_key_case($headers, CASE_UPPER);
        if (!isset($headers['USER-AGENT'])) {
            $headers['USER-AGENT'] = 'Unknown';
        }
        return $headers;
    }

    public static function generateToken($size = 10)
    {
        return md5(rand(1, $size) . microtime());
    }

    public function token()
    {
        //Generate a random string.
        $token = openssl_random_pseudo_bytes(64);
        //Convert the binary data into hexadecimal representation.
        return bin2hex($token);
    }
}
