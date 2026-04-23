<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\Test;

class ProductControllerTest extends TestCase
{
    protected function tearDown(): void
    {
        \Mockery::close();
        parent::tearDown();
    }

    #[Test]
    public function it_can_store_product()
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->image('product.jpg');

        $request = Request::create('/fake', 'POST', [
            'name' => 'Test Product',
            'price' => 1000,
            'stock' => 10,
            'description' => 'Test Desc',
        ], [], [
            'image' => $file
        ]);

        app()->instance('request', $request);

        $productMock = \Mockery::mock('overload:' . \App\Models\Product::class);
        $productMock->shouldReceive('save')->once();

        $controller = new \App\Http\Controllers\ProductController();

        $response = $controller->store();

        $this->assertNotNull($response);
    }
}