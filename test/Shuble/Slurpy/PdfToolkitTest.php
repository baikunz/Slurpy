<?php

namespace Shuble\Slurpy\PdfToolkit;

use Shuble\Slurpy\Operation\OperationInterface;
use Shuble\Slurpy\Operation\OperationNoop;
use Shuble\Slurpy\InputFile;
use Shuble\Slurpy\PdfToolkit;

class PdfToolkitTest extends \PHPUnit_Framework_TestCase
{
    public function testAddOption()
    {
        $pdfTk = $this->getMock('Shuble\Slurpy\PdfToolkit', null, array(''), '', false);

        $this->assertEquals(array(), $pdfTk->getOptions());

        $r = new \ReflectionMethod($pdfTk, 'addOption');
        $r->setAccessible(true);
        $r->invokeArgs($pdfTk, array('foo', 'bar'));

        $this->assertEquals(array('foo' => 'bar'), $pdfTk->getOptions(), '->addOption() adds an option');

        $r->invokeArgs($pdfTk, array('baz', 'bat'));

        $this->assertEquals(
            array(
                'foo' => 'bar',
                'baz' => 'bat'
            ),
            $pdfTk->getOptions(),
            '->addOption() appends the option to the existing ones'
        );

        $message = '->addOption() raises an exception when the specified option already exists';
        try {
            $r->invokeArgs($pdfTk, array('baz', 'bat'));
            $this->fail($message);
        } catch (\InvalidArgumentException $e) {
            $this->anything($message);
        }
    }

    public function testAddOptions()
    {
        $pdfTk = $this->getMock('Shuble\Slurpy\PdfToolkit', null,array(''), '', false);

        $this->assertEquals(array(), $pdfTk->getOptions());

        $r = new \ReflectionMethod($pdfTk, 'addOptions');
        $r->setAccessible(true);
        $r->invokeArgs($pdfTk, array(array('foo' => 'bar', 'baz' => 'bat')));

        $this->assertEquals(
            array(
                'foo' => 'bar',
                'baz' => 'bat'
            ),
            $pdfTk->getOptions(),
            '->addOptions() adds all the given options'
        );

        $r->invokeArgs($pdfTk, array(array('ban' => 'bag', 'bal' => 'bac')));

        $this->assertEquals(
            array(
                'foo' => 'bar',
                'baz' => 'bat',
                'ban' => 'bag',
                'bal' => 'bac'
            ),
            $pdfTk->getOptions(),
            '->addOptions() adds the given options to the existing ones'
        );

        $message = '->addOptions() raises an exception when one of the given options already exists';
        try {
            $r->invokeArgs($pdfTk, array(array('bak' => 'bam', 'bah' => 'bap', 'baz' => 'bat')));
            $this->fail($message);
        } catch (\InvalidArgumentException $e) {
            $this->anything($message);
        }
    }

    public function testSetOption()
    {
        $pdfTk = $this->getMock('Shuble\Slurpy\PdfToolkit', null,array(''), '', false);

        $r = new \ReflectionMethod($pdfTk, 'addOption');
        $r->setAccessible(true);
        $r->invokeArgs($pdfTk, array('foo', 'bar'));

        $pdfTk->setOption('foo', 'abc');

        $this->assertEquals(
            array(
                'foo' => 'abc'
            ),
            $pdfTk->getOptions(),
            '->setOption() defines the value of an option'
        );

        $message = '->setOption() raises an exception when the specified option does not exist';
        try {
            $pdfTk->setOption('bad', 'def');
            $this->fail($message);
        } catch (\InvalidArgumentException $e) {
            $this->anything($message);
        }
    }

    public function testSetOptions()
    {
        $pdfTk = $this->getMock('Shuble\Slurpy\PdfToolkit', null, array(''), '', false);

        $r = new \ReflectionMethod($pdfTk, 'addOptions');
        $r->setAccessible(true);
        $r->invokeArgs($pdfTk, array(array('foo' => 'bar', 'baz' => 'bat')));

        $pdfTk->setOptions(array('foo' => 'abc', 'baz' => 'def'));

        $this->assertEquals(
            array(
                'foo'   => 'abc',
                'baz'   => 'def'
            ),
            $pdfTk->getOptions(),
            '->setOptions() defines the values of all the specified options'
        );

        $message = '->setOptions() raises an exception when one of the specified options does not exist';
        try {
            $pdfTk->setOptions(array('foo' => 'abc', 'baz' => 'def', 'bad' => 'ghi'));
            $this->fail($message);
        } catch (\InvalidArgumentException $e) {
            $this->anything($message);
        }
    }

    /**
     * @dataProvider dataForGetSet
     */
    public function testGetSet($property, $value)
    {
        $pdfTk = new PdfToolkit('');

        $setter = sprintf('set%s', ucfirst($property));
        $getter = sprintf('get%s', ucfirst($property));

        $pdfTk->$setter($value);

        $this->assertEquals($value, $pdfTk->$getter());
    }

    public function dataForGetSet()
    {
        return array(
            array('binary', 'foo'),
            array('inputs', array()),
            array('inputs', array($this->getMock('Shuble\Slurpy\InputFile'))),
            array('operation', $this->getMock('Shuble\Slurpy\Operation\OperationInterface')),
            array('output', 'foo'),
        );
    }

