<?php

namespace Shuble\Slurpy\Operation;

use Shuble\Slurpy\Operation\OperationInterface;

abstract class BaseOperation implements OperationInterface
{
    /**
     * (non-PHPdoc)
     * @see \Shuble\Slurpy\Operation\OperationInterface::getArguments()
     */
    public function getArguments()
    {
        return array();
    }
}
