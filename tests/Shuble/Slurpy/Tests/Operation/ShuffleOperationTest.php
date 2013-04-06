<?php

namespace Shuble\Slurpy\Tests\Operation;

use Shuble\Slurpy\Operation\ShuffleOperation;

class ShuffleOperationTest extends \PHPUnit_Framework_TestCase
{
    public function testGetSetPageRanges()
    {
        $operation = new ShuffleOperation();
        $this->assertEquals(
            array(), $operation->getPageRanges(),
            '->getPageRanges() returns an empty array after operation is contructed'
        );

        $pageRange = $this->getMock('Shuble\Slurpy\Operation\OperationArgument\PageRange', array(), array(), '', false);

        $operation->setPageRanges(array($pageRange));
        $this->assertEquals(
            array($pageRange), $operation->getPageRanges(),
            '->setPageRanges() sets specified page ranges'
        );

        $operation->setPageRanges(array($pageRange, $pageRange));
        $this->assertEquals(
            array($pageRange, $pageRange), $operation->getPageRanges(),
            '->setPageRanges() override existing page ranges'
        );
    }

    public function testAddPageRange()
    {
        $pageRange = $this->getMock('Shuble\Slurpy\Operation\OperationArgument\PageRange', array(), array(), '', false);

        $operation = new ShuffleOperation();
        $operation->addPageRange($pageRange);

        $this->assertEquals(
            array($pageRange), $operation->getPageRanges(),
            '->addPageRange() appends a page range to existing ones'
        );
    }

    public function testGetName()
    {
        $operation = new ShuffleOperation();

        $this->assertEquals('shuffle', $operation->getName());
    }

    public function testGetArguments()
    {
        $pageRange = $this->getMock('Shuble\Slurpy\Operation\OperationArgument\PageRange', array('__toString'), array(), '', false);
        $pageRange
            ->expects($this->once())
            ->method('__toString')
            ->will($this->returnValue('arg1'))
        ;

        $operation = new ShuffleOperation();
        $operation->addPageRange($pageRange);

        $this->assertEquals(array('arg1'), $operation->getArguments());
    }
}
