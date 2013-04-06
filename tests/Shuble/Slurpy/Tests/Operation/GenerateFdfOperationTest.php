<?php

namespace Shuble\Slurpy\Tests\Operation;

use Shuble\Slurpy\Operation\GenerateFdfOperation;

class GenerateFdfOperationTest extends \PHPUnit_Framework_TestCase
{
    public function testGetName()
    {
        $operation = new GenerateFdfOperation();

        $this->assertEquals('generate_fdf', $operation->getName());
    }
}
