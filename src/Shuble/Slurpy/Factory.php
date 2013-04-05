<?php

namespace Shuble\Slurpy;

use Shuble\Slurpy\Operation\OperationArgument\PageRange;
use Shuble\Slurpy\InputFile;
use Shuble\Slurpy\Operation\CatOperation;

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
     *            'end_page' => end,
     *            'qualifier' => 'odd',
     *            'rotation' => 'east',
     *        ),
     *        ...
     *    )
     * @param string $output
     */
    public function cat(array $inputs, $output)
    {
        $slurpy = new Slurpy($this->binary);

        $slurpy->setOutput($output);

        $operation = new CatOperation();

        foreach ($inputs as $index => $input) {
            $inputFile = new InputFile();

            if (is_array($input) && empty($input['filepath'])) {
                throw new \InvalidArgumentException('You must specify a filepath for each input files');
            }

            $handle = $this->generateFileHandle($index);
            $filePath = is_array($input) ? $input['filepath'] : $input;
            $filePassword = is_array($input) && !empty($input['password'])
                ? $input['password']
                : null
            ;

            $inputFile->setHandle($handle);
            $inputFile->setFilePath($filePath);
            $inputFile->setPassword($filePassword);

            $slurpy->addInput($inputFile);

            $startPage = is_array($input) && !empty($input['start_page']) ? $input['start_page'] : null;
            $endPage = is_array($input) && !empty($input['end_page']) ? $input['end_page'] : null;
            $qualifier = is_array($input) && !empty($input['qualifier']) ? $input['qualifier'] : null;
            $rotation = is_array($input) && !empty($input['rotation']) ? $input['rotation'] : null;

            $pageRange = new PageRange($handle, $startPage, $endPage, $qualifier, $rotation);
            $operation->addPageRange($pageRange);

            $slurpy->setOperation($operation);
        }

        return $slurpy;
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
