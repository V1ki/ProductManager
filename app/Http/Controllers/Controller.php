<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use App\SHAdmin ;
use App\Customers ;
use Illuminate\Support\Facades\DB;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function allSHInfos(){
        return SHAdmin::get([DB::raw('sh_name_id as id'),DB::raw('sh_name as text')]);
    }

    public function customer(Request $request){
        // `customer_id`,`customer_name`
        $id = $request->get('q');
        return Customers::where('sh_name_id',$id)->get([DB::raw('customer_id as id'),DB::raw('customer_name as text')]);
    }

}
