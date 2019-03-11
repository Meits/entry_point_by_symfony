<?php
/**
 * Created by PhpStorm.
 * User: MeitsWorkPc
 * Date: 10.03.2019
 * Time: 21:39
 */

namespace App\Services;


class Mailer
{
    private $transport;

    public function __construct()
    {
        $this->transport = 'sendmail';
    }

    public function send() {
        echo $this->transport;
    }
}