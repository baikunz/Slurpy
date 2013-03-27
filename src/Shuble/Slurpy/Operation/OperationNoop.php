<?php

namespace Shuble\Slurpy\Operation;
class OperationNoop implements OperationInterface
{
    /**
     * @codeCoverageIgnore
     * (non-PHPdoc)
     * @see \Shuble\Slurpy\Operation\OperationInterface::getName()
     */
    public function getName()
    {
        return null;
    }

    /**
     * @codeCoverageIgnore
     * (non-PHPdoc)
     * @see \Shuble\Slurpy\Operation\OperationInterface::getArguments()
     */
    public function getArguments()
    {
        return array();
    }
}
