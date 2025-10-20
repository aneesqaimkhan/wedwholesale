<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tenant\Product;
use App\Models\Tenant\Customer;
use App\Models\Tenant\Order;
use App\Models\Tenant\OrderItem;

class TenantDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create sample products
        $products = [
            [
                'name' => 'Laptop Computer',
                'description' => 'High-performance laptop for business use',
                'price' => 1299.99,
                'stock_quantity' => 50,
                'sku' => 'LAPTOP-001',
                'category' => 'Electronics',
                'is_active' => true,
            ],
            [
                'name' => 'Office Chair',
                'description' => 'Ergonomic office chair with lumbar support',
                'price' => 299.99,
                'stock_quantity' => 25,
                'sku' => 'CHAIR-001',
                'category' => 'Furniture',
                'is_active' => true,
            ],
            [
                'name' => 'Wireless Mouse',
                'description' => 'Bluetooth wireless mouse with precision tracking',
                'price' => 49.99,
                'stock_quantity' => 100,
                'sku' => 'MOUSE-001',
                'category' => 'Electronics',
                'is_active' => true,
            ],
            [
                'name' => 'Desk Lamp',
                'description' => 'LED desk lamp with adjustable brightness',
                'price' => 79.99,
                'stock_quantity' => 30,
                'sku' => 'LAMP-001',
                'category' => 'Furniture',
                'is_active' => true,
            ],
        ];

        foreach ($products as $productData) {
            Product::create($productData);
        }

        // Create sample customers
        $customers = [
            [
                'name' => 'John Doe',
                'email' => 'john.doe@example.com',
                'phone' => '+1-555-0123',
                'address' => '123 Main St, City, State 12345',
                'company_name' => 'ABC Corporation',
                'is_active' => true,
            ],
            [
                'name' => 'Jane Smith',
                'email' => 'jane.smith@example.com',
                'phone' => '+1-555-0456',
                'address' => '456 Oak Ave, City, State 12345',
                'company_name' => 'XYZ Ltd',
                'is_active' => true,
            ],
            [
                'name' => 'Bob Johnson',
                'email' => 'bob.johnson@example.com',
                'phone' => '+1-555-0789',
                'address' => '789 Pine Rd, City, State 12345',
                'company_name' => 'Tech Solutions Inc',
                'is_active' => true,
            ],
        ];

        foreach ($customers as $customerData) {
            Customer::create($customerData);
        }

        // Create sample orders
        $customers = Customer::all();
        $products = Product::all();

        if ($customers->count() > 0 && $products->count() > 0) {
            // Create orders for each customer
            foreach ($customers as $customer) {
                $orderCount = rand(1, 3); // 1-3 orders per customer
                
                for ($i = 0; $i < $orderCount; $i++) {
                    $order = Order::create([
                        'order_number' => Order::generateOrderNumber(),
                        'customer_id' => $customer->id,
                        'total_amount' => 0, // Will be calculated
                        'status' => ['pending', 'processing', 'shipped', 'delivered'][rand(0, 3)],
                        'notes' => 'Sample order for demonstration',
                        'order_date' => now()->subDays(rand(1, 30)),
                    ]);

                    // Create order items
                    $itemCount = rand(1, 3); // 1-3 items per order
                    $totalAmount = 0;

                    for ($j = 0; $j < $itemCount; $j++) {
                        $product = $products->random();
                        $quantity = rand(1, 5);
                        $price = $product->price;

                        OrderItem::create([
                            'order_id' => $order->id,
                            'product_id' => $product->id,
                            'quantity' => $quantity,
                            'price' => $price,
                        ]);

                        $totalAmount += $quantity * $price;
                    }

                    // Update order total
                    $order->update(['total_amount' => $totalAmount]);
                }
            }
        }
    }
}
