<?php 

namespace App\Http\View;

use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class KeycapComposer
{
  public function compose(View $view){
    $keycaps = DB::table('keycap_types')->get();

    $view->with('keycaps', $keycaps);
  }
}