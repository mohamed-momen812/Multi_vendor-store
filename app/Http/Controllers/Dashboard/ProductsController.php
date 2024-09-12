<?php

namespace App\Http\Controllers\Dashboard;

use App\Helpers\Controllers\UplodeImage;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // with() eager loading: accepting relations i define in Product model to buid query with it, it is semplfy query or reduce
     public function index()
    {
        $products = Product::with(['category', 'store'])->paginate();

        return view("dashboard.products.index", compact("products"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $product = Product::findOrFail($id);

        // tags(): for access relation to get tags as a collection, so use pluck('name') to return only requried name, and convert it to array to simple convert it to string to show at the page
        // pluck('name'): This returns a collection of tag names.
        $tags = implode(",", $product->tags()->pluck('name')->toArray());

        return view("dashboard.products.edit", compact("product", 'tags'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product, UplodeImage $image)
    {
        $data = $request->except('tags', 'image');

        $old_image = $product->image;



        $path = $image->StoreImageAndGettingPass($request);

        $data['image'] = $path ?? $old_image;

        $product->update($data);

        $tags = json_decode($request->post('tags')); // convert json from pakage tagify to an array
        $tag_ids = [];

        $saved_tags = Tag::all(); // for performance: use it here one time to not use it many time in foreach to minemize queries

        if($tags){
            foreach ($tags as $item) {
                $slug = Str::slug($item->value);
                $tag = $saved_tags->where('slug', $slug)->first(); // this is not query cuase i have the collection already
                if (!$tag) {
                    $tag = Tag::create([
                        'name'=> $item->value,
                        'slug'=> $slug,
                    ]);
                }
                $tag_ids[] = $tag->id;
            }
            $product->tags()->sync($tag_ids); // It takes an array of IDs ($tag_ids in this case) and ensures that only those IDs are associated with the model.
        }                                     // Any existing associations that are not in the array will be removed, and any new ones will be added.
        return redirect()->route('dashboard.products.index')->with('success','Product updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }




}
