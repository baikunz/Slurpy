<?php

namespace Shuble\Slurpy\Tests\Operation;

use Shuble\Slurpy\Operation\DumpDataOperation;

class DumpDataOperationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider dataForTestGetSet
     */
    public function testGetSet($property, $value)
    {
        $operation = new DumpDataOperation();

        $setter = sprintf('set%s', ucfirst($property));
        $getter = sprintf('get%s', ucfirst($property));

        $operation->$setter($value);

        $this->assertEquals($value, $operation->$getter());
    }

    public function dataForTestGetSet()
    {
        return array(
            array('fields', false),
            array('fields', true),
            array('utf8', false),
            array('utf8', true),
        );
    }

    public function testGetName()
    {
        $operation = new DumpDataOperation();

        $this->assertEquals('dump_data', $operation->getName(), 'Dump data by default');

        $operation->setFields(true);
        $this->assertEquals('dump_data_fields', $operation->getName());
        $operation->setFields(false);

        $operation->setUtf8(true);
        $this->assertEquals('dump_data_utf8', $operation->getName());

        $operation->setFields(true);
        $this->assertEquals('dump_data_fields_utf8', $operation->getName());
    }
}
