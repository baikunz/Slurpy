<?php

namespace Shuble\Slurpy\Tests\Operation;

use Shuble\Slurpy\Operation\BackgroundOperation;

class BackgroundOperationTest extends \PHPUnit_Framework_TestCase
{
    public function testGetName()
    {
        $operation = new BackgroundOperation();

        $this->assertEquals('background', $operation->getName(), 'simple background by default');

        $operation->setMulti(true);
        $this->assertEquals('multibackground', $operation->getName());

        $operation->setMulti(false);
        $this->assertEquals('background', $operation->getName());
    }

    /**
     * @dataProvider dataForTestGetSet
     */
    public function testGetSet($property, $value)
    {
        $operation = new BackgroundOperation();

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
            array('backgroundFile', null),
            array('backgroundFile', 'path/to/file'),
        );
    }

    public function testGetArguments()
    {
        $operation = new BackgroundOperation();

        $file = 'path/to/file';
        $operation->setBackgroundFile($file);

        $this->assertEquals(array($file), $operation->getArguments());
    }
}
