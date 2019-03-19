<?php
/**
 * Created by PhpStorm.
 * User: MeitsWorkPc
 * Date: 20.02.2019
 * Time: 22:07
 */

namespace App\System\Config;


use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\FileLocator;

class Config
{

    private $loader;
    private $locator;

    private $config = [];

    /**
     * @return mixed
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @param YamlConfigLoader $loader
     */
    public function setLoader($locator)
    {
        $this->loader =  new YamlConfigLoader($locator);
    }

    /**
     * @param FileLocator $locator
     */
    public function setLocator($directories)
    {
        $this->locator =  new FileLocator($directories);
    }
    /**
     * Config constructor.
     */
    public function __construct($dir)
    {
        $directories = array(BASEPATH.'/'.$dir);

        $this->setLocator($directories);
        $this->setLoader($this->locator);
    }


    public function addConfig($file) {
        $configValues = $this->loader->load($this->locator->locate($file));
        if($configValues) {
            foreach ($configValues as $key => $arr) {
                $this->config[$key] = $arr;
            }
        }
    }


    public function get($keyValue) {
        if(strpos($keyValue,".")) {
            list($key, $value) = explode('.', $keyValue);
        }
        else {
            $key = $keyValue;
            $value = null;
        }
        if($key && isset($this->config[$key])) {
            if($value && isset($this->config[$key][$value])) {
                return $this->config[$key][$value];
            }
            else {
                return $this->config[$key];
            }
        }

    }
}