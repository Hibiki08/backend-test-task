<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Domain;

use Raketa\BackendTestTask\Repository\Entity\Product;

final readonly class CartItem
{
    public function __construct(
        private string $uuid,
        private Product $product,
        private int $quantity,
    ) {}

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function getProduct(): Product
    {
        return $this->product;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function addQuantity(int $quantity): self
    {
        return new self(
            $this->uuid,
            $this->product,
            $this->quantity + $quantity,
        );
    }

    public function getProductUuid(): string
    {
        return $this->product->getUuid();
    }

    public function getPrice(): float
    {
        return $this->product->getPrice() * $this->quantity;
    }
}
