<?php namespace Scraper\Repository;

use Application\AppClasses\Repository as AppRepository;
use Doctrine\ORM\Query\Expr\Join;
use Scraper\Entity\Source;

class SourcePageRepository extends AppRepository\TaRepository {

    protected $entitySimpleName = 'SourcePage';

    /**
     * Eager load not working for some reason,
     * so here is a temp workaround!
     *
     * @param $id
     * @return bool|null|object
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\TransactionRequiredException
     */
    public function eagerFind($id) {
        $page = $this->_em
            ->find('Scraper\Entity\SourcePage', $id);

        if(!$page)
            return false;

        $sourceId = $page->getSource();

        $source = $this->_em
            ->find('Scraper\Entity\Source', $sourceId);

        $page->setSource($source);

        return $page;
    }

    /**
     * @param Source $source
     * @return array
     */
    public function allForSource(Source $source) {
        $qb = $this->_em->createQueryBuilder();

        $qb->select('sp')
            ->from('Scraper\Entity\Source', 'p')
            ->join('Scraper\Entity\SourcePage', 'sp', Join::WITH, $qb->expr()->eq('p.id', 'sp.source'))
            ->where('p = :source')
            ->setParameters(array('source' => $source));

        return $qb->getQuery()->getResult();
    }

}
