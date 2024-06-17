<?php

namespace Tests\Unit;

use Tests\TestCase;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use App\Console\Commands\UpdateProductSaleStatus;
use App\Repositories\Eloquent\EloquentProductRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;


class UpdateProductSaleStatusTest extends TestCase
{
    // use RefreshDatabase;

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
        /** $productRepository ProductRepositoryInterface::class; */
        $productRepository = $this->createMock(EloquentProductRepository::class);
        $productRepository->method('getAll')->willReturn(new Collection($this->getProductData()));

        $productRepository->expects($this->exactly(3))
            ->method('updateOnSaleStatus')
            ->withConsecutive(
                [$this->equalTo(1), $this->equalTo(1)],
                [$this->equalTo(2), $this->equalTo(0)],
                [$this->equalTo(3), $this->equalTo(0)]
            );
        $command = new UpdateProductSaleStatus($productRepository);
        $command->handle($productRepository);
    }
}
