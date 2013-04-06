<?php

namespace Shuble\Slurpy\Tests\Operation;

use Shuble\Slurpy\Operation\UpdateInfoOperation;

class UpdateInfoOperationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider dataForTestGetSet
     */
    public function testGetSet($property, $value)
    {
        $operation = new UpdateInfoOperation();

        $setter = sprintf('set%s', ucfirst($property));
        $getter = sprintf('get%s', ucfirst($property));

        $operation->$setter($value);

        $this->assertEquals($value, $operation->$getter());
    }

    public function dataForTestGetSet()
    {
        return array(
            array('dataFile', 'path/to/file'),
            array('dataFile', null),
            array('utf8', true),
            array('utf8', false),
        );
    }

    public function testGetName()
    {
        $operation = new UpdateInfoOperation();

        $this->assertEquals('update_info', $operation->getName(), 'simple update info operation by default');

        $operation->setUtf8(true);
        $this->assertEquals('update_info_utf8', $operation->getName());

        $operation->setUtf8(false);
        $this->assertEquals('update_info', $operation->getName());
    }

    public function testGetArguments()
    {
        $operation = new UpdateInfoOperation();

        $file = 'path/to/file';
        $operation->setDataFile($file);

        $this->assertEquals(array($file), $operation->getArguments());
    }
}
