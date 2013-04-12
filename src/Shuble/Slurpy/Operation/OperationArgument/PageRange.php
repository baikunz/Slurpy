<?php

/**
 * This file is part of the Slurpy package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license MIT License
 */

namespace Shuble\Slurpy\Operation\OperationArgument;

/**
 * A PageRange is used as parameters for cat and shuffle operations
 *
 *     * A file handle which is one from an input file (The whole file is used
 *       if no start/end page)
 *     * A start page which is the first page from this page range
 *       or the single page if the end page is ommited. (optional)
 *     * An end page which  is the last page of this page range (optional)
 *       (A page can be an int, 'end', 'rend' or r followed by an int. if
 *       'r' means reverse, so 'rend' is the first page and r1 is the last page.)
 *     * A qualifier which can be either odd or even (optional)
 *     * Rotation which can be one of north, south, east, west, left,
 *       right or down (optional)
 *
 * @link http://www.pdflabs.com/docs/pdftk-man-page/#dest-op-cat
 * @see Shuble\Slurpy\Operation\CatOperation
 * @see Shuble\Slurpy\Operation\ShuffleOperation
 *
 * @author  ALKOUM Dorian <dorian.alkoum@gmail.com>
 */
class PageRange
{
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
     * @param string           $fileHandle A file handle which is one from an input file (The whole file is
     *                                     used if no start/end page)
     * @param null|int|string  $startPage  A start page which is the first page from this page range or the
     *                                     single page if the end page is ommited.
     * @param null|int|string  $endPage    An end page which  is the last page of this page range
     * @param null|string      $qualifier  A qualifier which can be either odd or even
     * @param null|string      $rotation   Rotation which can be one of north, south, east, west, left,
     *                                     right or down
     */
    public function __construct(
        $fileHandle,
        $startPage = null,
        $endPage = null,
        $qualifier = null,
        $rotation = null
    ) {
        $this->setFileHandle($fileHandle);

        $this->startPage = $startPage;
        $this->endPage   = $endPage;
        $this->qualifier = $qualifier;
        $this->rotation  = $rotation;
    }

    /**
     * Sets the file handle
     *
     * @param string $fileHandle An InputFile handle
     *
     * @throws \InvalidArgumentException If the handle is not one or more upper-case letters
     *
     * @return PageRange
     */
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

    /**
     * Gets the file handle
     *
     * @return string
     */
    public function getFileHandle()
    {
        return $this->fileHandle;
    }

    /**
     * Sets the first page of this page range, or the single page if endPage is null
     *
     * @param null|int|string $startPage Either an int, 'end', 'rend', 'r' followed by an int or null
     *
     * @throws \InvalidArgumentException If not an int, 'end', 'rend', 'r' followed by an int or null
     *
     * @return PageRange
     */
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

    /**
     * Gets the first page of this page range, or the single page if endPage is null
     *
     * @return null|int|string An int, 'end', 'rend', 'r' followed by an int or null
     */
    public function getStartPage()
    {
        return $this->startPage;
    }

    /**
     * Sets the last page of this page range
     *
     * @param null|int|string $endPage Either an int, 'end', 'rend', 'r' followed by an int or null
     *
     * @throws \InvalidArgumentException If not an int, 'end', 'rend', 'r' followed by an int or null
     *
     * @return PageRange
     */
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

    /**
     * Gets the last page of this page range
     *
     * @return null|int|string An int, 'end', 'rend', 'r' followed by an int or null
     */
    public function getEndPage()
    {
        return $this->endPage;
    }

    /**
     * Sets the qualifier
     *
     * @param null|string $qualifier A valid qualifier is either 'even' or 'odd'
     *
     * @throws \InvalidArgumentException If not 'even', 'odd' or null
     *
     * @return PageRange
     */
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

    /**
     * Gets the qualifier
     *
     * @return null|string $qualifier A valid qualifier is either 'even' or 'odd'
     */
    public function getQualifier()
    {
        return $this->qualifier;
    }

    /**
     * Sets rotation for this page range (see. ROTATION_* constants)
     *
     * @param null|string $rotation One of 'north', 'south', 'east', 'west', 'left', 'right',
     *                              or 'down'
     *
     * @throws \InvalidArgumentException if not one of 'north', 'south', 'east', 'west', 'left',
     *                                   'right', or 'down'
     *
     * @return PageRange
     */
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

    /**
     * Gets rotation for this page range (see. ROTATION_* constants)
     *
     * @return null|string One of 'north', 'south', 'east', 'west', 'left', 'right',
     *                     or 'down'
     */
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
