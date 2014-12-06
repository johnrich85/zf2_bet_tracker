<?php

namespace Bankroll\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

/**
 * @ORM\Entity
 */

class Bankroll {
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    //TODO - need to implement users :D
    /** @ORM\Column(name="`userId`",type="integer") */
    protected $userId;

    /** @ORM\Column(name="`date`",type="datetime") */
    protected $lastChange;

    /** @ORM\Column(name="`amount`",type="float") */
    protected $value;




}