<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class ProductController extends Controller
{
    protected $jsonFileName = 'products.json';

    public function index()
    {
        $products = Product::orderBy('datetime_submitted', 'desc')->get();
        if (!Storage::exists($this->jsonFileName)) {
            Storage::put($this->jsonFileName, json_encode([]));
        }
        return view('products', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
                               'product_name'      => 'required|string',
                               'quantity_in_stock' => 'required|integer',
                               'price_per_item'    => 'required|numeric',
                           ]);
        $product = Product::create([
                                       'product_name'      => $request->product_name,
                                       'quantity_in_stock' => $request->quantity_in_stock,
                                       'price_per_item'    => $request->price_per_item,
                                       'datetime_submitted'=> Carbon::now(),
                                   ]);
        $this->appendToJson($product);
        return response()->json([
                                    'status'  => 'success',
                                    'message' => 'Product saved successfully.',
                                    'data'    => $product
                                ], 200);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
                               'product_name'      => 'required|string',
                               'quantity_in_stock' => 'required|integer',
                               'price_per_item'    => 'required|numeric',
                           ]);

        $product = Product::findOrFail($id);

        $product->update([
                             'product_name'      => $request->product_name,
                             'quantity_in_stock' => $request->quantity_in_stock,
                             'price_per_item'    => $request->price_per_item,
                         ]);

        $this->regenerateJsonFromDB();

        $allProducts = Product::orderBy('datetime_submitted', 'desc')->get();
        return response()->json([
                                    'status'  => 'success',
                                    'message' => 'Product updated successfully.',
                                    'data'    => $allProducts
                                ], 200);
    }

    /**
     * Append a single Product to the existing JSON file.
     */
    private function appendToJson(Product $product)
    {
        $json = Storage::get($this->jsonFileName);
        $data = json_decode($json, true) ?: [];

        $data[] = [
            'id'                => $product->id,
            'product_name'      => $product->product_name,
            'quantity_in_stock' => $product->quantity_in_stock,
            'price_per_item'    => $product->price_per_item,
            'datetime_submitted'=> $product->datetime_submitted,
        ];

        Storage::put($this->jsonFileName, json_encode($data, JSON_PRETTY_PRINT));
    }

    private function regenerateJsonFromDB()
    {
        $allProducts = Product::orderBy('datetime_submitted', 'desc')->get();
        $data = [];

        foreach ($allProducts as $p) {
            $data[] = [
                'id'                => $p->id,
                'product_name'      => $p->product_name,
                'quantity_in_stock' => $p->quantity_in_stock,
                'price_per_item'    => $p->price_per_item,
                'datetime_submitted'=> $p->datetime_submitted,
            ];
        }
        Storage::put($this->jsonFileName, json_encode($data, JSON_PRETTY_PRINT));
    }

    public function destroy($id)
    {
        // Find product in DB
        $product = Product::findOrFail($id);

        // Delete from DB
        $product->delete();

        // Regenerate JSON from DB
        $this->regenerateJsonFromDB();

        // Return the updated product list
        $allProducts = Product::orderBy('datetime_submitted', 'desc')->get();

        return response()->json([
                                    'status'  => 'success',
                                    'message' => 'Product deleted successfully.',
                                    'data'    => $allProducts
                                ], 200);
    }

}
