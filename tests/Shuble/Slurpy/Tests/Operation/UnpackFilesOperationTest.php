<?php

namespace Shuble\Slurpy\Tests\Operation;

use Shuble\Slurpy\Operation\UnpackFilesOperation;

class UnpackFilesOperationTest extends \PHPUnit_Framework_TestCase
{
    public function testGetName()
    {
       $operation = new UnpackFilesOperation();

       $this->assertEquals('unpack_files', $operation->getName());
    }
}
