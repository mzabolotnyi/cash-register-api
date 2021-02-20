<?php

namespace App\Repository;

use App\Service\Pagination\Paginator;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * @method object|null find($id, $lockMode = null, $lockVersion = null)
 * @method object|null findOneBy(array $criteria, array $orderBy = null)
 * @method object|null findOneByUuid($uuid)
 * @method object[]    findAll()
 * @method object[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
abstract class SuperRepository extends ServiceEntityRepository
{
    /** @var Paginator */
    public $paginator;

    /**
     * @required
     * @param Paginator $paginator
     */
    public function setPaginator(Paginator $paginator)
    {
        $this->paginator = $paginator;
    }

    public function findByParams(array $params): array
    {
        $qb = $this->createQueryBuilder($this->getAlias());

        return $this->paginate($qb, $params);
    }

    protected function getAlias(): string
    {
        return preg_replace('/[^\w]/', '', $this->_entityName);
    }

    protected function paginate(QueryBuilder $qb, array $params = []): array
    {
        if (isset($params['pagination']) && !is_array($params['pagination'])) {
            throw new BadRequestHttpException('Value of key "pagination" must be of type array.');
        }

        return $this->paginator->paginate($qb, $params['pagination'] ?? []);
    }
}