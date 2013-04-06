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
    }
}
