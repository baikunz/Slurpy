<?php

namespace Knp\Slurpy;

use Shuble\Slurpy\Operation\PageRange;

class PageRangeTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateWithValidHandle()
    {
        $handle = 'FOOBAR';

        $pageRange = new PageRange($handle);

        $this->assertEquals($handle, (string) $pageRange);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testCreateWithInvalidHandle()
    {
        $handle = 'foobar';

        $pageRange = new PageRange($handle);
    }

    /**
     * @dataProvider dataForInvalidStartPageEndPage
     * @expectedException InvalidArgumentException
     */
    public function testSetInvalidStartPage($fileHandle, $startPage)
    {
        $pageRange = new PageRange($fileHandle);
        $pageRange->setStartPage($startPage);
    }

    /**
     * @dataProvider dataForInvalidStartPageEndPage
     * @expectedException InvalidArgumentException
     */
    public function testSetInvalidEndPage($fileHandle, $endPage)
    {
        $pageRange = new PageRange($fileHandle);
        $pageRange->setEndPage($endPage);
    }

    public function dataForInvalidStartPageEndPage()
    {
        $fileHandle = 'A';

        return array(
            array($fileHandle, '01'),
            array($fileHandle, 'r01'),
            array($fileHandle, 'start'),
        );
    }

    /**
     * @dataProvider dataForValidPageRange
     */
    public function testValidPageRange($fileHandle, $startPage, $endPage, $qualifier, $rotation, $expected)
    {
        $pageRange = new PageRange($fileHandle);
        $pageRange
            ->setStartPage($startPage)
            ->setEndPage($endPage)
            ->setQualifier($qualifier)
            ->setRotation($rotation)
        ;

        $this->assertEquals($expected, (string) $pageRange);
    }

    public function dataForValidPageRange()
    {
        $fileHandle = 'A';

        return array(
            array($fileHandle, 1, null, null, null, $fileHandle. '1'),
            array($fileHandle, 'end', null, null, null, $fileHandle. 'end'),
            array($fileHandle, 'rend', null, null, null, $fileHandle. 'rend'),
            array($fileHandle, 'r1', null, null, null, $fileHandle. 'r1'),

            array($fileHandle, 1, null, null, PageRange::ROTATION_NORTH, $fileHandle. '1north'),
            array($fileHandle, 'end', null, null, PageRange::ROTATION_NORTH, $fileHandle. 'endnorth'),
            array($fileHandle, 'rend', null, null, PageRange::ROTATION_NORTH, $fileHandle. 'rendnorth'),
            array($fileHandle, 'r1', null, null, PageRange::ROTATION_NORTH, $fileHandle. 'r1north'),

            array($fileHandle, 1, 2, null, null, $fileHandle. '1-2'),
            array($fileHandle, 1, 'end', null, null, $fileHandle. '1-end'),
            array($fileHandle, 'rend', 'end', null, null, $fileHandle. 'rend-end'),
            array($fileHandle, 'r3', 'r1', null, null, $fileHandle. 'r3-r1'),
            array($fileHandle, 'r3', 'end', null, null, $fileHandle. 'r3-end'),

            array($fileHandle, 1, 2, null, PageRange::ROTATION_NORTH, $fileHandle. '1-2north'),
            array($fileHandle, 1, 'end', null, PageRange::ROTATION_NORTH, $fileHandle. '1-endnorth'),
            array($fileHandle, 'rend', 'end', null, PageRange::ROTATION_NORTH, $fileHandle. 'rend-endnorth'),
            array($fileHandle, 'r3', 'r1', null, PageRange::ROTATION_NORTH, $fileHandle. 'r3-r1north'),
            array($fileHandle, 'r3', 'end', null, PageRange::ROTATION_NORTH, $fileHandle. 'r3-endnorth'),
        );
    }
}
