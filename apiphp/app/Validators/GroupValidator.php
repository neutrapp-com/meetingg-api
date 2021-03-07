<?php

declare(strict_types=1);


namespace Meetingg\Validators;

use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\StringLength;

class GroupValidator extends Validation
{
    public function initialize()
    {
        $this->add(
            'title',
            new PresenceOf(
                [
                    'message' => 'The title is required',
                ]
            )
        );
        
        $this->add(
            'title',
            new StringLength(
                [
                    'max' => 40,
                    'min' => 1,
                    'messageMaximum' => 'The :field is too long',
                    'messageMinimum' => 'The :field is too short',
                ]
            )
        );
    }
}
