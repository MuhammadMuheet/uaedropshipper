<?php

namespace App\Exports;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Carbon\Carbon;

class OrdersExport implements FromCollection, WithHeadings, WithMapping
{
    protected $orderIds;
    protected $showPurchaseCost;

    public function __construct($orderIds = null)
    {
        $this->orderIds = $orderIds;
        $this->showPurchaseCost = Auth::check() && in_array(Auth::user()->role, ['admin', 'sub_admin']);
    }

    public function collection()
    {
        $query = Order::with([
            'seller',
            'subSeller',
            'state',
            'area',
            'driver',
            'logisticCompany',
            'orderItems.product' => function ($query) {
                $query->select('id', 'product_name', 'product_image');
            },
            'orderItems.productVariation' => function ($query) {
                $query->select('id', 'variation_name', 'variation_value', 'variation_image');
            },
            'orderItems.productStockBatch' => function ($query) {
                $query->select('id', 'regular_price', 'purchase_price');
            }
        ])->orderBy('id', 'DESC');

        if ($this->orderIds) {
            $query->whereIn('id', $this->orderIds);
        }

        return $query->get();
    }

    public function headings(): array
    {
        $headings = [
            'Date',
            'Delivery Date',
            'Status',
            'Comment Box',
            'Order ID',
            'Tracking No(Only For Courier)',
            'Owner',
            'Customer Name',
            'Total',
            'Calling Number',
            'Shipper Ref(Only For Courier)',
            'Country Code',
            'Billing State',
            'Billing City',
            'Shipping Address',
            'Customer Note',
            'Products',
            'WhatsApp',
            'Billing Area',
            'Order Taker Name',
            'Logistic Company',
            'Driver',
            'Email',
            'Location Link',
            'Costs of Goods',
        ];

        if ($this->showPurchaseCost) {
            $headings[] = 'Purchase Costs of Goods';
        }

        return $headings;
    }

    public function map($order): array
    {
        $products = [];
        $productTotal = [];

        foreach ($order->orderItems as $item) {
            $productName = $item->product ? $item->product->product_name : 'Unknown Product';
            $variationInfo = '';

            if ($item->productVariation) {
                $variationInfo = ' (' . $item->productVariation->variation_name . ': ' .
                    $item->productVariation->variation_value . ')';
            }

            $purchasePrice = $item->productStockBatch->purchase_price ?? 0;
            $productTotal[] = $purchasePrice * $item->quantity;

            $products[] = $productName . $variationInfo . ' qty( ' . $item->quantity . ')';
        }

        $totalPurchaseAmount = array_sum($productTotal);
        $productsString = implode(", ", $products);

        $row = [
            $order->created_at->format('Y-m-d'),
            $order->delivery_date ? Carbon::parse($order->delivery_date)->format('Y-m-d') : 'N/A',
            $order->status,
            ' ',
            ($order->subSeller ? $order->subSeller->unique_id : '') . '-' . $order->id,
            ' ',
            $order->subSeller ? $order->subSeller->unique_id : 'N/A',
            $order->customer_name,
            $order->cod_amount,
            $order->phone,
            ' ',
            'UAE',
            ' ',
            $order->state ? $order->state->state : 'N/A',
            $order->address,
            $order->delivery_instruction ? $order->delivery_instruction : 'N/A',
            $productsString,
            $order->whatsapp,
            $order->area ? $order->area->area : 'N/A',
            $order->subSeller ? ucfirst($order->subSeller->name) : 'N/A',
            $order->logisticCompany ? ucfirst($order->logisticCompany->name) : 'N/A',
            $order->driver ? ucfirst($order->driver->name) : 'N/A',
            $order->subSeller ? $order->subSeller->email : 'N/A',
            $order->map_url ? $order->map_url : 'N/A',
            $order->subtotal ?? 'N/A',
        ];

        if ($this->showPurchaseCost) {
            $row[] = $totalPurchaseAmount;
        }

        return $row;
    }
}
