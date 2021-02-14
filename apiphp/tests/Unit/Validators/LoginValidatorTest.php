<?php

declare(strict_types=1);

namespace Tests\Unit\Validators;

use Phalcon\Messages\Messages;

use Tests\Unit\AbstractUnitTest;

use Meetingg\Validators\LoginValidator;

class LoginValidatorTest extends AbstractUnitTest
{
    /**
     * @dataProvider validationProvider
     */
    public function testExampleValidation(array $data, int $totalErrors = 0) : void
    {
        $validator = new LoginValidator();

        $errors = $validator->validate($data);
    
        $this->assertTrue($errors instanceof Messages);
        $this->assertSame($totalErrors, $errors->count());
    }

    public static function validationProvider() : array
    {
        return [
            [[], 4],
            [['email'=>'','password'=>''], 4],
            [['email'=>'invalidemail','password'=>''], 3],
            [['email'=>'test@gmail.com','password'=>''], 2],
            [['email'=>'test@gmail.com','password'=>'password'], 0],
        ];
    }
}
