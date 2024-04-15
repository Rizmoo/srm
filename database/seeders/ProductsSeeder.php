<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products= [
            [
                'name'=> 'Omo 1kg',
                'reorder_stock'=> 100,
                'quantity'=> 300,
            ],
            [
                'name'=> 'Mumias Sugar 1kg',
                'reorder_stock'=> 200,
                'quantity'=> 250,
            ],
            [
                'name'=> 'Cooking Oil 1l',
                'reorder_stock'=> 40,
                'quantity'=> 20,
            ],
            [
                'name'=> 'Dasani water 500ml',
                'reorder_stock'=> 100,
                'quantity'=> 300,
            ],
            [
                'name'=> 'Ken salt',
                'reorder_stock'=> 100,
                'quantity'=> 104,
            ],
            [
                'name'=> 'Brown bread 400 grams',
                'reorder_stock'=> 100,
                'quantity'=> 300,
            ],
            [
                'name'=> 'coca cola 500 ml',
                'reorder_stock'=> 100,
                'quantity'=> 300,
            ],
            [
                'name'=> 'Afya 500 ml',
                'reorder_stock'=> 100,
                'quantity'=> 300,
            ],
            [
                'name'=> 'Red bull',
                'reorder_stock'=> 100,
                'quantity'=> 80,
            ],
            [
                'name'=> 'Dormans Coffee 500 grams',
                'reorder_stock'=> 100,
                'quantity'=> 300,
            ],
        ];

        foreach ( $products as $product)
        {
            Product::create([
                'name'=> $product['name'],
                'reorder_stock'=> $product['reorder_stock'],
                'quantity'=> $product['quantity'],
            ]);
        }
    }
}

