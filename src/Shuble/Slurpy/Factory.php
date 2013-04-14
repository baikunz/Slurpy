<?php

/**
 * This file is part of the Slurpy package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license MIT License
 */

namespace Shuble\Slurpy;

use Shuble\Slurpy\Operation\OperationArgument\FormData;

use Shuble\Slurpy\Operation\AttachFilesOperation;
use Shuble\Slurpy\Operation\UpdateInfoOperation;
use Shuble\Slurpy\Operation\UnpackFilesOperation;
use Shuble\Slurpy\Operation\DumpDataOperation;
use Shuble\Slurpy\Operation\StampOperation;
use Shuble\Slurpy\Operation\FillFormOperation;
use Shuble\Slurpy\Operation\GenerateFdfOperation;
use Shuble\Slurpy\Operation\BurstOperation;
use Shuble\Slurpy\Operation\BackgroundOperation;
use Shuble\Slurpy\Operation\ShuffleOperation;
use Shuble\Slurpy\Operation\CatOperation;
use Shuble\Slurpy\Operation\OperationArgument\PageRange;
use Shuble\Slurpy\InputFile;

/**
 *
 * @author dorian ALKOUM <baikunz@gmail.com>
 */
class Factory
{
    /**
     * Path to pdftk binary
     *
     * @var string
     */
    protected $binary;

    /**
     * Constructor
     *
     * @param string $binary
     */
    public function __construct($binary)
    {
        $this->binary = $binary;
    }

    /**
     * Creates a slurpy instance initialized with a cat operation
     *
     * @param array $inputs An array of input files
     *    array(
     *        'path/to/file1.pdf',
     *        'path/to/file2.pdf',
     *        ...
     *    )
     *    or
     *    array(
     *        array(
     *            'filepath' => 'path/to/file1.pdf',
     *            'password' => 'pa$$word',
     *            'start_page' => 1,
     *            'end_page' => 'end',
     *            'qualifier' => 'odd',
     *            'rotation' => 'east',
     *        ),
     *        ...
     *    )
     * @param string $output
     *
     * @return Slurpy
     */
    public function cat(array $inputs, $output)
    {
        $slurpy = $this->getSlurpy($output);

        $operation = new CatOperation();
        $slurpy->setOperation($operation);

        foreach ($inputs as $index => $input) {
            if (is_array($input) && empty($input['filepath'])) {
                throw new \InvalidArgumentException('You must specify a filepath for each input files');
            }

            $inputFile = $this->getInputFile($input, $index);
            $slurpy->addInput($inputFile);

            $pageRange = $this->getPageRange($inputFile->getHandle(), $input);
            $operation->addPageRange($pageRange);
        }

        return $slurpy;
    }

    public function shuffle(array $inputs, $output)
    {
        $slurpy = $this->getSlurpy($output);

        $operation = new ShuffleOperation();
        $slurpy->setOperation($operation);

        foreach ($inputs as $index => $input) {
            if (is_array($input) && empty($input['filepath'])) {
                throw new \InvalidArgumentException('You must specify a filepath for each input files');
            }

            $inputFile = $this->getInputFile($input, $index);
            $slurpy->addInput($inputFile);

            $pageRange = $this->getPageRange($inputFile->getHandle(), $input);
            $operation->addPageRange($pageRange);
        }

        return $slurpy;
    }

    public function background($input, $background, $output, $multi = false)
    {
        if (is_array($input) && empty($input['filepath'])) {
            throw new \InvalidArgumentException('You must specify a filepath for the input file');
        }

        $slurpy = $this->getSlurpy($output);

        $inputFile = $this->getInputFile($input);
        $slurpy->addInput($inputFile);

        $operation = new BackgroundOperation();
        $operation
            ->setMulti($multi)
            ->setBackgroundFile($background)
        ;
        $slurpy->setOperation($operation);

        return $slurpy;
    }

    public function multiBackground($input, $background, $output)
    {
        return $this->background($input, $background, $output, true);
    }

    public function burst($input, $output)
    {
        if (is_array($input) && empty($input['filepath'])) {
            throw new \InvalidArgumentException('You must specify a filepath for the input file');
        }

        $slurpy = $this->getSlurpy($output);

        $inputFile = $this->getInputFile($input);
        $slurpy->addInput($inputFile);

        $slurpy->setOperation(new BurstOperation());

        return $slurpy;
    }

    public function generateFdf($input, $output)
    {
        if (is_array($input) && empty($input['filepath'])) {
            throw new \InvalidArgumentException('You must specify a filepath for the input file');
        }

        $slurpy = $this->getSlurpy($output);

        $inputFile = $this->getInputFile($input);
        $slurpy->addInput($inputFile);

        $slurpy->setOperation(new GenerateFdfOperation());

        return $slurpy;
    }

