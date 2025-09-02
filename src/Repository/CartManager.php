<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Repository;

use Exception;
use Psr\Log\LoggerInterface;
use Raketa\BackendTestTask\Domain\Cart;
use Raketa\BackendTestTask\Infrastructure\Connector;

class CartManager
{
    private const CART_TTL = 24 * 60 * 60;

    public function __construct(
        private readonly Connector $connector,
        private readonly LoggerInterface $logger,
    ) {}

    public function saveCart(string $sessionId, Cart $cart): void
    {
        try {
            $this->connector->set($sessionId, $cart, self::CART_TTL);
        } catch (Exception $e) {
            $this->logger->error('Error saving cart', ['exception' => $e]);
        }
    }

    public function getCart(string $sessionId): ?Cart
    {
        try {
            return $this->connector->get($sessionId);
        } catch (Exception $e) {
            $this->logger->error('Error getting cart', ['exception' => $e]);
        }

        return null;
    }
}
