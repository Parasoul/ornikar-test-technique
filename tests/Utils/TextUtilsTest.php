<?php

namespace Test\Utils;

use App\Utils\TextUtils;
use PHPUnit_Framework_TestCase;

class TextUtilsTest extends PHPUnit_Framework_TestCase
{
    public function testStringLoweredAndUCFirst_nominal(): void
    {
        $randomStringOne = 'fdfsfs';
        $randomStringTwo = 'QSSDFFSFDSSDSD';
        $randomStringThree = 'SD DsfqFqDsfs';

        $this->assertEquals('Fdfsfs', TextUtils::getStringLoweredAndUCFirst($randomStringOne));
        $this->assertEquals('Qssdffsfdssdsd', TextUtils::getStringLoweredAndUCFirst($randomStringTwo));
        $this->assertEquals('Sd dsfqfqdsfs', TextUtils::getStringLoweredAndUCFirst($randomStringThree));
    }
}