    public function fillForm($input, $dataFile, $output, $flatten = false)
    {
        if (is_array($input) && empty($input['filepath'])) {
            throw new \InvalidArgumentException('You must specify a filepath for the input file');
        }

        $slurpy = $this->getSlurpy($output);

        $inputFile = $this->getInputFile($input);
        $slurpy->addInput($inputFile);

        $operation = new FillFormOperation();
        $dataFile = is_array($dataFile)
            ? new FormData($dataFile)
            : $dataFile
        ;
        $operation->setDataFile($dataFile);
        $slurpy->setOperation($operation);

        $slurpy->setOption('flatten', $flatten);

        return $slurpy;
    }

    public function stamp($input, $stampFile, $output, $multi = false)
    {
        if (is_array($input) && empty($input['filepath'])) {
            throw new \InvalidArgumentException('You must specify a filepath for the input file');
        }

        $slurpy = $this->getSlurpy($output);

        $inputFile = $this->getInputFile($input);
        $slurpy->addInput($inputFile);

        $operation = new StampOperation();
        $operation
            ->setMulti($multi)
            ->setStampFile($stampFile)
        ;
        $slurpy->setOperation($operation);

        return $slurpy;
    }

    public function multistamp($input, $stampFile, $output)
    {
        return $this->stamp($input, $stampFile, $output, true);
    }

    public function dumpData($input, $output, $fields = false, $utf8 = false)
    {
        if (is_array($input) && empty($input['filepath'])) {
            throw new \InvalidArgumentException('You must specify a filepath for the input file');
        }

        $slurpy = $this->getSlurpy($output);

        $inputFile = $this->getInputFile($input);
        $slurpy->addInput($inputFile);

        $operation = new DumpDataOperation();
        $operation
            ->setFields($fields)
            ->setUtf8($utf8)
        ;
        $slurpy->setOperation($operation);

        return $slurpy;
    }

    public function dumpDataFields($input, $output)
    {
        return $this->dumpData($input, $output, true);
    }

    public function dumpDataUtf8($input, $output)
    {
        return $this->dumpData($input, $output, false, true);
    }

    public function dumpDataFieldsUtf8($input, $output)
    {
        return $this->dumpData($input, $output, true, true);
    }

    public function unpackFiles($input, $output)
    {
        if (is_array($input) && empty($input['filepath'])) {
            throw new \InvalidArgumentException('You must specify a filepath for the input file');
        }

        $slurpy = $this->getSlurpy($output);

        $inputFile = $this->getInputFile($input);
        $slurpy->addInput($inputFile);

        $slurpy->setOperation(new UnpackFilesOperation());

        return $slurpy;
    }

    public function updateInfo($input, $dataFile, $output, $utf8 = false)
    {
        if (is_array($input) && empty($input['filepath'])) {
            throw new \InvalidArgumentException('You must specify a filepath for the input file');
        }

        $slurpy = $this->getSlurpy($output);

        $inputFile = $this->getInputFile($input);
        $slurpy->addInput($inputFile);

        $operation = new UpdateInfoOperation();
        $operation
            ->setDataFile($dataFile)
            ->setUtf8($utf8)
        ;
        $slurpy->setOperation($operation);

        return $slurpy;
    }

    public function attachFiles($input, array $files, $output, $toPage = null)
    {
        if (is_array($input) && empty($input['filepath'])) {
            throw new \InvalidArgumentException('You must specify a filepath for the input file');
        }

        $slurpy = $this->getSlurpy($output);

        $inputFile = $this->getInputFile($input);
        $slurpy->addInput($inputFile);

        $operation = new AttachFilesOperation();
        $operation
            ->setFiles($files)
            ->setToPage($toPage)
        ;
        $slurpy->setOperation($operation);

        return $slurpy;
    }

    protected function getSlurpy($output)
    {
        $slurpy = new Slurpy($this->binary);

        $slurpy->setOutput($output);

        return $slurpy;
    }

    protected function getInputFile($input, $index = 0)
    {
        $handle = $this->generateFileHandle($index);
        $filePath = is_array($input) ? $input['filepath'] : $input;
        $filePassword = is_array($input) && !empty($input['password'])
            ? $input['password']
            : null
        ;

        $inputFile = new InputFile();
        $inputFile
            ->setHandle($handle)
            ->setFilePath($filePath)
            ->setPassword($filePassword)
        ;

        return $inputFile;
    }

    protected function getPageRange($handle, $input)
    {
        $startPage = is_array($input) && !empty($input['start_page']) ? $input['start_page'] : null;
        $endPage = is_array($input) && !empty($input['end_page']) ? $input['end_page'] : null;
        $qualifier = is_array($input) && !empty($input['qualifier']) ? $input['qualifier'] : null;
        $rotation = is_array($input) && !empty($input['rotation']) ? $input['rotation'] : null;

        return new PageRange($handle, $startPage, $endPage, $qualifier, $rotation);
    }

    /**
     * Generate a unique file handle
     *
     * @param int $index
     *
     * @return string
     */
    protected function generateFileHandle($index)
    {
        return chr(65 + floor($index/26) % 26) . chr(65 + $index % 26);
    }
}