    public function testMergeOptions()
    {
        $pdfTk = $this->getMock('Shuble\Slurpy\PdfToolkit', null, array(''), '', false);

        $originalOptions = array('foo' => 'bar', 'baz' => 'bat');

        $addOptions = new \ReflectionMethod($pdfTk, 'addOptions');
        $addOptions->setAccessible(true);
        $addOptions->invokeArgs($pdfTk, array($originalOptions));

        $r = new \ReflectionMethod($pdfTk, 'mergeOptions');
        $r->setAccessible(true);

        $mergedOptions = $r->invokeArgs($pdfTk, array(array('foo' => 'ban')));

        $this->assertEquals(
            array(
                'foo' => 'ban',
                'baz' => 'bat'
            ),
            $mergedOptions,
            '->mergeOptions() merges an option to the instance ones and returns the result options array'
        );

        $this->assertEquals(
            $originalOptions,
            $pdfTk->getOptions(),
            '->mergeOptions() does NOT change the instance options'
        );

        $mergedOptions = $r->invokeArgs($pdfTk, array(array('foo' => 'ban', 'baz' => 'bag')));

        $this->assertEquals(
            array(
                'foo' => 'ban',
                'baz' => 'bag'
            ),
            $mergedOptions,
            '->mergeOptions() merges many options to the instance ones and returns the result options array'
        );

        $message = '->mergeOptions() throws an InvalidArgumentException once there is an undefined option in the given array';
        try {
            $r->invokeArgs($pdfTk, array(array('foo' => 'ban', 'bad' => 'bah')));
            $this->fail($message);
        } catch (\InvalidArgumentException $e) {
            $this->anything($message);
        }
    }

    /**
     * @dataProvider dataForBuildCommand
     */
    public function testBuildCommand($binary, $inputs, $output, $operation, $options, $expected)
    {
        $pdfTk = $this->getMock('Shuble\Slurpy\PdfToolkit', array('mergeOptions'), array(), '', false);

        $pdfTk
            ->setBinary($binary)
            ->setInputs($inputs)
            ->setOutput($output)
            ->setOperation($operation)
        ;

        $r = new \ReflectionMethod($pdfTk, 'buildCommand');
        $r->setAccessible(true);

        $this->assertEquals($expected, $r->invokeArgs($pdfTk, array($options)));
    }

    public function dataForBuildCommand()
    {
        $op = $this->getMock('Shuble\Slurpy\Operation\OperationInterface');
        $op
            ->expects($this->any())
            ->method('getName')
            ->will($this->returnValue('operation'))
        ;

        $op
            ->expects($this->any())
            ->method('getArguments')
            ->will($this->returnValue(array('arg1', 'arg2')))
        ;

        return array(
            array(
                'thebinary',
                array(new InputFile('/path', 'HANDLE')),
                '/the/output/path',
                new OperationNoop(),
                array(),
                "thebinary HANDLE=/path output /the/output/path"
            ),
            array(
                'thebinary',
                array(new InputFile('/path', 'HANDLE', 'pa$$w0rd')),
                '/the/output/path',
                new OperationNoop(),
                array(),
                'thebinary HANDLE=/path input_pw HANDLE=pa$$w0rd output /the/output/path'
            ),
            array(
                'thebinary',
                array(new InputFile('/path', 'HANDLE', 'pa$$w0rd')),
                '/the/output/path',
                new OperationNoop(),
                array('flatten' => true),
                'thebinary HANDLE=/path input_pw HANDLE=pa$$w0rd output /the/output/path flatten'
            ),
            array(
                'thebinary',
                array(new InputFile('/path', 'HANDLE', 'pa$$w0rd')),
                '/the/output/path',
                $op,
                array('flatten' => null),
                'thebinary HANDLE=/path input_pw HANDLE=pa$$w0rd operation \'arg1\' \'arg2\' output /the/output/path'
            ),
            array(
                'thebinary',
                array(new InputFile('/path', 'HANDLE', 'pa$$w0rd')),
                '/the/output/path',
                $op,
                array('flatten' => false),
                'thebinary HANDLE=/path input_pw HANDLE=pa$$w0rd operation \'arg1\' \'arg2\' output /the/output/path'
            ),
            array(
                'thebinary',
                array(new InputFile('/path', 'HANDLE', 'pa$$w0rd')),
                '/the/output/path',
                $op,
                array('user_pw' => 'foo'),
                'thebinary HANDLE=/path input_pw HANDLE=pa$$w0rd operation \'arg1\' \'arg2\' output /the/output/path user_pw \'foo\''
            ),
            array(
                'thebinary',
                array(new InputFile('/path', 'HANDLE', 'pa$$w0rd')),
                '/the/output/path',
                $op,
                array('allow' => array('foo', 'bar')),
                'thebinary HANDLE=/path input_pw HANDLE=pa$$w0rd operation \'arg1\' \'arg2\' output /the/output/path allow \'foo\' \'bar\''
            ),
        );
    }
}
