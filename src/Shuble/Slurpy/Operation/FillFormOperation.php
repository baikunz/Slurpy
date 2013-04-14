<?php

/**
 * This file is part of the Slurpy package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license MIT License
 */

namespace Shuble\Slurpy\Operation;

use Shuble\Slurpy\Operation\BaseOperation;
use Shuble\Slurpy\Operation\OperationArgument\FormData;

class FillFormOperation extends BaseOperation
{
    /**
     * Path to datafile used to fill the form or a FormData instance
     *
     * @var FormData|string
     */
    protected $dataFile;

    /**
     * Set data filepath used to fill the form
     *
     * @param FormData|string $dataFile
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
     *
     * @return FormData|string
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
        return $this->dataFile instanceof FormData
            ? array('-')
            : array($this->dataFile)
        ;
    }

    /**
     * (non-PHPdoc)
     * @see \Shuble\Slurpy\Operation\OperationInterface::getStdin()
     */
    public function getStdin()
    {
        return $this->dataFile instanceof FormData
            ? $this->dataFile->getXfdf('bla')
            : null
        ;
    }
}
