<?php

namespace Shuble\Slurpy\Tests;

use Shuble\Slurpy\Operation\OperationInterface;
use Shuble\Slurpy\InputFile;
use Shuble\Slurpy\Slurpy;

class SlurpyTest extends \PHPUnit_Framework_TestCase
{
    public function testAddOption()
    {
        $slurpy = $this->getMock('Shuble\Slurpy\Slurpy', null, array(''), '', false);

        $this->assertEquals(array(), $slurpy->getOptions());

        $r = new \ReflectionMethod($slurpy, 'addOption');
        $r->setAccessible(true);
        $r->invokeArgs($slurpy, array('foo', 'bar'));

        $this->assertEquals(array('foo' => 'bar'), $slurpy->getOptions(), '->addOption() adds an option');

        $r->invokeArgs($slurpy, array('baz', 'bat'));

        $this->assertEquals(
            array(
                'foo' => 'bar',
                'baz' => 'bat'
            ),
            $slurpy->getOptions(),
            '->addOption() appends the option to the existing ones'
        );

        $message = '->addOption() raises an exception when the specified option already exists';
        try {
            $r->invokeArgs($slurpy, array('baz', 'bat'));
            $this->fail($message);
        } catch (\InvalidArgumentException $e) {
            $this->anything($message);
        }
    }

    public function testAddOptions()
    {
        $slurpy = $this->getMock('Shuble\Slurpy\Slurpy', null,array(''), '', false);

        $this->assertEquals(array(), $slurpy->getOptions());

        $r = new \ReflectionMethod($slurpy, 'addOptions');
        $r->setAccessible(true);
        $r->invokeArgs($slurpy, array(array('foo' => 'bar', 'baz' => 'bat')));

        $this->assertEquals(
            array(
                'foo' => 'bar',
                'baz' => 'bat'
            ),
            $slurpy->getOptions(),
            '->addOptions() adds all the given options'
        );

        $r->invokeArgs($slurpy, array(array('ban' => 'bag', 'bal' => 'bac')));

        $this->assertEquals(
            array(
                'foo' => 'bar',
                'baz' => 'bat',
                'ban' => 'bag',
                'bal' => 'bac'
            ),
            $slurpy->getOptions(),
            '->addOptions() adds the given options to the existing ones'
        );

        $message = '->addOptions() raises an exception when one of the given options already exists';
        try {
            $r->invokeArgs($slurpy, array(array('bak' => 'bam', 'bah' => 'bap', 'baz' => 'bat')));
            $this->fail($message);
        } catch (\InvalidArgumentException $e) {
            $this->anything($message);
        }
    }

    public function testSetOption()
    {
        $slurpy = $this->getMock('Shuble\Slurpy\Slurpy', null,array(''), '', false);

        $r = new \ReflectionMethod($slurpy, 'addOption');
        $r->setAccessible(true);
        $r->invokeArgs($slurpy, array('foo', 'bar'));

        $slurpy->setOption('foo', 'abc');

        $this->assertEquals(
            array(
                'foo' => 'abc'
            ),
            $slurpy->getOptions(),
            '->setOption() defines the value of an option'
        );

        $message = '->setOption() raises an exception when the specified option does not exist';
        try {
            $slurpy->setOption('bad', 'def');
            $this->fail($message);
        } catch (\InvalidArgumentException $e) {
            $this->anything($message);
        }
    }

