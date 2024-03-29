<?php
declare(strict_types=1);

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class UserNicknameUniqueness extends Constraint
{
    public $message = 'User with this nickname is already exist';
}