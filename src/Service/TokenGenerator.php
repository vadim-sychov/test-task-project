<?php
declare(strict_types=1);

namespace App\Service;

/**
 * This service is used for generation random token
 * and keep this logic in single place
 */
class TokenGenerator
{
    /**
     * @return string
     */
    public function generateToken(): string
    {
        return md5((string) mt_rand(0, 999999));
    }
}