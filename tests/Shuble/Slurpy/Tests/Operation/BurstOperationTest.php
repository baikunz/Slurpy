<?php

namespace Shuble\Slurpy\Tests\Operation;

use Shuble\Slurpy\Operation\BurstOperation;

class BurstOperationTest extends \PHPUnit_Framework_TestCase
{
    public function testGetName()
    {
        $operation = new BurstOperation();

        $this->assertEquals('burst', $operation->getName());
    }

    public function testGetArguments()
    {
        $operation = new BurstOperation();

        $this->assertEquals(array(), $operation->getArguments());
    }
}
