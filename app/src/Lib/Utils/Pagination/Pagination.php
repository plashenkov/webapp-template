<?php

namespace App\Lib\Utils\Pagination;

class Pagination
{
    /**
     * Default options.
     * @var array
     */
    protected $options = [
        'paginationLength' => 10,
        'ignoreSinglePage' => false
    ];

    /**
     * Pagination constructor.
     * @param array $options Options.
     */
    public function __construct(array $options = [])
    {
        $this->options = array_replace($this->options, $options);
    }

    /**
     * Calculate pagination.
     * @param int $page Current page index.
     * @param int|array $pagesTotal Pass either a total page count (int) directly or an array: [itemsTotal, itemsPerPage].
     * @param array $options Options.
     * @return array Array of pages.
     */
    public function calculate($page, $pagesTotal, array $options = [])
    {
        $options = array_replace($this->options, $options);

        if (is_array($pagesTotal)) {
            $itemsTotal = $pagesTotal[0];
            $itemsPerPage = $pagesTotal[1];
            $pagesTotal = ceil($itemsTotal / $itemsPerPage);
        }

        $pages = [];

        if ($options['ignoreSinglePage']) {
            $pagerNotEmpty = $pagesTotal > 1;
        } else {
            $pagerNotEmpty = $pagesTotal > 0;
        }

        if ($pagerNotEmpty) {
            $first = $page - floor(($options['paginationLength'] - 5) / 2);

            if ($first < 4) {
                $first = 1;
                $last = $options['paginationLength'] - 2;
            } else {
                $last = $first + $options['paginationLength'] - 5;
            }

            if ($last > $pagesTotal - 3) {
                $last = $pagesTotal;
                $first = $last - $options['paginationLength'] + 3;
                if ($first < 4) {
                    $first = 1;
                }
            }

            if ($first != 1) {
                $pages[] = new Page(1, $page == 1);
                $pages[] = new Page(false);
            }

            for ($i = $first; $i <= $last; $i++) {
                $pages[] = new Page($i, $page == $i);
            }

            if ($last != $pagesTotal) {
                $pages[] = new Page(false);
                $pages[] = new Page($pagesTotal, $page == $pagesTotal);
            }
        }

        return $pages;
    }
}
