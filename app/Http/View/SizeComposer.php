<?php 

namespace App\Http\View;

use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class SizeComposer
{
  public function compose(View $view){
    $sizes = DB::table('keyboard_sizes')->get();

    $view->with('sizes', $sizes);
  }
}