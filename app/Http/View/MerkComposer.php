<?php 

namespace App\Http\View;

use App\Models\Merk;
use Illuminate\View\View;

class MerkComposer
{
  public function compose(View $view){
    $merks = Merk::orderBy('name', 'asc')->get();

    $view->with('merks', $merks);
  }
}