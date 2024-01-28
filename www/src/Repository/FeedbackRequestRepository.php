<?php

namespace App\Repository;

use App\Entity\FeedbackRequest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<FeedbackRequest>
 *
 * @method FeedbackRequest|null find($id, $lockMode = null, $lockVersion = null)
 * @method FeedbackRequest|null findOneBy(array $criteria, array $orderBy = null)
 * @method FeedbackRequest[]    findAll()
 * @method FeedbackRequest[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FeedbackRequestRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FeedbackRequest::class);
    }

    public function create(FeedbackRequest $request): void
    {
        $this->getEntityManager()->persist($request);
        $this->getEntityManager()->flush();
    }

    public function update(): void
    {
        $this->getEntityManager()->flush();
    }
}
