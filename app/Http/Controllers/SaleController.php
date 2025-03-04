<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Category;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SaleController extends Controller
{
    public function index()
    {
        $sales = Sale::with('category', 'images')->where('isSold', false)->get();
        return view('index', compact('sales'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product' => 'required|string|max:255',
            'description' => 'required|string',
            'category_name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'img.*' => 'required|image|max:2048',
        ]);

        // Verificar si la categoría existe, si no, crearla
        $category = Category::firstOrCreate(
            ['name' => $request->category_name],
            ['name' => $request->category_name]
        );

        $sale = Sale::create([
            'user_id' => Auth::id(),
            'product' => $request->product,
            'description' => $request->description,
            'category_id' => $category->id,
            'price' => $request->price,
            'isSold' => false,
        ]);

        // Guardar las imágenes
        if ($request->hasFile('img')) {
            foreach ($request->file('img') as $image) {
                $imgPath = $image->store('images', 'public');
                Image::create([
                    'sale_id' => $sale->id,
                    'ruta' => $imgPath,
                ]);
            }
        }

        return redirect()->route('profile')->with('success', 'Product added successfully.');
    }

    public function show($id)
    {
        $sale = Sale::with('category', 'images')->findOrFail($id);
        return view('product', compact('sale'));
    }

    public function profile()
    {
        $user = Auth::user();
        $sales = Sale::where('user_id', $user->id)->with('category', 'images')->get();
        $categories = Category::all(); // Obtener todas las categorías
        return view('profile', compact('sales', 'categories'));
    }

    public function destroy($id)
    {
        $sale = Sale::findOrFail($id);
        $sale->delete();

        return redirect()->route('profile')->with('success', 'Product deleted successfully.');
    }

    public function toggleSold(Request $request, $id)
    {
        $sale = Sale::findOrFail($id);
        $sale->isSold = !$sale->isSold;
        $sale->save();

        if ($request->ajax()) {
            return response()->json(['success' => true, 'isSold' => $sale->isSold]);
        }

        return redirect()->route('profile')->with('success', 'Product status updated successfully.');
    }

    public function edit($id)
    {
        $sale = Sale::with('images')->findOrFail($id);
        $categories = Category::all();
        return view('edit', compact('sale', 'categories'));
    }

    public function update(Request $request, $id)
{
    $request->validate([
        'product' => 'required|string|max:255',
        'description' => 'required|string',
        'category_name' => 'required|string|max:255',
        'price' => 'required|numeric',
        'img.*' => 'image|max:2048',
    ]);

    $sale = Sale::findOrFail($id);

    // Verificar si la categoría existe, si no, crearla
    $category = Category::firstOrCreate(
        ['name' => $request->category_name],
        ['name' => $request->category_name]
    );

    $sale->update([
        'product' => $request->product,
        'description' => $request->description,
        'category_id' => $category->id,
        'price' => $request->price,
    ]);

    // Guardar las nuevas imágenes
    if ($request->hasFile('img')) {
        foreach ($request->file('img') as $image) {
            $imgPath = $image->store('images', 'public');
            Image::create([
                'sale_id' => $sale->id,
                'ruta' => $imgPath,
            ]);
        }
    }

    return redirect()->route('profile')->with('success', 'Product updated successfully.');
}
public function destroyImage($id)
{
    $image = Image::findOrFail($id);
    $image->delete();

    return redirect()->back()->with('success', 'Image deleted successfully.');
}
}