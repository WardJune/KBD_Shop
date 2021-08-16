<?php 

namespace App\Http\View;

use Illuminate\View\View;
use App\Models\Category;

class CategoryComposer
{
  public function compose(View $view){
    $category = Category::get();

    $view->with('category', $category);
  }
}