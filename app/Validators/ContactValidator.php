<?php

declare(strict_types=1);


namespace Meetingg\Validators;

use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\InclusionIn;
use Phalcon\Validation\Validator\StringLength;

use Meetingg\Models\User;

class ContactValidator extends BaseValidation
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
        
        $this->add(
            'starred',
            new InclusionIn(
                [
                    'domain' => [
                        self::ACTIVE,
                        self::INACTIVE,
                    ],
                    'message' => 'The :field must be Active/Inactive',
                    'allowEmpty'=>true
                ]
            )
        );
        
        $this->add(
            'blocked',
            new InclusionIn(
                [
                    'domain' => [
                        self::ACTIVE,
                        self::INACTIVE,
                    ],
                    'message' => 'The :field must be Active/Inactive',
                    'allowEmpty'=>true
                ]
            )
        );
    }
}
