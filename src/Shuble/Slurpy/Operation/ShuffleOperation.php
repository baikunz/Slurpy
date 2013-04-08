<?php

/**
 * This file is part of the Slurpy package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license MIT License
 */

namespace Shuble\Slurpy\Operation;

use Shuble\Slurpy\Operation\OperationArgument\PageRange;

/**
 * Collates pages from input PDFs to create a new PDF.Works like
 * the cat operation except that it takes one page at a time from
 * each page range to assemble the output PDF. If one range runs
 * out of pages, it continues with the remaining ranges.
 * Ranges can use all of the features described above for cat,
 * like reverse page ranges, multiple ranges from a single PDF,
 * and page rotation. This feature was designed to help collate PDF
 * pages after scanning paper documents.
 *
 * @see PageRange
 * @link http://www.pdflabs.com/docs/pdftk-man-page/#dest-op-shuffle
 *
 * @author dorian ALKOUM <baikunz@gmail.com>
 */
class ShuffleOperation extends BaseOperation
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
