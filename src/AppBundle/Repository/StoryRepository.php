<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\Common\Collections\ArrayCollection as CustomArrayCollection;

/**
* StoryRepository
*
* Repository class for story.
*/
class StoryRepository extends EntityRepository
{
    public function getStoriesCount()
    {
        $db = $this->createQueryBuilder('s')
                ->select('COUNT(s.id) as no_stories');
        $query =$db->getQuery();
        return $query->getSingleScalarResult();
    }

    public function getStory ($no) {
        $db = $this->createQueryBuilder('s')
            ->andWhere('s.id = :id')
            ->setParameter('id', $no);
        $query = $db->getQuery();

        return $query->getResult();
    }

    public function findLarger ($no) {
        $db = $this->createQueryBuilder('s')
            ->andWhere('s.storyOrder >= :val')
            ->setParameter('val', $no);
        $query = $db->getQuery();

        return $query->getResult();
    }

    public function findBetween ($no1,$no2) {
        $db = $this->createQueryBuilder('s')
            ->andWhere('s.storyOrder >= :val1')
            ->andWhere('s.storyOrder >= :val2')
            ->setParameter('val1', $no1)
            ->setParameter('val2', $no2);
        $query = $db->getQuery();

        return $query->getResult();
    }



}
