<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;

#[Entity, Table( 'receipts' )]
class Receipt
{
    #[Id, Column( options: [ 'unsigned' => true ] ), GeneratedValue]
    private int $id;

    #[Column( name: 'file_name' )]
    private string $fileName;

    #[Column( name: 'created_at' )]
    private \DateTime $createdAt;

    #[ManyToOne( inversedBy: 'receipts' )]
    private Transaction $transaction;

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
    public function getFileName(): string
    {
        return $this->fileName;
    }

    /**
     * @param string $fileName
     * @return Receipt
     */
    public function setFileName( string $fileName ): Receipt
    {
        $this->fileName = $fileName;

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
     * @return Receipt
     */
    public function setCreatedAt( \DateTime $createdAt ): Receipt
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return Transaction
     */
    public function getTransaction(): Transaction
    {
        return $this->transaction;
    }

    /**
     * @param Transaction $transaction
     * @return Receipt
     */
    public function setTransaction( Transaction $transaction ): Receipt
    {
        $transaction->addReceipt( $this );

        $this->transaction = $transaction;

        return $this;
    }
}
