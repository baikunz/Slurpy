<?php

namespace Shuble\Slurpy\Tests\Operation;

use Shuble\Slurpy\Operation\AttachFilesOperation;

class AttachFilesOperationTest extends \PHPUnit_Framework_TestCase
{
    public function testGetName()
    {
        $operation = new AttachFilesOperation();

        $this->assertEquals('attach_files', $operation->getName());
    }

    public function testAddSetGetFiles()
    {
        $operation = new AttachFilesOperation();

        $this->assertEquals(array(), $operation->getFiles());

        $files = array(
            '/path/to/file1',
            '/path/to/file2',
        );
        $operation->setFiles($files);
        $this->assertEquals($files, $operation->getFiles());

        $file = '/path/to/file3';
        $files[] = $file;
        $operation->addFile($file);
        $this->assertEquals($files, $operation->getFiles());
    }

    public function testSetGetToPage()
    {
        $operation = new AttachFilesOperation();

        $this->assertEquals(null, $operation->getToPage());

        $operation->setToPage(1);
        $this->assertEquals(1, $operation->getToPage());
    }

    /**
     * @dataProvider dataForTestGetArguments
     */
    public function testGetArguments($files, $toPage, $expected)
    {
        $operation = new AttachFilesOperation();

        $operation
            ->setFiles($files)
            ->setToPage($toPage)
        ;

        $this->assertEquals($expected, $operation->getArguments());
    }

    public function dataForTestGetArguments()
    {
        return array(
            array(
                array('/path/to/file1'),
                null,
                array('/path/to/file1'),
            ),
            array(
                array('/path/to/file1'),
                6,
                array('/path/to/file1', 'to_page', 6),
            ),
            array(
                array('/path/to/file1', 'path/to/file2'),
                6,
                array('/path/to/file1', 'path/to/file2', 'to_page', 6),
            )
        );
    }
}
