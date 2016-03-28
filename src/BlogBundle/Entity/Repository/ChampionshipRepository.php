<?php
namespace BlogBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use BlogBundle\Entity\Taxonomy;

/**
 * ChampionshiptRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */

class ChampionshipRepository extends EntityRepository
{
    public function findByTaxonomy($taxonomySlug, $type, $limit = null)
    {
        $championshipClass = $this->_entityName;
        $query = "SELECT a FROM $championshipClass a";

        if ($type == Taxonomy::TYPE_TAG)
        {
            $query.=" INNER JOIN a.tags tr";
        }else
        {
            $query.=" INNER JOIN a.category tr";
        }

        $query.=" INNER JOIN tr.term t
                  WHERE tr.type=:type AND t.slug=:taxonomySlug
                  ORDER BY a.name DESC"
        ;

        $query = $this->getEntityManager()
            ->createQuery($query)
            ->setParameter("type",$type)
            ->setParameter('taxonomySlug',$taxonomySlug);

        if($limit)
        {
            $query->setMaxResults($limit);
        }

        $championships = $query->useQueryCache(true)->setQueryCacheLifetime(60)->getResult();

        return $championships;
    }
}