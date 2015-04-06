<?php namespace App\Http\Controllers;

use App\Models\Category;
use Request;

class CategoriesController extends Controller {

    public function index()
    {
        return view('category.categories', ['categories' => Category::all()->sortBy('category_name')]);
    }

    public function save($id = 0)
    {
        $category_name = strtolower(Request::input('category_name'));
        $labels = strtolower(Request::input('labels'));

        $category = null;

        if($id > 0)
        {
            $category = Category::find($id);
        }

        if(is_null($category))
        {
            $category = new Category();
        }

        $category->category_name = $category_name;
        $category->labels = $labels;

        $category->save();
    }

    public function delete($id = 0)
    {
        Category::find($id)->delete();
    }
}
