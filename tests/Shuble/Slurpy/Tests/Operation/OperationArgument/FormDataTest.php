<?php

namespace Shuble\Slurpy\Tests\Operation\OperationArgument;

use Shuble\Slurpy\Operation\OperationArgument\FormData;

class FormDataTest extends \PHPUnit_Framework_TestCase
{
    protected $formData;

    public function setUp()
    {
        $this->formData = new FormData();
    }

    public function testCreation()
    {
        $this->assertEquals(array(), $this->formData->getFields());
    }

    public function testAddGetSetField()
    {
        $fieldName = 'field';
        $fieldValue = 'value';

        $message = '->getField throw exception if the field does not exist';
        try {
            $this->formData->getField($fieldName);
            $this->fail($message);
        } catch (\InvalidArgumentException $e) {
            $this->anything($message);
        }

        $message = '->setField throw exception if the field does not exist';
        try {
            $this->formData->setField($fieldName, $fieldValue);
            $this->fail($message);
        } catch (\InvalidArgumentException $e) {
            $this->anything($message);
        }

        $this->formData->addField($fieldName, $fieldValue);
        $this->assertEquals($fieldValue, $this->formData->getField($fieldName));

        $message = '->addField throw exception if the field already exist';
        try {
            $this->formData->addField($fieldName, $fieldValue);
            $this->fail($message);
        } catch (\InvalidArgumentException $e) {
            $this->anything($message);
        }

        $this->formData->setField($fieldName, 'value2');
        $this->assertEquals('value2', $this->formData->getField($fieldName));
    }

    public function testAddGetSetFields()
    {
        $fields = array(
            'field1' => 'value1',
            'field2' => 'value2',
        );

        $message = '->setFields throw exception if any of the fields does not exist';
        try {
            $this->formData->setFields($fields);
            $this->fail($message);
        } catch (\InvalidArgumentException $e) {
            $this->anything($message);
        }

        $this->formData->addFields($fields);
        $this->assertEquals($fields, $this->formData->getFields($fields));

        $message = '->addFields throw exception if any of the fields already exist';
        try {
            $this->formData->addFields($fields);
            $this->fail($message);
        } catch (\InvalidArgumentException $e) {
            $this->anything($message);
        }

        $this->formData->setFields($fields);
        $this->assertEquals($fields, $this->formData->getFields($fields));
    }

    public function testGetXfdf()
    {
        $fields = array(
            'field1' => 'value1',
            'field2' => 'value2',
        );
        $this->formData->addFields($fields);

        $modified = time();
        $file = '/path/to/file.pdf';
        $original = md5($file);
        $encoding = 'UTF-8';
        $expected =
<<<XML
<?xml version="1.0" encoding="{$encoding}" ?>
<xfdf xmlns="http://ns.adobe.com/xfdf/" xml:space="preserve">
    <fields>
        <field name="field1"><value>value1</value></field><field name="field2"><value>value2</value></field>
    </fields>
    <ids original="{$original}" modified="{$modified}" />
    <f href="{$file}" />
</xfdf>
XML;

        $actual = $this->formData->getXfdf($file, $modified, $encoding);

        $this->assertXmlStringEqualsXmlString($expected, $actual);
    }

}
