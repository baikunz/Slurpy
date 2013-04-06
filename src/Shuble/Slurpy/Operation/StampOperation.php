<?php

namespace Shuble\Slurpy\Operation;

class StampOperation extends BaseOperation
{
    /**
     * Whether the stamp operation is multi or not
     *
     * @var boolean
     */
    protected $multi;

    /**
     * Path to a stamp pdf file
     *
     * @var string
     */
    protected $stampFile;

    /**
     * Constructor
     *
     * @param string $stampFile Path to the stamp file
     * @param string $multi     Whether the stamp operation is multi or not
     */
    public function __construct($stampFile = null, $multi = false)
    {
        $this->stampFile = $stampFile;
        $this->multi = $multi;
    }

    /**
     * (non-PHPdoc)
     * @see \Shuble\Slurpy\Operation\OperationInterface::getName()
     */
    public function getName()
    {
        return false === $this->multi
            ? 'stamp'
            : 'multistamp'
        ;
    }

    /**
     * (non-PHPdoc)
     * @see \Shuble\Slurpy\Operation\BaseOperation::getArguments()
     */
    public function getArguments()
    {
        return array($this->stampFile);
    }

    /**
     * Sets whether the stamp operation is multiple or not
     *
     * @param boolean $multi
     *
     * @return StampOperation
     */
    public function setMulti($multi)
    {
        $this->multi = (boolean) $multi;

        return $this;
    }

    /**
     * Gets whether the stamp operation is multiple or not
     *
     * @return boolean
     */
    public function getMulti()
    {
        return $this->multi;
    }

    /**
     * Sets the path to the stamp file
     *
     * @param string $stampFile
     *
     * @return StampOperation
     */
    public function setStampFile($stampFile)
    {
        $this->stampFile = $stampFile;

        return $this;
    }

    /**
     * Gets the path to the stamp file
     *
     * @return string
     */
    public function getStampFile()
    {
        return $this->stampFile;
    }
}
