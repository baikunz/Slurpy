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
 * Assembles ("catenates") pages from input PDFs to create a new PDF.
 * Use cat to merge PDF pages or to split PDF pages from documents.
 * You can also use it to rotate PDF pages. Page order in the new PDF
 * is specified by the order of the given page ranges.
 *
 * @see PageRange
 * @link http://www.pdflabs.com/docs/pdftk-man-page/#dest-op-cat
 *
 * @author dorian ALKOUM <baikunz@gmail.com>
 */
class CatOperation extends BaseOperation
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
     * @see PageRange
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
     * @see PageRange
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
     * @see PageRange
     *
     * @param PageRange $pageRange A single PageRange
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
        return 'cat';
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
