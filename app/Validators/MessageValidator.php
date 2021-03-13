<?php

declare(strict_types=1);


namespace Meetingg\Validators;

use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\StringLength;

class MessageValidator extends BaseValidation
{
    public function initialize()
    {
        $this->add(
            'content',
            new PresenceOf(
                [
                    'message' => 'The message must not be empty',
                ]
            )
        );
        
        $this->add(
            'content',
            new StringLength(
                [
                    'max' => 1000,
                    'min' => 1,
                    'messageMaximum' => 'The message is too long',
                    'messageMinimum' => 'The message is too short',
                ]
            )
        );
    }
}
