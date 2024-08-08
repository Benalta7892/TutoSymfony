<?php

namespace App\Repository;

use App\DTO\CategoryWithCountDTO;
use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Query;
use Gedmo\Translatable\Query\TreeWalker\TranslationWalker;
use Gedmo\Translatable\TranslatableListener;



/**
 * @extends ServiceEntityRepository<Category>
 */
class CategoryRepository extends ServiceEntityRepository
{
  public function __construct(ManagerRegistry $registry)
  {
    parent::__construct($registry, Category::class);
  }

  /**
   * @return CategoryWithCountDTO[]
   */
  public function findAllWithCount(): array
  {
    // dd(
    //   $this->getEntityManager()->createQuery(<<<DQL
    //     SELECT NEW App\DTO\CategoryWithCountDTO(c.id, c.name, COUNT(c.id))
    //     FROM App\Entity\Category c
    //     LEFT JOIN c.recipes r
    //     GROUP BY c.id
    //   DQL)->getResult()
    // );

    return $this->createQueryBuilder('c')
      ->select('NEW App\DTO\CategoryWithCountDTO(c.id, c.name, COUNT(c.id))')
      ->leftJoin('c.recipes', 'r')
      ->groupBy('c.id, c.name')
      ->getQuery()
      ->setHint(
        Query::HINT_CUSTOM_OUTPUT_WALKER,
        TranslationWalker::class
      )->setHint(
        // TranslatableListener::HINT_TRANSLATABLE_LOCALE,
        // 'en'
        TranslatableListener::HINT_FALLBACK,
        1
      )
      ->getResult();
  }

  //    /**
  //     * @return Category[] Returns an array of Category objects
  //     */
  //    public function findByExampleField($value): array
  //    {
  //        return $this->createQueryBuilder('c')
  //            ->andWhere('c.exampleField = :val')
  //            ->setParameter('val', $value)
  //            ->orderBy('c.id', 'ASC')
  //            ->setMaxResults(10)
  //            ->getQuery()
  //            ->getResult()
  //        ;
  //    }

  //    public function findOneBySomeField($value): ?Category
  //    {
  //        return $this->createQueryBuilder('c')
  //            ->andWhere('c.exampleField = :val')
  //            ->setParameter('val', $value)
  //            ->getQuery()
  //            ->getOneOrNullResult()
  //        ;
  //    }
}
