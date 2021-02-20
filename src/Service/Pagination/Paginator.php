<?php


namespace App\Service\Pagination;

use Doctrine\ORM\QueryBuilder;
use Knp\Component\Pager\Pagination\SlidingPagination;
use Knp\Component\Pager\PaginatorInterface;

class Paginator
{
    /** @var integer */
    const DEFAULT_LIMIT = 100;

    /** @var PaginatorInterface */
    private $paginator;

    public function __construct(PaginatorInterface $paginator)
    {
        $this->paginator = $paginator;
    }

    /**
     * @param $target
     * @param QueryBuilder|array $params
     * @return array
     */
    public function paginate($target, array $params): array
    {
        $page = $params['page'] ?? 1;
        $limit = $params['limit'] ?? self::DEFAULT_LIMIT;

        /** @var SlidingPagination $result */
        $result = $this->paginator->paginate($target, $page, $limit, ['wrap-queries' => true]);

        $page = $result->getCurrentPageNumber();
        $total = $result->getTotalItemCount();
        $limit = $result->getItemNumberPerPage();

        return [
            'items' => $result->getItems(),
            'pagination' => compact('page', 'total', 'limit'),
        ];
    }
}