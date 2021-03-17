<?php

declare(strict_types=1);


namespace Meetingg\Validators;

use Phalcon\Validation\Validator\Between;
use Phalcon\Validation\Validator\CallBack;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Regex;
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
            new StringLength(
                [
                    'max' => 2000,
                    'min' => 0,
                    'messageMaximum' => 'The :field is too long',
                    'allowEmpty'=> true
                ]
            )
        );
        
        $this->add(
            'start_at',
            new PresenceOf(
                [
                    'message' => 'The Start date is required',
                ]
            )
        );

        $this->add(
            'end_at',
            new PresenceOf(
                [
                    'message' => 'The End date is required',
                ]
            )
        );
        
        $this->add(
            'start_at',
            new Between(
                [
                    'minimum' => time() ,
                    'maximum' => time() + (10000 * 24 * 60 * 60),
                    'message' => 'The Start Date must be minimum after now',
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
            ['end_at' , 'start_at'],
            new Callback(
                [
                    'message' => 'The end date must be after start date',
                    'callback' => function ($data) {
                        return !empty($data['end_at'])  && !empty($data['start_at']) && $data['end_at'] > $data['start_at'];
                    }
                ]
            )
        );

        $this->add(
            'participants',
            new Regex([
                'pattern' => "/^([0-9a-z]{8}-[0-9a-z]{4}-[0-9a-z]{4}-[0-9a-z]{4}-[0-9a-z]{12})+(?:,([0-9a-z]{8}-[0-9a-z]{4}-[0-9a-z]{4}-[0-9a-z]{4}-[0-9a-z]{12})+)*$/",
                'message' => 'The :field list is invalid',
                'allowEmpty' => true
            ])
        );
    }
}
