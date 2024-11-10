<?php

namespace App\Models;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="users")
 */
class User
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $full_name;

    /**
     * @ORM\Column(type="string")
     */
    private $role;

    /**
     * @ORM\Column(type="integer")
     */
    private $efficiency;

    public function getId()
    {
        return $this->id;
    }

    public function getFullName()
    {
        return $this->full_name;
    }

    public function setFullName($full_name)
    {
        $this->full_name = $full_name;
    }

    public function getRole()
    {
        return $this->role;
    }

    public function setRole($role)
    {
        $this->role = $role;
    }

    public function getEfficiency()
    {
        return $this->efficiency;
    }

    public function setEfficiency($efficiency)
    {
        $this->efficiency = $efficiency;
    }
}