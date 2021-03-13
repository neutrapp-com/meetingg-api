<?php

declare(strict_types=1);

namespace Meetingg\Validators;

use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\StringLength;

class PasswordValidator extends BaseValidation
{
    public function initialize()
    {
        $this->add(
            'password',
            new PresenceOf(
                [
                    'message' => 'The password is required',
                ]
            )
        );

        $this->add(
            'password',
            new StringLength(
                [
                    'max' => 40,
                    'min' => 5,
                    'messageMaximum' => 'The :field is too long',
                    'messageMinimum' => 'The :field is too short',
                ]
            )
        );
    }
}
