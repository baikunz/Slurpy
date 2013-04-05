<?php

namespace Shuble\Slurpy\Operation;

class GenerateFdfOperation extends BaseOperation
{
    /**
     * (non-PHPdoc)
     * @see \Shuble\Slurpy\Operation\OperationInterface::getName()
     */
    public function getName()
    {
        return 'generate_fdf';
    }
}
