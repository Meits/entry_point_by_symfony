<?php
/**
 * Created by PhpStorm.
 * User: MeitsWorkPc
 * Date: 11.03.2019
 * Time: 11:24
 */

namespace App\System\Database;


use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;

class Orm
{
    private $params;
    private $entityManager;

    /**
     * Orm constructor.
     * @param $params
     */
    public function __construct($params)
    {
        $this->params = $params;

        $isDevMode = true;

        $config = Setup::createAnnotationMetadataConfiguration(array("src/Entities"), $isDevMode);
//$config = Setup::createXMLMetadataConfiguration(array("config/xml"), $isDevMode);
//$config = Setup::createYAMLMetadataConfiguration(array("config/yml"), $isDevMode);

        $this->setEntityManager(EntityManager::create($this->params,$config));
    }

    /**
     * @return EntityManager
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }

    /**
     * @param EntityManager $entityManager
     */
    public function setEntityManager($entityManager)
    {
        $this->entityManager = $entityManager;
    }


}