<?php

namespace Raketa\BackendTestTask\Controller;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Raketa\BackendTestTask\Domain\Cart;
use Raketa\BackendTestTask\Domain\CartItem;
use Raketa\BackendTestTask\Domain\Customer;
use Raketa\BackendTestTask\Repository\CartManager;
use Raketa\BackendTestTask\Repository\ProductRepository;
use Raketa\BackendTestTask\View\CartView;
use Ramsey\Uuid\Uuid;

readonly class AddToCartController
{
    public function __construct(
        private ProductRepository $productRepository,
        private CartView $cartView,
        private CartManager $cartManager,
    ) {
    }

    public function get(RequestInterface $request): ResponseInterface
    {
        $rawRequest = json_decode($request->getBody()->getContents(), true);
        $product = $this->productRepository->getByUuid($rawRequest['productUuid']);

        $cart = $this->cartManager->getCart(session_id());
        if (!$cart) {
            // Если корзины нет, создаем новую. Юзера берем из базы или сессии.
            $customer = new Customer(1, 'Freddie', 'Freddie Mercury', '', 'freddie.mercury@test.com');
            $cart = new Cart(Uuid::uuid4()->toString(), $customer, 'credit_card', []);
        }

        $cart->addItem(new CartItem(
            Uuid::uuid4()->toString(),
            $product,
            $rawRequest['quantity'],
        ));

        $this->cartManager->saveCart(session_id(), $cart);

        $response = new JsonResponse();
        $response->getBody()->write(
            json_encode(
                [
                    'status' => 'success',
                    'cart' => $this->cartView->toArray($cart)
                ],
                JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
            )
        );

        return $response
            ->withHeader('Content-Type', 'application/json; charset=utf-8')
            ->withStatus(200);
    }
}
