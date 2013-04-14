<?php

namespace Shuble\Slurpy\Tests\Operation;

use Shuble\Slurpy\Operation\FillFormOperation;

class FillFormOperationTest extends \PHPUnit_Framework_TestCase
{
    public function testGetSetDataFile()
    {
        $operation = new FillFormOperation();

        $file = 'path/to/file';
        $operation->setDataFile($file);

        $this->assertEquals($file, $operation->getDataFile());

        $dataFile = $this->getMock('Shuble\Slurpy\Operation\OperationArgument\FormData');
        $operation->setDataFile($dataFile);

        $this->assertEquals($dataFile, $operation->getDataFile());
    }

    public function testGetName()
    {
        $operation = new FillFormOperation();

        $this->assertEquals('fill_form', $operation->getName());
    }

    public function testGetArguments()
    {
        $operation = new FillFormOperation();

        $file = 'path/to/file';
        $operation->setDataFile($file);

        $this->assertEquals(array($file), $operation->getArguments());

        $dataFile = $this->getMock('Shuble\Slurpy\Operation\OperationArgument\FormData');
        $operation->setDataFile($dataFile);

        $this->assertEquals(array('-'), $operation->getArguments());
    }

    public function testGetStdin()
    {
        $operation = new FillFormOperation();

        $file = 'path/to/file';
        $operation->setDataFile($file);

        $this->assertEquals(null, $operation->getStdin());

        $dataFile = $this->getMock('Shuble\Slurpy\Operation\OperationArgument\FormData');
        $dataFile
            ->expects($this->once())
            ->method('getXfdf')
            ->will($this->returnValue('foobar'))
        ;

        $operation->setDataFile($dataFile);

        $this->assertEquals('foobar', $operation->getStdin());
    }
}
