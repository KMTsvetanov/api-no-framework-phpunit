<?php


namespace App\Authorization;


use Firebase\JWT\JWT;
use Firebase\JWT\SignatureInvalidException;

/**
 * Class JWTAuthorization
 * @package App\Authorization
 */
class JWTAuthorization
{
    /**
     * @param false $jwtRequired
     */
    public static function authorizeCheck($jwtRequired = false)
    {
        if ($jwtRequired) {
            if (!isset($_SERVER['HTTP_AUTHORIZATION'])) {
                throw new SignatureInvalidException('JWTs Credentials required!');
            }
            list($jwt) = sscanf($_SERVER['HTTP_AUTHORIZATION'], 'Bearer %s');
            if (!$jwt) {
                throw new SignatureInvalidException('JWTs Credentials required!');
            }

            JWT::decode($jwt, 'secret', array('HS256'));
        }
    }
}