<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Repository;

use Doctrine\DBAL\Connection;
use Raketa\BackendTestTask\Repository\Entity\Product;

class ProductRepository
{
    public function __construct(private Connection $connection) {}

    public function getByUuid(string $uuid): Product
    {
        $row = $this->connection->fetchAssociative(
            'SELECT * FROM products WHERE uuid = ?',
            [$uuid]
        );

        if (empty($row)) {
            throw new \Exception('Product not found');
        }

        return $this->createFromRow($row);
    }

    /** @return Product[] */
    public function getByCategory(string $category): array
    {
        return array_map(
            fn (array $row): Product => $this->createFromRow($row),
            $this->connection->fetchAllAssociative(
                'SELECT * FROM products WHERE is_active = 1 AND category = ?',
                [$category]
            )
        );
    }

    private function createFromRow(array $row): Product
    {
        return new Product(
            $row['id'],
            $row['uuid'],
            $row['is_active'],
            $row['category'],
            $row['name'],
            $row['description'],
            $row['thumbnail'],
            $row['price'],
        );
    }
}
