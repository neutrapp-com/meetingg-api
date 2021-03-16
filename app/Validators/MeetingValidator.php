<?php

declare(strict_types=1);


namespace Meetingg\Validators;

use Phalcon\Validation\Validator\Between;
use Phalcon\Validation\Validator\CallBack;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\InclusionIn;
use Phalcon\Validation\Validator\StringLength;

class MeetingValidator extends BaseValidation
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
            'description',
            new PresenceOf(
                [
                    'message' => 'The :field is required',
                ]
            )
        );
        
        $this->add(
            'description',
            new StringLength(
                [
                    'max' => 2000,
                    'min' => 0,
                    'messageMaximum' => 'The :field is too long',
                ]
            )
        );
        
        $this->add(
            'start_at',
            new PresenceOf(
                [
                    'message' => 'The :field is required',
                ]
            )
        );
        
        $this->add(
            'start_at', 
            new Between(
                [
                    'minimum' => time() ,
                    'maximum' => time() + (10000 * 24 * 60 * 60),
                    'message' => 'The :field must be minimum after now',
                ]
            )
        );
        
        $this->add(
            ['end_at', 'start_at'],
            new PresenceOf(
                [
                    'message' => 'The date are required',
                ]
            )
        );
        
        $this->add(
            ['end_at', 'start_at'], 
            new Between(
                [
                    'minimum' => time() ,
                    'maximum' => time() + (10000 * 24 * 60 * 60),
                    'message' => 'The date must be minimum after now',
                ]
            )
        );

        $this->add(
            'end_at', new Callback(
                [
                    'message' => 'The end date must be after start date',
                    'callback' => function ($data) {
                        return $data['end_at'] > $data['start_at'];
                    }
                ]
            )
        );
    }
}
