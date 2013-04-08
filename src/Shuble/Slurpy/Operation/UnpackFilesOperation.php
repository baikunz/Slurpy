<?php

/**
 * This file is part of the Slurpy package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license MIT License
 */

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
