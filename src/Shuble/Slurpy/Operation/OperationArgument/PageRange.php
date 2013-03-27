<?php

namespace Shuble\Slurpy\Operation\OperationArgument;

/**
 * Page range
 *
 * @package Slurpy
 *
 * @author  ALKOUM Dorian <dorian.alkoum@gmail.com>
 */
class PageRange
{
    const PAGE_END = 'end';

    const QUALIFIER_EVEN = 'even';
    const QUALIFIER_ODD  = 'odd';

    const ROTATION_NORTH  = 'north';
    const ROTATION_SOUTH  = 'south';
    const ROTATION_EAST   = 'east';
    const ROTATION_WEST   = 'west';
    const ROTATION_LEFT   = 'left';
    const ROTATION_RIGHT  = 'right';
    const ROTATION_DOWN   = 'down';

    /**
     * Handle of the page range file
     *
     * @var string
     */
    protected $fileHandle;

    /**
     * Starting page for this page range
     *
     * @var mixed
     */
    protected $startPage;

    /**
     * Ending page for this page range
     *
     * @var mixed
     */
    protected $endPage;

    /**
     * Qualifier for filtering on odd / even pages
     *
     * @var null|string
     */
    protected $qualifier;

    /**
     * Rotation for this page range
     *
     * @var null|string
     */
    protected $rotation;

    /**
     * Constructor
     *
     * @param string $fileHandle
     * @param array  $options
     */
    public function __construct($fileHandle)
    {
        $this->setFileHandle($fileHandle);
    }

    public function setFileHandle($fileHandle)
    {
        if (!ctype_upper((string) $fileHandle)) {
            throw new \InvalidArgumentException(sprintf(
                '"%s" is not a valid handle. A handle must be one or more upper-case letters',
                $fileHandle
            ));
        }

        $this->fileHandle = $fileHandle;

        return $this;
    }

    public function getFileHandle()
    {
        return $this->fileHandle;
    }

    public function setStartPage($startPage)
    {
        if (null !== $startPage && 0 === preg_match('/^r?(end|[1-9][0-9]*)$/', $startPage)) {
            throw new \InvalidArgumentException(sprintf(
                '"%s" is not a valid page. Expecting "end", "rend", positive int, or "r" followed by positive int.',
                $startPage
            ));
        }

        $this->startPage = $startPage;

        return $this;
    }

    public function getStartPage()
    {
        return $this->startPage;
    }

    public function setEndPage($endPage)
    {
        if (null !== $endPage && 0 === preg_match('/^r?(end|[1-9][0-9]*)$/', $endPage)) {
            throw new \InvalidArgumentException(sprintf(
                '"%s" is not a valid page. Expecting "end", "rend", positive int, or "r" followed by positive int.',
                $endPage
            ));
        }

        $this->endPage = $endPage;

        return $this;
    }

    public function getEndPage()
    {
        return $this->endPage;
    }

    public function setQualifier($qualifier)
    {
        if (null !== $qualifier && !in_array($qualifier, $this->getAllowedQualifiers())) {
            throw new \InvalidArgumentException(sprintf(
                '"%s" is not a valid qualifier. Expecting one of "%s"',
                $qualifier,
                implode('", "', $this->getAllowedQualifiers())
            ));
        }

        $this->qualifier = $qualifier;

        return $this;
    }

    public function getQualifier()
    {
        return $this->qualifier;
    }

    public function setRotation($rotation)
    {
        if (null !== $rotation && !in_array($rotation, $this->getAllowedRotations())) {
            throw new \InvalidArgumentException(sprintf(
                '"%s" is not a valid rotation. Expecting one of "%s"',
                $rotation,
                implode('", "', $this->getAllowedRotations())
            ));
        }

        $this->rotation = $rotation;

        return $this;
    }

    public function getRotation()
    {
        return $this->rotation;
    }

    public function __toString()
    {
        $pageRange = $this->fileHandle;

        if (null !== $this->startPage) {
            $pageRange .= $this->startPage;

            if (null !== $this->endPage) {
                $pageRange .= sprintf('-%s', $this->endPage);
            }
        }

        if (null !== $this->endPage && null !== $this->qualifier) {
            $pageRange .= $this->qualifier;
        }

        if (null !== $this->rotation) {
            $pageRange .= $this->rotation;
        }

        return $pageRange;
    }

    /**
     * Return an array of supported qualifiers
     *
     * @return array
     */
    protected function getAllowedQualifiers()
    {
        return array(
            static::QUALIFIER_EVEN,
            static::QUALIFIER_ODD,
        );
    }

    /**
     * Return an array of supported rotations
     *
     * @return array
     */
    protected function getAllowedRotations()
    {
        return array(
            static::ROTATION_NORTH,
            static::ROTATION_SOUTH,
            static::ROTATION_EAST,
            static::ROTATION_WEST,
            static::ROTATION_LEFT,
            static::ROTATION_RIGHT,
            static::ROTATION_DOWN,
        );
    }
}
