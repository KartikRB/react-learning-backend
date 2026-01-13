<?php

namespace App\Repositories;

use App\Models\Product;

class ProductRepository extends BaseRepository
{
    /**
     * @var string
     */
    public string $modelName = Product::class;
}
