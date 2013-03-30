<?php

namespace Shuble\Slurpy\Tests;

use Shuble\Slurpy\InputFile;

class InputFileTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider dataForGetSet
     */
    public function testGetSet($property, $value)
    {
        $inputFile = new InputFile();

        $setter = sprintf('set%s', ucfirst($property));
        $getter = sprintf('get%s', ucfirst($property));

        $inputFile->$setter($value);

        $this->assertEquals($value, $inputFile->$getter());
    }

    public function dataForGetSet()
    {
        return array(
            array('filePath', '/path/to/file'),
            array('handle', 'HANDLE'),
            array('password', 'pa$$w0rd'),
            array('password', null),
        );
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testSetInvalidHandle()
    {
        $inputFile = new InputFile();
        $inputFile->setHandle('foo');
    }
}
