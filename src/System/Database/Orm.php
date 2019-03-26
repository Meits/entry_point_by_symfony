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
    public function __construct()
    {
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
    public function setEntityManager($params, $pathEntities = array("src/Entities"), $isDevMode = 'dev')
    {

        $config = Setup::createAnnotationMetadataConfiguration($pathEntities, $isDevMode);
        //$config = Setup::createXMLMetadataConfiguration(array("config/xml"), $isDevMode);
        //$config = Setup::createYAMLMetadataConfiguration(array("config/yml"), $isDevMode);

        $this->entityManager = EntityManager::create($params,$config);
    }


}