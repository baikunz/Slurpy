<?php

namespace Shuble\Slurpy\Tests\Operation;

use Shuble\Slurpy\Operation\CatOperation;

class CatOperationTest extends \PHPUnit_Framework_TestCase
{
    public function testGetSetPageRanges()
    {
        $catOperation = new CatOperation();
        $this->assertEquals(
            array(), $catOperation->getPageRanges(),
            '->getPageRanges() returns an empty array after operation is contructed'
        );

        $pageRange = $this->getMock('Shuble\Slurpy\Operation\OperationArgument\PageRange', array(), array(), '', false);

        $catOperation->setPageRanges(array($pageRange));
        $this->assertEquals(
            array($pageRange), $catOperation->getPageRanges(),
            '->setPageRanges() sets specified page ranges'
        );

        $catOperation->setPageRanges(array($pageRange, $pageRange));
        $this->assertEquals(
            array($pageRange, $pageRange), $catOperation->getPageRanges(),
            '->setPageRanges() override existing page ranges'
        );
    }

    public function testAddPageRange()
    {
        $pageRange = $this->getMock('Shuble\Slurpy\Operation\OperationArgument\PageRange', array(), array(), '', false);

        $catOperation = new CatOperation();
        $catOperation->addPageRange($pageRange);

        $this->assertEquals(
            array($pageRange), $catOperation->getPageRanges(),
            '->addPageRange() appends a page range to existing ones'
        );
    }

    public function testGetName()
    {
        $catOperation = new CatOperation();

        $this->assertEquals('cat', $catOperation->getName());
    }

    public function testGetArguments()
    {
        $pageRange = $this->getMock('Shuble\Slurpy\Operation\OperationArgument\PageRange', array('__toString'), array(), '', false);
        $pageRange
            ->expects($this->once())
            ->method('__toString')
            ->will($this->returnValue('arg1'))
        ;

        $catOperation = new CatOperation();
        $catOperation->addPageRange($pageRange);

        $this->assertEquals(array('arg1'), $catOperation->getArguments());
    }
}
