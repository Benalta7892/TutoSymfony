<?php

namespace App\Repository;

use App\Entity\Recipe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Component\HttpFoundation\Request;

/**
 * @extends ServiceEntityRepository<Recipe>
 */
class RecipeRepository extends ServiceEntityRepository
{
  public function __construct(ManagerRegistry $registry)
  {
    parent::__construct($registry, Recipe::class);
  }

  public function paginateRecipes(Request $request): Paginator
  {
    return new Paginator(
      $this
        ->createQueryBuilder('r')
        ->setFirstResult(0)
        ->setMaxResults(2)
        ->getQuery()
        ->setHint(Paginator::HINT_ENABLE_DISTINCT, false),
      false
    );
  }

  public function findTotalDuration(): int
  {
    return $this->createQueryBuilder('r')
      ->select('SUM(r.duration) as total')
      ->getQuery()
      ->getSingleScalarResult();
  }

  /**
   * @return Recipe[]
   */

  public function findWithDurationLowerThan(int $duration): array
  {
    return $this->createQueryBuilder('r')
      ->where('r.duration <= :duration')
      ->orderBy('r.duration', 'ASC')
      ->setMaxResults(10)
      ->setParameter('duration', $duration)
      ->getQuery()
      ->getResult();
  }

  //    /**
  //     * @return Recipe[] Returns an array of Recipe objects
  //     */
  //    public function findByExampleField($value): array
  //    {
  //        return $this->createQueryBuilder('r')
  //            ->andWhere('r.exampleField = :val')
  //            ->setParameter('val', $value)
  //            ->orderBy('r.id', 'ASC')
  //            ->setMaxResults(10)
  //            ->getQuery()
  //            ->getResult()
  //        ;
  //    }

  //    public function findOneBySomeField($value): ?Recipe
  //    {
  //        return $this->createQueryBuilder('r')
  //            ->andWhere('r.exampleField = :val')
  //            ->setParameter('val', $value)
  //            ->getQuery()
  //            ->getOneOrNullResult()
  //        ;
  //    }
}
