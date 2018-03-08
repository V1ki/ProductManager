<?php


namespace App\Http\Controllers;

use App\DevModel;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;

class DevModelController extends BaseController
{
    /**
     * All Device Model
     */
    public function all(){
        return DevModel::get(['id', DB::raw('name as text')]);
    }

}
