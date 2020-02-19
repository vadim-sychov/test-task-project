<?php
declare(strict_types=1);

namespace App\Validator;

use App\Repository\UserRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Check that passed value is unique user nickname
 */
class UserNicknameUniquenessValidator extends ConstraintValidator
{
    /** @var UserRepository */
    private $userRepository;

    /**
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param mixed $value
     * @param Constraint|UserNicknameUniqueness $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        if (empty($value)) {
            return;
        }

        $user = $this->userRepository->findBy('nickname', $value);

        if (!is_null($user)) {
            $this->context->buildViolation($constraint->message)->addViolation();
        }
    }
}
