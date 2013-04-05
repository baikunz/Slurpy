<?php

namespace Shuble\Slurpy\Operation;

class UnpackFilesOperation extends BaseOperation
{
    /**
     * (non-PHPdoc)
     * @see \Shuble\Slurpy\Operation\OperationInterface::getName()
     */
    public function getName()
    {
        return 'unpack_files';
    }
}
