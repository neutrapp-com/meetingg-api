<?php

declare(strict_types=1);

namespace Tests\Unit\Validators;

use Phalcon\Messages\Messages;

use Tests\Unit\AbstractUnitTest;

use Meetingg\Validators\RegisterValidator;

class RegisterValidatorTest extends AbstractUnitTest
{
    /**
     * @dataProvider validationProvider
     */
    public function testExampleValidation(array $data, int $totalErrors = 0) : void
    {
        $validator = new RegisterValidator();

        $errors = $validator->validate($data);
    
        $this->assertTrue($errors instanceof Messages);
        $this->assertSame($totalErrors, $errors->count());
    }

    public static function validationProvider() : array
    {
        return [
            [[], 9],
            [['email'=>'','password'=>''], 7],
            [['email'=>'invalideemail','password'=>''], 6],
            [['email'=>'test@gmail.com','password'=>''], 5],
            [['email'=>'test@gmail.com','password'=>'password'], 4],
            [['email'=>'test@gmail.com','password'=>'password','country'=>'invalide','city'=>''], 4],
            [['email'=>'test@gmail.com','password'=>'password','country'=>'FR','city'=>''], 3],
            [['email'=>'test@gmail.com','password'=>'password','country'=>'FR','city'=>'Paris'], 3],
        ];
    }
}
