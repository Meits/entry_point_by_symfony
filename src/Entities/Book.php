<?php
/**
 * Created by PhpStorm.
 * User: MeitsWorkPc
 * Date: 11.03.2019
 * Time: 11:32
 */

namespace App\Entities;

/**
 * @Entity(repositoryClass="\Repositories\BookRepository")
 * @Table(name="books")
 */
class Book
{
    /**
     * @id
     * @Column(type="integer")
     * @GeneratedValue
     */
    public $id;

    /**
     * @Column(type="string", name="title_book", length=32, unique=true, nullable=true)
     * @GeneratedValue
     */
    public $title;

}