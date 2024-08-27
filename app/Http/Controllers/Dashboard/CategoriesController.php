<?php

namespace App\Http\Controllers\Dashboard;

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
    public function index()
    {
        $request = request();  // acccess to the request, also we can access to the request via index(Requset $request)
        // $request->query(); // access to params in the request

        // $query = Category::query(); // access to the query builder

        // $categories = Category::simplePaginate(2); // « Previous Next »

        /*
            select a.*, b.name as parent_name
            from cotegories as a
            left join categories as b on b.id = a.parent_id
         */
        $categories = Category::with('parent') // access parent data in each single category, with = populate in node
            /*
                leftJoin('categories as parents', 'parents.id', '=', 'categories.parent_id')
                ->select([
                    'categories.*',
                    'parents.name as parent_name',
                ])
            */
            // ->select('categories.*') // becuase i use selectRaw
            // ->selectRaw('( select count(*) from products where category_id = categories.id and status = active ) as products_count') // Add a new "raw" select expression to the query, without column
            ->withCount([
                'products as product_number' => function ($query) {
                    $query->where('status','=','active');
                }
            ]) // from relations instead of select
            ->filter($request->query()) // custom filter form model
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
    public function store(Request $request)
    {
        $request->validate(Category::rules(), [
            'required' => 'This field is required',
            'name.unique' => 'This name is already exists!'// if use name.unique use this message for name only
        ]);

        // Merge new input into the current request's input array
        $request->merge([
            'slug' => Str::slug($request->post('name')),
        ]);

        // we can't merge image becuase we have in req image already so we access on all field of the req except the image on $data and push image field
        $data = $request->except('image');

        $path = $this->uploadImage($request);

        $data['image'] = $path;


        $category = Category::create($data);

        // PRG: post redirect get
        return Redirect::route('dashboard.categories.index')
            ->with('success','Category created'); // flash message via session when redirect access it in dashboard.categories.index
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category) // access cotegory form route binding
    {
        return view('dashboard.categories.show', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id) // $id param
    {

        $category = Category::find($id);
        if(! $category){
            return Redirect::route('dashboard.categories.index')
            ->with('info','No item found');
        }

        /**
            SELECT * FROM categories WHERE id <> $id
            AND (parent_id IS NULL OR parent_id <> $id)
            must use where(function ($query) use ($id) to excute () first
            use $query to access to query in the outer function
            use $id to pass $id to the function, we con't pass it inside function becuase function scope
         */
        $parents = Category::where('id', '<>', $id)
            ->where(function ($query) use ($id) {
                $query->whereNull('parent_id')
                ->orWhere('parent_id', '<>', $id);
            })->get();

        return view('dashboard.categories.edit', compact('category', 'parents'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CategoryRequest $request, string $id)
    {

        // validation in CategoryRequest
        // $request->validate(Category::rules($id));

        $category = Category::findOrFail($id);

        $old_image = $category->image; // access for old_image to remove it from disk

        // we can't merge image becuase we have in req image already so we access on all field of the req except the image on $data and push image field
        $data = $request->except('image');

        $path = $this->uploadImage($request);


        $data['image'] = $path ?? $old_image;

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

    protected function uploadImage(Request $request) {
        if(!$request->hasFile('image')) {
            return ;
        }

        $file = $request->file('image');

        $path = $file->store('uploads', [
            'disk'=> 'public',
        ]);

        return $path;
    }

    public function trash() {
        $categories = Category::onlyTrashed()->paginate();
        return view('dashboard.categories.trash', compact('categories'));
    }

    public function restore(Request $request, $id) {
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
