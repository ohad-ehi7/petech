<?php
/**
 * Category Controller
 * 
 * Handles all category-related operations including CRUD operations.
 *
 * @category Controllers
 * @package  App\Http\Controllers
 * @author   ChangChang Team <team@changchang.com>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://github.com/changchang
 */

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * Class CategoryController
 * 
 * Manages category operations including creation, updates, and deletion.
 *
 * @category Controllers
 * @package  App\Http\Controllers
 * @author   ChangChang Team <team@changchang.com>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://github.com/changchang
 */
class CategoryController extends Controller
{
    /**
     * Display a listing of the categories.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $categories = Category::with('products')->get();
        return view('category-list', compact('categories'));
    }

    /**
     * Show the form for creating a new category.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('new-category');
    }

    /**
     * Store a newly created category in storage.
     *
     * @param Request $request The incoming request containing category data
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'CategoryName' => 'required|string|max:255|unique:categories,CategoryName'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        Category::create($request->all());

        return redirect()->route('categories.index')
            ->with('success', 'Category created successfully.');
    }

    /**
     * Display the specified category.
     *
     * @param Category $category The category to display
     *
     * @return \Illuminate\View\View
     */
    public function show(Category $category)
    {
        $category->load('products');
        return view('category-overview', compact('category'));
    }

    /**
     * Show the form for editing the specified category.
     *
     * @param Category $category The category to edit
     *
     * @return \Illuminate\View\View
     */
    public function edit(Category $category)
    {
        return view('edit-category', compact('category'));
    }

    /**
     * Update the specified category in storage.
     *
     * @param Request  $request  The incoming request containing updated category data
     * @param Category $category The category to update
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Category $category)
    {
        $validator = Validator::make($request->all(), [
            'CategoryName' => 'required|string|max:255|unique:categories,CategoryName,' . $category->CategoryID . ',CategoryID'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $category->update($request->all());

        return redirect()->route('categories.index')
            ->with('success', 'Category updated successfully.');
    }

    /**
     * Remove the specified category from storage.
     *
     * @param Category $category The category to delete
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Category $category)
    {
        // Check if category has products
        if ($category->products()->count() > 0) {
            return redirect()->route('categories.index')
                ->with('error', 'Cannot delete category with associated products.');
        }

        $category->delete();

        return redirect()->route('categories.index')
            ->with('success', 'Category deleted successfully.');
    }
}
