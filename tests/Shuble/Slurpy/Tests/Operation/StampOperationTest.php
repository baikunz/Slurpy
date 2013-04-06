<?php

namespace Shuble\Slurpy\Tests\Operation;

use Shuble\Slurpy\Operation\StampOperation;

class StampOperationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider dataForTestGetSet
     */
    public function testGetSet($property, $value)
    {
        $operation = new StampOperation();

        $setter = sprintf('set%s', ucfirst($property));
        $getter = sprintf('get%s', ucfirst($property));

        $operation->$setter($value);

        $this->assertEquals($value, $operation->$getter());
    }

    public function dataForTestGetSet()
    {
        return array(
            array('multi', true),
            array('multi', false),
            array('stampFile', 'path/to/file'),
            array('stampFile', null),
        );
    }

    public function testGetName()
    {
        $operation = new StampOperation();

        $this->assertEquals('stamp', $operation->getName(), 'simple stamp operation by default');

        $operation->setMulti(true);
        $this->assertEquals('multistamp', $operation->getName());

        $operation->setMulti(false);
        $this->assertEquals('stamp', $operation->getName());
    }

    public function testGetArguments()
    {
        $operation = new StampOperation();

        $file = 'path/to/file';
        $operation->setStampFile($file);

        $this->assertEquals(array($file), $operation->getArguments());
    }
}
