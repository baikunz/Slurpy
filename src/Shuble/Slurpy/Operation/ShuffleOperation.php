<?php

namespace Shuble\Slurpy\Operation;

use Shuble\Slurpy\Operation\OperationInterface;
use Shuble\Slurpy\Operation\OperationArgument\PageRange;

class ShuffleOperation implements OperationInterface
{
    /**
     * An array of page ranges as arguments
     *
     * @var PageRange[]
     */
    protected $pageRanges = array();

    /**
     * Sets page ranges
     *
     * @param PageRange[] $pageRanges An array of PageRange
     */
    public function setPageRanges(array $pageRanges)
    {
        $this->pageRanges = $pageRanges;

        return $this;
    }

    /**
     * Get page ranges
     *
     * @return PageRange[]
     */
    public function getPageRanges()
    {
        return $this->pageRanges;
    }

    /**
     * Add a page range
     *
     * @param PageRange $pageRange
     *
     * @return OperationInterface
     */
    public function addPageRange(PageRange $pageRange)
    {
        $this->pageRanges[] = $pageRange;

        return $this;
    }

    /**
     * (non-PHPdoc)
     * @see \Shuble\Slurpy\Operation\OperationInterface::getName()
     */
    public function getName()
    {
        return 'shuffle';
    }

    /**
     * (non-PHPdoc)
     * @see \Shuble\Slurpy\Operation\OperationInterface::getArguments()
     */
    public function getArguments()
    {
        return array_map('strval', $this->pageRanges);
    }
}
