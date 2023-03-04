<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;

#[Entity, Table( 'categories' )]
class Category
{
    #[Id, Column( options: [ 'unsigned' => true ] ), GeneratedValue]
    private int $id;

    #[Column]
    private string $name;

    #[Column( name: 'created_at' )]
    private \DateTime $createdAt;

    #[Column( name: 'updated_at' )]
    private \DateTime $updatedAt;

    #[ManyToOne( inversedBy: 'categories' )]
    private User $user;

    #[OneToMany( mappedBy: 'category', targetEntity: Transaction::class )]
    private Collection $transactions;

    public function __construct()
    {
        $this->transactions = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Category
     */
    public function setName( string $name ): Category
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     * @return Category
     */
    public function setCreatedAt( \DateTime $createdAt ): Category
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTime $updatedAt
     * @return Category
     */
    public function setUpdatedAt( \DateTime $updatedAt ): Category
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     * @return Category
     */
    public function setUser( User $user ): Category
    {
        $user->addCategory( $this );

        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getTransactions(): Collection
    {
        return $this->transactions;
    }

    /**
     * @param Transaction $transaction
     * @return Category
     */
    public function addTransaction( Transaction $transaction ): Category
    {
        $this->transactions->add( $transaction );

        return $this;
    }
}
