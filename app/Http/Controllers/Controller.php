<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use TorMorten\Eventy\Facades\Eventy;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function sendJson($data, $withDie = false)
    {
        if ($withDie) {
            echo json_encode($data);
            die;
        } else {
            return response()->json($data);
        }
    }

    protected function getFolderView($view){
        if(is_admin()){
            return 'Backend::screens.admin.' . $view;
        }elseif(is_partner()){
            $path = Eventy::filter('gmz_partner_path', 'Backend::screens.partner.', $view);
            return $path . $view;
        }else{
            return 'Backend::screens.customer.' . $view;
        }
    }

    protected function getView($view, $data = []){
        if(view()->exists($view)){
            return view( $view, $data);
        }else{
            return response()->redirectTo('dashboard');
        }
    }
}
