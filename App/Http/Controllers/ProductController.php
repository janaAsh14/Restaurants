<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\MainOrder;
use App\Models\Order;
use App\Models\OrderProduct;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
class ProductController extends Controller
{
    public function index(Request $request)
    {
        // Retrieve products
        $products = Product::all();

        // Generate a random ID
        $randomId = Str::random(10);

        // Assuming you have a user_id (from session or auth, else you can generate a random one)
        $userId = $request->user() ? $request->user()->id : Str::uuid();

        // Store the browsing activity
        User::create([
            'id' => $userId,
            'random-id' => $randomId,
        ]);
        return response()->json([
            'products' => $products,
            'tracking_id' => $randomId, // Optionally return the random ID if needed
        ]);
    }


    public function store(Request $request)
    {
        // Validate the Request
        $request->validate([
            'name' => 'required|string',
            'price' => 'required|numeric',
            'image' => 'required|string',
            'desc' => 'required|integer',
            'active' => 'required|string',
            'section_id' => 'required|integer',
            'category_id' => 'required|integer',
        ]);

        try {
            // After validation, proceed with product creation
            $ownerId = Auth::id();

            if (!$ownerId) {
                return response()->json([
                    'message' => 'Owner ID is not available. Please ensure the user is authenticated.'
                ], 400);
            }

            $product = Product::create([
                'name' => $request->input('name'),
                'owner_id' => $ownerId,
                'price' => $request->input('price'),
                'image' => $request->input('image'),
                'desc' => $request->input('desc'),
                'active' => $request->input('active'),
                'section_id' => $request->input('section_id'),
                'category_id' => $request->input('category_id'),
            ]);


            return response()->json([
                'message' => "Product Created Successfully",
                'product' => $product
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' =>  Auth::id(),
                'error' => $e->getMessage()
            ], 500);
        }
    }



    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string',
            'price' => 'required|numeric',
            'image' => 'required|string',
            'desc' => 'required|integer',
            'active' => 'required|string',
            'section_id' => 'required|integer',
            'category_id' => 'required|integer',
        ]);

        $product = Product::findOrFail($id);
        $product->name = $request->name;
        $product->price = $request->price;
        $product->image = $request->image;
        $product->desc = $request->desc;
        $product->active = $request->active;
        $product->section_id = $request->section_id;
        $product->category_id = $request->category_id;
        $product->save();
        return response()->json([
            'result' => 'Product updated successfully',
            'Product' => $product
        ]);
    }
    public function destroy(Product $product)
    {
        $product = Product::find($product->id);

        if (!$product) {
            return response()->json(['message' => 'Book not found'], 404);
        }


        $deleted = $product->delete();
        if ($deleted) {
            return response()->json(['message' => 'Product Deleted Successfully'], 200);
        } else {
            return response()->json(['message' => 'Product Not Found'], 404);
        }
    }
    public function reserveTable(Request $request, $userId)
    {
        // Validate the request
        $validatedData = $request->validate([

            'table_number' => 'required|integer',
        ]);



        // Link the user to the table by creating or updating the main order record
        $mainOrder = MainOrder::updateOrCreate(
            ['user-id' => $userId],
            ['table' => $validatedData['table_number']]
        );

        return response()->json([
            'message' => 'Table reserved successfully',

            'table_number' => $validatedData['table_number']
        ]);
    }

    public function orderProducts(Request $request, $mainOrderId)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'product_id' => 'required|integer',
            'count' => 'required|integer',
            'notes' => 'required|string',
        ]);

        // Find the product to get its price
        $product = Product::find($validatedData['product_id']);
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        // Update or create the Order
        $order = Order::updateOrCreate(
            ['main_order_id' => $mainOrderId],
            [
                'count' => $validatedData['count'],
                'notes' => $validatedData['notes'],
            ]
        );

        // Update or create the OrderProduct
        OrderProduct::updateOrCreate(
            ['product_id' => $validatedData['product_id']],
            ['order_id' => $order->id]
        );

        // Calculate the total price for the main order
        $totalPrice = OrderProduct::join('orders', 'order_products.order_id', '=', 'orders.id')
            ->join('products', 'order_products.product_id', '=', 'products.id')
            ->where('orders.main_order_id', $mainOrderId)
            ->sum(DB::raw('orders.count * products.price'));

        // Update the MainOrder with the new total price
        $mainOrder = MainOrder::find($mainOrderId);
        if ($mainOrder) {
            $mainOrder->update(['price' => $totalPrice]);
        } else {
            // Handle the case where the main order does not exist
            return response()->json(['message' => 'Main order not found'], 404);
        }

        return response()->json([
            'message' => 'Order added successfully',
        ]);
    }


    public function getSalesData()
    {
        $today = Carbon::today();
        $startOfWeek = Carbon::now()->startOfWeek();
        $startOfMonth = Carbon::now()->startOfMonth();

        $dailySales = MainOrder::whereDate('created_at', $today)
            ->sum('price');

        $weeklySales = MainOrder::whereBetween('created_at', [$startOfWeek, $today])
            ->sum('price');

        $monthlySales = MainOrder::whereBetween('created_at', [$startOfMonth, $today])
            ->sum('price');

        return response()->json([
            'daily_sales' => $dailySales,
            'weekly_sales' => $weeklySales,
            'monthly_sales' => $monthlySales,
        ]);
    }
}