    public function testSetOptions()
    {
        $slurpy = $this->getMock('Shuble\Slurpy\Slurpy', null, array(''), '', false);

        $r = new \ReflectionMethod($slurpy, 'addOptions');
        $r->setAccessible(true);
        $r->invokeArgs($slurpy, array(array('foo' => 'bar', 'baz' => 'bat')));

        $slurpy->setOptions(array('foo' => 'abc', 'baz' => 'def'));

        $this->assertEquals(
            array(
                'foo'   => 'abc',
                'baz'   => 'def'
            ),
            $slurpy->getOptions(),
            '->setOptions() defines the values of all the specified options'
        );

        $message = '->setOptions() raises an exception when one of the specified options does not exist';
        try {
            $slurpy->setOptions(array('foo' => 'abc', 'baz' => 'def', 'bad' => 'ghi'));
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
        $slurpy = new Slurpy('');

        $setter = sprintf('set%s', ucfirst($property));
        $getter = sprintf('get%s', ucfirst($property));

        $slurpy->$setter($value);

        $this->assertEquals($value, $slurpy->$getter());
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
        $slurpy = $this->getMock('Shuble\Slurpy\Slurpy', null, array(''), '', false);

        $originalOptions = array('foo' => 'bar', 'baz' => 'bat');

        $addOptions = new \ReflectionMethod($slurpy, 'addOptions');
        $addOptions->setAccessible(true);
        $addOptions->invokeArgs($slurpy, array($originalOptions));

        $r = new \ReflectionMethod($slurpy, 'mergeOptions');
        $r->setAccessible(true);

        $mergedOptions = $r->invokeArgs($slurpy, array(array('foo' => 'ban')));

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
            $slurpy->getOptions(),
            '->mergeOptions() does NOT change the instance options'
        );

        $mergedOptions = $r->invokeArgs($slurpy, array(array('foo' => 'ban', 'baz' => 'bag')));

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
            $r->invokeArgs($slurpy, array(array('foo' => 'ban', 'bad' => 'bah')));
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
        $slurpy = $this->getMock('Shuble\Slurpy\Slurpy', array('mergeOptions'), array(), '', false);

        $slurpy
            ->setBinary($binary)
            ->setInputs($inputs)
            ->setOutput($output)
        ;

        if (null !== $operation) {
            $slurpy->setOperation($operation);
        }

        $r = new \ReflectionMethod($slurpy, 'buildCommand');
        $r->setAccessible(true);

        $this->assertEquals($expected, $r->invokeArgs($slurpy, array($options)));
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

        $op
            ->expects($this->any())
            ->method('getStdin')
            ->will($this->returnValue('foobar'))
        ;

        return array(
            array(
                'thebinary',
                array(new InputFile('/path', 'HANDLE')),
                '/the/output/path',
                null,
                array(),
                "thebinary HANDLE=/path output /the/output/path"
            ),
            array(
                'thebinary',
                array(new InputFile('/path', 'HANDLE', 'pa$$w0rd')),
                '/the/output/path',
                null,
                array(),
                'thebinary HANDLE=/path input_pw HANDLE=pa$$w0rd output /the/output/path'
            ),
            array(
                'thebinary',
                array(new InputFile('/path', 'HANDLE', 'pa$$w0rd')),
                '/the/output/path',
                null,
                array('flatten' => true),
                'thebinary HANDLE=/path input_pw HANDLE=pa$$w0rd output /the/output/path flatten'
            ),
            array(
                'thebinary',
                array(new InputFile('/path', 'HANDLE', 'pa$$w0rd')),
                '/the/output/path',
                $op,
                array('flatten' => null),
                'echo \'foobar\' | thebinary HANDLE=/path input_pw HANDLE=pa$$w0rd operation \'arg1\' \'arg2\' output /the/output/path'
            ),
            array(
                'thebinary',
                array(new InputFile('/path', 'HANDLE', 'pa$$w0rd')),
                '/the/output/path',
                $op,
                array('flatten' => false),
                'echo \'foobar\' | thebinary HANDLE=/path input_pw HANDLE=pa$$w0rd operation \'arg1\' \'arg2\' output /the/output/path'
            ),
            array(
                'thebinary',
                array(new InputFile('/path', 'HANDLE', 'pa$$w0rd')),
                '/the/output/path',
                $op,
                array('user_pw' => 'foo'),
                'echo \'foobar\' | thebinary HANDLE=/path input_pw HANDLE=pa$$w0rd operation \'arg1\' \'arg2\' output /the/output/path user_pw \'foo\''
            ),
            array(
                'thebinary',
                array(new InputFile('/path', 'HANDLE', 'pa$$w0rd')),
                '/the/output/path',
                $op,
                array('allow' => array('foo', 'bar')),
                'echo \'foobar\' | thebinary HANDLE=/path input_pw HANDLE=pa$$w0rd operation \'arg1\' \'arg2\' output /the/output/path allow \'foo\' \'bar\''
            ),
        );
    }

    public function testCheckProcessStatus()
    {
        $slurpy = $this->getMock(
            'Shuble\Slurpy\Slurpy',
            array(
                'configure',
            ),
            array(),
            '',
            false
        );

        $r = new \ReflectionMethod($slurpy, 'checkProcessStatus');
        $r->setAccessible(true);

        try {
            $r->invokeArgs($slurpy, array(0, '', '', 'the command'));
            $this->anything('0 status means success');
        } catch (\RuntimeException $e) {
            $this->fail('0 status means success');
        }

        try {
            $r->invokeArgs($slurpy, array(1, '', '', 'the command'));
            $this->anything('1 status means failure, but no stderr content');
        } catch (\RuntimeException $e) {
            $this->fail('1 status means failure, but no stderr content');
        }

        try {
            $r->invokeArgs($slurpy, array(1, '', 'Could not connect to X', 'the command'));
            $this->fail('1 status means failure');
        } catch (\RuntimeException $e) {
            $this->anything('1 status means failure');
        }
    }
}
