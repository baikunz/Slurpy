<?php

/**
 * This file is part of the Slurpy package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license MIT License
 */

namespace Shuble\Slurpy\Operation;

/**
 *
 * @author dorian ALKOUM <baikunz@gmail.com>
 */
class BackgroundOperation extends BaseOperation
{
    /**
     * Whether the background operation is multi or not
     *
     * @var boolean
     */
    protected $multi;

    /**
     * Path to a background pdf file
     *
     * @var string
     */
    protected $backgroundFile;

    /**
     * Constructor
     *
     * @param string $backgroundFile Path to the background file
     * @param string $multi          Whether the background operation is multi or not
     */
    public function __construct($backgroundFile = null, $multi = false)
    {
        $this->backgroundFile = $backgroundFile;
        $this->multi = $multi;
    }

    /**
     * (non-PHPdoc)
     * @see \Shuble\Slurpy\Operation\OperationInterface::getName()
     */
    public function getName()
    {
        return false === $this->multi
            ? 'background'
            : 'multibackground'
        ;
    }

    /**
     * (non-PHPdoc)
     * @see \Shuble\Slurpy\Operation\BaseOperation::getArguments()
     */
    public function getArguments()
    {
        return array($this->backgroundFile);
    }

    /**
     * Sets whether the background operation is multiple or not
     *
     * @param boolean $multi
     *
     * @return BackgroundOperation
     */
    public function setMulti($multi)
    {
        $this->multi = (boolean) $multi;

        return $this;
    }

    /**
     * Gets whether the background operation is multiple or not
     *
     * @return boolean
     */
    public function getMulti()
    {
        return $this->multi;
    }

    /**
     * Sets the path to the background file
     *
     * @param string $backgroundFile
     *
     * @return BackgroundOperation
     */
    public function setBackgroundFile($backgroundFile)
    {
        $this->backgroundFile = $backgroundFile;

        return $this;
    }

    /**
     * Gets the path to the background file
     *
     * @return string
     */
    public function getBackgroundFile()
    {
        return $this->backgroundFile;
    }
}
