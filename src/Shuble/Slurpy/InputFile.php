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

    /**
     * Constructor
     *
     * @param string $filePath Path to file
     * @param string $handle   Handle (alias) for internal reference to this file
     * @param string $password Possword if input file is protected (Optional)
     */
    public function __construct($filePath = null, $handle = null, $password = null)
    {
        $this->filePath = $filePath;
        $this->handle = $handle;
        $this->password = $password;
    }

    /**
     * Get input file path
     *
     * @return string
     */
    public function getFilePath()
    {
        return $this->filePath;
    }

    /**
     * Sets input file path
     *
     * @param string $filePath
     *
     * @return InputFile
     */
    public function setFilePath($filePath)
    {
        $this->filePath = (string) $filePath;

        return $this;
    }

    /**
     * Get input file handle
     *
     * @return string
     */
    public function getHandle()
    {
        return $this->handle;
    }

    /**
     * Set input file handle
     *
     * @param string $handle
     *
     * @throws \InvalidArgumentException
     *
     * @return InputFile
     */
    public function setHandle($handle)
    {
        if (!ctype_upper((string) $handle)) {
            throw new \InvalidArgumentException(sprintf(
                '"%s" is not a valid handle. A handle must be one or more upper-case letters',
                $handle
            ));
        }

        $this->handle = $handle;

        return $this;
    }

    /**
     * Get input file password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set Input file password
     *
     * @param string $password
     *
     * @return \Shuble\Slurpy\InputFile
     */
    public function setPassword($password)
    {
        $this->password = null === $password
            ? null
            : (string) $password
        ;

        return $this;
    }
}
