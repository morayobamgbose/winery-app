<?php

namespace App\Repository;

use App\Entity\Order;
use App\Entity\Wine;
use App\Twig\Order\OrderExtension;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Order|null find($id, $lockMode = null, $lockVersion = null)
 * @method Order|null findOneBy(array $criteria, array $orderBy = null)
 * @method Order[]    findAll()
 * @method Order[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Order::class);
    }


    public function getAllOrders()
    {
        return $this->createQueryBuilder('o')

            ->orderBy('o.id', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function getOrdersWithUnavailableResponseForWineByDate(Wine $wine,$wineLastAvailableDate, $winePenultimateAvailableDate ){
        return $this->createQueryBuilder('o')
            ->join('o.orderItems', 't')
            ->where('t.available = :available')
            ->andWhere('t.wine = :wine')
            ->andWhere('o.createdAt > :winePenultimateAvailableDate')
            ->andWhere('o.createdAt < :wineLastAvailableDate')
            ->setParameter('available', false)
            ->setParameter('winePenultimateAvailableDate', $winePenultimateAvailableDate)
            ->setParameter('wineLastAvailableDate', $wineLastAvailableDate)
            ->setParameter('wine', $wine)
            ->getQuery()
            ->getResult();
    }
    /*
    public function findOneBySomeField($value): ?Order
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
