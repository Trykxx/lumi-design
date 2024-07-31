<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @extends ServiceEntityRepository<Product>
 */
class ProductRepository extends ServiceEntityRepository
{
    private PaginatorInterface $paginator;

    public function __construct(ManagerRegistry $registry, PaginatorInterface $paginator)
    {
        parent::__construct($registry, Product::class);
        $this->paginator = $paginator;
    }

    public function paginateProduct(int $page): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->createQueryBuilder('p'),
            $page,
            10
        );
    }
    public function paginateProductOrderByUpdatedAt(int $page): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->createQueryBuilder('p')
            ->orderBy('p.updatedAt', 'DESC'),
            $page,
            10
        );
    }

    public function findWithCategory(string $slug): ?Product
    {
        return $this->createQueryBuilder('p') // transforme un objet en requete sql pour Product
            ->innerJoin('p.category', 'c') // Left join  // inner join va recuperer si ya concordance des deux tables
            ->where('p.slug = :slug') // condition, :
            ->setParameter('slug', $slug) // on definit la valeur du parametre slug
            ->getQuery() // execute la requete
            ->getOneOrNullResult(); // retourne le resultat ou null si il ne l'est pas
    }
    //    /**
    //     * @return Product[] Returns an array of Product objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Product
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
