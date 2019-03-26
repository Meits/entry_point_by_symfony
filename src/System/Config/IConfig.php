<?php
/**
 * Created by PhpStorm.
 * User: MeitsWorkPc
 * Date: 26.03.2019
 * Time: 22:06
 */

namespace App\System\Config;


interface IConfig
{
    public function get($keyValue);
    public function addConfig($file);
}