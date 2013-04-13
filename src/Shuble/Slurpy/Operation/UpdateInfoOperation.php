<?php

/**
 * This file is part of the Slurpy package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license MIT License
 */

namespace Shuble\Slurpy\Operation;

class UpdateInfoOperation extends BaseOperation
{
    /**
     * Path to the data file
     *
     * @var string
     */
    protected $dataFile;

    /**
     * Whether the data input file is utf8 encoded or not
     *
     * @var boolean
     */
    protected $utf8;

    /**
     * Constructor
     *
     * @param boolean $utf8 Whether the data input file is utf8 encoded or not
     */
    public function __construct($dataFile = null, $utf8 = false)
    {
        $this->dataFile = $dataFile;
        $this->utf8 = $utf8;
    }

    /**
     * (non-PHPdoc)
     * @see \Shuble\Slurpy\Operation\OperationInterface::getName()
     */
    public function getName()
    {
        return false === $this->utf8
            ? 'update_info'
            : 'update_info_utf8'
        ;
    }

    /**
     * (non-PHPdoc)
     * @see \Shuble\Slurpy\Operation\BaseOperation::getArguments()
     */
    public function getArguments()
    {
        return array($this->dataFile);
    }

    /**
     * Sets the path to data file
     *
     * @param string $dataFile
     *
     * @return UpdateInfoOperation
     */
    public function setDataFile($dataFile)
    {
        $this->dataFile = $dataFile;

        return $this;
    }

    /**
     * Gets the path to data file
     *
     * @return string
     */
    public function getDataFile()
    {
        return $this->dataFile;
    }

    /**
     * Sets whether the data input file is utf8 encoded or not
     *
     * @param boolean $utf8
     *
     * @return UpdateInfoOperation
     */
    public function setUtf8($utf8)
    {
        $this->utf8 = (boolean) $utf8;

        return $this;
    }

    /**
     * Gets whether the data input file is utf8 encoded or not
     *
     * @return boolean
     */
    public function getUtf8()
    {
        return $this->utf8;
    }
}
