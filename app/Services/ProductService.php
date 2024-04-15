<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Product;
use App\Models\Reorder;
use App\OrderStatus;
use App\Providers\ReorderStatus;

class ProductService
{
  
    public function order(Product $product, $quantity)
    {
//        dd($product, $quantity);

        /*check unfulfilled Orders*/
        if ($product -> unfulfilled_orders >= 1){
            return false;
        }

        $last_id = 1;
        $last_Order = Order::latest()->first();
        if ($last_Order != null)
        {
             $last_id = ($last_Order->id)+1 ;
        }


        /*create order*/
        Order::create([
            'order_id'=> $this->generateOrderNumber($last_id),
            'product_id'=> $product->id,
            'quantity'=> $quantity,
            'status'=> OrderStatus::Unprocessed
        ]);

        /*update product table*/
        $product->increment('unfulfilled_orders');

        return true;
    }

    public function generateOrderNumber($orderId) {
        $orderId = strval($orderId);
        return str_pad($orderId, 6, '0', STR_PAD_LEFT);
    }

    public function processOrder(Order $order)
    {


        /*ge new quantity*/
        $old = $order->product -> quantity;
        $odr = $order-> quantity;
        $new = $old - $odr;

        /*get */
        $product = $order ->product;
        $product->quantity = $new;
        $product-> unfulfilled_orders = $order->product -> unfulfilled_orders - 1;
        $product-> fulfilled_orders = $order->product -> fulfilled_orders + 1;
        $product->save();

        /*Mark Order as processed*/
        $order->update([
            'status'=> OrderStatus::Processed
        ]);

        /* Generate Reorder Notice */
        if ($new <= $order -> product->reorder_stock)
        {
            Reorder::updateOrCreate(
                [
                   'product_id'=> $order->product->id,
                   'status'=> ReorderStatus::Pending,
                ],
                [
                    'product_id'=> $order->product->id,
                    'status'=> ReorderStatus::Pending,
                ]
            );
        }
    }
}
