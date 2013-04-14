<?php

namespace Shuble\Slurpy\Tests;

use Shuble\Slurpy\InputFile;
use Shuble\Slurpy\Factory;

class FactoryTest extends \PHPUnit_Framework_TestCase
{
    protected $factory;

    protected function setUp()
    {
        $this->factory = new Factory('path/to/binary');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testCatWithInvalidInput()
    {
        $inputs = array(
            array(
                'foo' => 'bar',
                'foobar' => 'baz',
            )
        );

        $this->factory->cat($inputs, 'output');
    }

    public function testCatWithValidInput()
    {
        $output = 'outputfile';
        $inputs = array(
            'path/to/file',
            array(
                'filepath' => 'path/to/file2',
                'password' => 'passw0rd',
            ),
        );

        $slurpy = $this->factory->cat($inputs, $output);

        $this->assertInstanceOf('Shuble\Slurpy\Operation\CatOperation', $slurpy->getOperation());
        $this->assertEquals($output, $slurpy->getOutput());

        $inputFiles = $slurpy->getInputs();

        $input = array_shift($inputFiles);
        $this->assertEquals($inputs[0], $input->getFilePath());
        $this->assertEquals(null, $input->getPassword());

        $input = array_shift($inputFiles);
        $this->assertEquals($inputs[1]['filepath'], $input->getFilePath());
        $this->assertEquals($inputs[1]['password'], $input->getPassword());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testShuffleWithInvalidInput()
    {
        $inputs = array(
            array(
                'foo' => 'bar',
                'foobar' => 'baz',
            )
        );

        $this->factory->shuffle($inputs, 'output');
    }

    public function testShuffleWithValidInput()
    {
        $output = 'outputfile';
        $inputs = array(
            'path/to/file',
            array(
                'filepath' => 'path/to/file2',
                'password' => 'passw0rd',
            ),
        );

        $slurpy = $this->factory->shuffle($inputs, $output);

        $this->assertInstanceOf('Shuble\Slurpy\Operation\ShuffleOperation', $slurpy->getOperation());
        $this->assertEquals($output, $slurpy->getOutput());

        $inputFiles = $slurpy->getInputs();

        $input = array_shift($inputFiles);
        $this->assertEquals($inputs[0], $input->getFilePath());
        $this->assertEquals(null, $input->getPassword());

        $input = array_shift($inputFiles);
        $this->assertEquals($inputs[1]['filepath'], $input->getFilePath());
        $this->assertEquals($inputs[1]['password'], $input->getPassword());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testBackgroundWithInvalidInput()
    {
        $input = array(
            'foo' => 'bar',
            'foobar' => 'baz',
        );

        $this->factory->background($input, 'backgroundfile', 'output');
    }
}
