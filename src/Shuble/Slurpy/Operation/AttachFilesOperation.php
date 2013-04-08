<?php

/**
 * This file is part of the Slurpy package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license MIT License
 */

namespace Shuble\Slurpy\Operation;

/**
 *
 * @author dorian ALKOUM <baikunz@gmail.com>
 */
class AttachFilesOperation extends BaseOperation
{
    /**
     * An array of filenames to attach
     *
     * @var array
     */
    protected $files;

    /**
     * Where to attach the files
     *
     * null for the whole pdf
     * page number for a specific page
     *
     * @var null|int
     */
    protected $toPage;

    /**
     * Constructor
     *
     * @param array    $files  An array of path to files
     * @param null|int $toPage Where to attach the files
     */
    public function __construct(array $files = array(), $toPage = null)
    {
        $this->files = $files;
        $this->toPage = $toPage;
    }

    /**
     * Sets the files to attach
     *
     * @param array $files
     *
     * @return AttachFilesOperation
     */
    public function setFiles(array $files)
    {
        $this->files = $files;

        return $this;
    }

    /**
     * Add a file to attach
     *
     * @param string $file
     *
     * @return AttachFilesOperation
     */
    public function addFile($file)
    {
        $this->files[] = $file;

        return $this;
    }

    /**
     * Gets the file to attach
     *
     * @return array
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * Sets where to attach the files
     *
     * @param null|int $toPage null for the whole pdf, or page number for a
     *                         specific page
     *
     * @return AttachFilesOperation
     */
    public function setToPage($toPage)
    {
        $this->toPage = null === $toPage
            ? null
            : (int) $toPage
        ;

        return $this;
    }

    /**
     * Gets where to attach the files
     *
     * @return null|int
     */
    public function getToPage()
    {
        return $this->toPage;
    }

    /**
     * (non-PHPdoc)
     * @see \Shuble\Slurpy\Operation\OperationInterface::getName()
     */
    public function getName()
    {
        return 'attach_files';
    }

    /**
     * (non-PHPdoc)
     * @see \Shuble\Slurpy\Operation\BaseOperation::getArguments()
     */
    public function getArguments()
    {
        $arguments = $this->files;

        if (null !== $this->toPage) {
            $arguments[] = 'to_page';
            $arguments[] = $this->toPage;
        }

        return $arguments;
    }
}
