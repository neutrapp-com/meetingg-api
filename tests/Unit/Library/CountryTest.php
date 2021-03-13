<?php

declare(strict_types=1);

namespace Tests\Unit\Library;

use Meetingg\Library\Country;
use Tests\Unit\AbstractUnitTest;

class CountryTest extends AbstractUnitTest
{

    /**
     * Test country by code
     *
     * @return void
     */
    public function testCountryByCode() : void
    {
        $this->assertSame([
            "name"=>"France",
            "nativetongue"=> "France"
        ], Country::getCountry("FR"));

        $this->assertSame([
            "name"=>"Morocco",
            "nativetongue"=> "‫المغرب"
        ], Country::getCountry("MA"));
    }

    /**
     * Test all country keys
     *
     * @return void
     */
    public function testAllCountryKeys() : void
    {
        $this->assertIsArray(Country::allKeys());
    }

    /**
     * Test country::all
     *
     * @return void
     */
    public function testAllCountry() : void
    {
        $this->assertIsArray(Country::all());
    }
}
