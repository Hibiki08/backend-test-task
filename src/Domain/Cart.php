<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Domain;

final class Cart
{
    public function __construct(
        readonly private string $uuid,
        readonly private Customer $customer,
        readonly private string $paymentMethod,
        private array $items,
    ) {
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function getCustomer(): Customer
    {
        return $this->customer;
    }

    public function getPaymentMethod(): string
    {
        return $this->paymentMethod;
    }

    public function getItems(): array
    {
        return $this->items;
    }

    public function addItem(CartItem $item): void
    {
        foreach ($this->items as $key => $existingItem) {
            if ($existingItem->getProductUuid() === $item->getProductUuid()) {
                $this->items[$key] = $existingItem->addQuantity($item->getQuantity());
                return;
            }
        }

        $this->items[] = $item;
    }
}
