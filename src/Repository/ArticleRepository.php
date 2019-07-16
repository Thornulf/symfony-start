<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Article::class);
    }

    public function getAllArticleByPage($articlePerPage = 20, $pageNumber = 1) {
        $qb = $this->createQueryBuilder('a')
                ->select("a.id, a.title, a.createdAt, a.updatedAt, a.content, 
                                CONCAT_WS(' ', author.firstName, author.name) as fullAuthorName,
                                GROUP_CONCAT(t.tagName SEPARATOR ', ') as tagList")
                ->join("a.author", 'author')
                ->leftJoin('a.tags', 't')
                ->groupBy('a.id')
                ->setMaxResults($articlePerPage)
                ->setFirstResult(($pageNumber-1) * $articlePerPage);

        return $qb->getQuery()->getArrayResult();
    }

    public function getTotalNumberOfArticle() {
        $qb = $this->createQueryBuilder('a')
                    ->select('COUNT(a)');

        return $qb->getQuery()->getSingleScalarResult();
    }

    public function getLastArticles($numberOfArticle) {
        $qb = $this ->createQueryBuilder('l')
                    ->select('l')
                    ->orderBy('l.createdAt', 'DESC')
                    ->setMaxResults($numberOfArticle);

        return $qb->getQuery()->getResult();
    }

    // /**
    //  * @return Article[] Returns an array of Article objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Article
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
