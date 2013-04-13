<?php

/**
 * This file is part of the Slurpy package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license MIT License
 */

namespace Shuble\Slurpy\Operation;

use Shuble\Slurpy\Operation\OperationInterface;

class FillFormOperation implements OperationInterface
{
    /**
     * Path to datafile used to fill the form
     *
     * @var string
     */
    protected $dataFile;

    /**
     * Set data filepath used to fill the form
     *
     * @param string $dataFile
     *
     * @return FillFormOperation
     */
    public function setDataFile($dataFile)
    {
        $this->dataFile = $dataFile;

        return $this;
    }

    /**
     * Get data filepath used to fill the form
     * @return string
     */
    public function getDataFile()
    {
        return $this->dataFile;
    }

    /**
     * (non-PHPdoc)
     * @see \Shuble\Slurpy\Operation\OperationInterface::getName()
     */
    public function getName()
    {
        return 'fill_form';
    }

    /**
     * (non-PHPdoc)
     * @see \Shuble\Slurpy\Operation\OperationInterface::getArguments()
     */
    public function getArguments()
    {
        return array($this->dataFile);
    }
}
