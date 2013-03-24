<?php

namespace Shuble\Slurpy;

/**
 * InputFile
 *
 * @package Slurpy
 *
 * @author  ALKOUM Dorian <dorian.alkoum@gmail.com>
 */
class InputFile
{
    /**
     * Path to file
     *
     * @var string
     */
    protected $filePath;

    /**
     * Handle for the file
     *
     * @var string
     */
    protected $handle;

    /**
     * Password to open file
     *
     * @var string
     */
    protected $password;
}
