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

#[Entity, Table( 'transactions' )]
class Transaction
{
    #[Id, Column( options: [ 'unsigned' => true ] ), GeneratedValue]
    private int $id;

    #[Column]
    private string $description;

    #[Column]
    private \DateTime $date;

    #[Column( type: Types::DECIMAL, precision: 13, scale: 3 )]
    private float $amount;

    #[Column( name: 'created_at' )]
    private \DateTime $createdAt;

    #[Column( name: 'updated_at' )]
    private \DateTime $updatedAt;

    #[ManyToOne( inversedBy: 'transactions' )]
    private User $user;

    #[ManyToOne( inversedBy: 'transactions' )]
    private Category $category;

    #[OneToMany( mappedBy: 'transaction', targetEntity: Receipt::class )]
    private Collection $receipts;

    public function __construct()
    {
        $this->receipts = new ArrayCollection();
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
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return Transaction
     */
    public function setDescription( string $description ): Transaction
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDate(): \DateTime
    {
        return $this->date;
    }

    /**
     * @param \DateTime $date
     * @return Transaction
     */
    public function setDate( \DateTime $date ): Transaction
    {
        $this->date = $date;

        return $this;
    }

    /**
     * @return float
     */
    public function getAmount(): float
    {
        return $this->amount;
    }

    /**
     * @param float $amount
     * @return Transaction
     */
    public function setAmount( float $amount ): Transaction
    {
        $this->amount = $amount;

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
     * @return Transaction
     */
    public function setCreatedAt( \DateTime $createdAt ): Transaction
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
     * @return Transaction
     */
    public function setUpdatedAt( \DateTime $updatedAt ): Transaction
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
     * @return Transaction
     */
    public function setUser( User $user ): Transaction
    {
        $user->addTransaction( $this );

        $this->user = $user;

        return $this;
    }

    /**
     * @return Category
     */
    public function getCategory(): Category
    {
        return $this->category;
    }

    /**
     * @param Category $category
     * @return Transaction
     */
    public function setCategory( Category $category ): Transaction
    {
        $category->addTransaction( $this );

        $this->category = $category;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getReceipts(): Collection
    {
        return $this->receipts;
    }

    /**
     * @param Receipt $receipt
     * @return Transaction
     */
    public function addReceipt( Receipt $receipt ): Transaction
    {
        $this->receipts->add( $receipt );

        return $this;
    }
}
