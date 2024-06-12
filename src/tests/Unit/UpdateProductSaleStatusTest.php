<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class UpdateProductSaleStatusTest extends TestCase
{
    use RefreshDatabase;

    public function getProductData()
    {
        return [
            [
                'id' => 1,
                'on_sale_start' => Carbon::now()->subHour(),
                'on_sale_end' => Carbon::now()->addHour(),
                'is_on_sale' => 0
            ],
            [
                'id' => 2,
                'on_sale_start' => Carbon::now()->subHours(2),
                'on_sale_end' => Carbon::now()->subHour(),
                'is_on_sale' => 0
            ],
            [
                'id' => 3,
                'on_sale_start' => Carbon::now()->addHour(),
                'on_sale_end' => Carbon::now()->addHours(2),
                'is_on_sale' => 1
            ]
        ];
    }

    public function testProductSaleStatus()
    {
        $productRepository = $this->createMock(ProductRepositoryInterface::class);
        $productRepository->method('getAll')->willReturn(new Collection($this->getProductData()));

        $productRepository->expects($this->exactly(3))
            ->method('update')
            ->withConsecutive(
                [$this->equalTo(1), $this->equalTo(['is_on_sale' => 1])],

                [$this->equalTo(2), $this->equalTo(['is_on_sale' => 0])],
                [$this->equalTo(3), $this->equalTo(['is_on_sale' => 0])]
            );

        $products = $productRepository->getAll();

        foreach ($products as $product) {
            if (now()->greaterThanOrEqualTo($product['on_sale_start']) && now()->lessThanOrEqualTo($product['on_sale_end'])) {
                $productRepository->update($product['id'], ['is_on_sale' => 1]);
            } else {
                $productRepository->update($product['id'], ['is_on_sale' => 0]);
            }
        }
    }
}
