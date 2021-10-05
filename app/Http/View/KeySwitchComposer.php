<?php 

namespace App\Http\View;

use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class KeySwitchComposer
{
  public function compose(View $view){
    $switches = DB::table('key_switchs')->get();

    $view->with('switches', $switches);
  }
}