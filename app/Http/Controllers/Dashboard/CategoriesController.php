<?php

namespace App\Http\Controllers\Dashboard;

use App\Helpers\Controllers\UplodeImage;
use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        // $request = request();  // acccess to the request, also we can access to the request via index(Requset $request)
        // $request->query(); // access to params in the request, return array
        // $query = Category::query(); // access to the query builder, create new query builder
        // $categories = Category::simplePaginate(2); // « Previous Next »

        // get categories with parent by joining table, but can access it with relations
        /*
            // select a.*, b.name as parent_name
            // from cotegories as a
            // left join categories as b on b.id = a.parent_id
            $categories = Category::query()
                ->leftJoin('categories as parents', 'parents.id', '=', 'categories.parent_id')
                ->select([
                        'categories.*',
                        'parents.name as parent_name',
                    ])

                ->select('categories.*') // becuase i use selectRaw
                ->selectRaw('( select count(*) from products where category_id = categories.id and status = active ) as products_count') // Add a new "raw" select expression to the query, without column
        */

        // access parent data in each single category, with like populate in node
        $categories = Category::with('parent')
            ->withCount([ // by the relation in category model git all related product
                'products as product_number' => function ($query) {
                    $query->where('status', '=', 'active');
                }
            ])
            ->filter($request->query()) // custom filter form category model, (local scope)
            ->orderBy('categories.id') // built in filter, sorting, by defualt sorting with created_at
            // ->withTrashed() // all with removing by soft delete
            // ->onlyTrashed() // just those removing by soft delete
            ->paginate();

        return view("dashboard.categories.index", compact("categories"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $parents = Category::all();
        $category = new Category(); // empty object to _form where shared with create and edit
        return view("dashboard.categories.create", compact('category', "parents"));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, UplodeImage $image)
    {
        $request->validate(Category::rules(), [
            // redifine the error messages
            'required' => 'This field is required',
            'name.unique' => 'This name is already exists!'// if use name.unique use this message for name only
        ]);

        // Merge new input into the current request's input array
        $request->merge([
            'slug' => Str::slug($request->post('name')),
        ]);

        // we can't merge image becuase we have in req image already so we access on all field of the req except the image on $data and push image field
        $data = $request->except('image');

        $path = $image->StoreImageAndGettingPass($request);

        $data['image'] = $path;


        $category = Category::create($data);

        // PRG: post redirect get
        return Redirect::route('dashboard.categories.index')
            ->with('success','Category created'); // flash message via session when redirect access it in dashboard.categories.index
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category) // access cotegory via oute model binding
    {
        return view('dashboard.categories.show', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id) // $id param, also we can use route model pinding
    {

        $category = Category::find($id);
        if(! $category){
            return Redirect::route('dashboard.categories.index')
            ->with('info','No item found'); // Flash a piece of data to the session.
        }

        /**
            SELECT * FROM categories WHERE id <> $id
            AND (parent_id IS NULL OR parent_id <> $id)
            must use where(function ($query) use ($id) to excute () first
            use $query to access to query in the outer function
            use $id to pass $id to the function, we con't pass it inside function becuase function scope
         */
        $parents = Category::where('id', '<>', $id) // me can't be my father
            ->where(function ($query) use ($id) {
                $query->whereNull('parent_id') // category with out parent will match
                ->orWhere('parent_id', '<>', $id); // my childern can't be my father
            })->get();

        return view('dashboard.categories.edit', compact('category', 'parents'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CategoryRequest $request, string $id, UplodeImage $image)
    {

        // validation in CategoryRequest
        // $request->validate(Category::rules($id)); // if you will not use  validation in CategoryRequest

        $category = Category::findOrFail($id);

        $old_image = $category->image; // access for the pass to the old_image to remove it from disk

        // we can't merge image becuase we have in req image already so we access on all field of the req except the image on $data and push image field
        $data = $request->except('image');

        $path = $image->StoreImageAndGettingPass($request);


        $data['image'] = $path ?? $old_image; // if no pass return the old one

        $category->update($data); // == $category->update($request->all())->save();

        if ($old_image && ($old_image != $data['image'])) {
            Storage::disk('public')->delete($old_image);
        }

        // PRG: post redirect get
        return Redirect::route('dashboard.categories.index')
            ->with('success','Category updated'); // flash message when redirect access it in dashboard.categories.index
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        // $category = Category::findOrFail($id); // not need to find category becouse Category $category found it so i can access to it directly
        $category->delete();

        // if($category->image) { // removing image from public folder if not using soft delete
        //     Storage::disk('public')->delete($category->image);
        // }
        // Category::destroy($id); doesn't return category
        // Category::where('id','=', $id)->delete();

        return Redirect::route('dashboard.categories.index')
            ->with('success','Category deleted');
    }


    public function trash() {
        $categories = Category::onlyTrashed()->paginate();
        return view('dashboard.categories.trash', compact('categories'));
    }

    public function restore($id) {
        $category = Category::onlyTrashed()->findOrFail($id);
        $category->restore();

        return Redirect::route('dashboard.categories.trash')
            ->with('success','Category restored!');
    }

    public function forceDelete($id) {
        $category = Category::onlyTrashed()->findOrFail($id);
        $category->forceDelete();

        if($category->image) { // removing image from public folder if not using soft delete
            Storage::disk('public')->delete($category->image);
        }

        return Redirect::route('dashboared.categories.trash')
            ->with('success','Category deleted forever!');
    }


}
