<?php
/**
 * Created by PhpStorm.
 * User: Jream
 * Date: 5/12/2020
 * Time: 11:31 PM
 */
namespace App\Modules;
use File;

class ServiceProvider extends  \Illuminate\Support\ServiceProvider{
	public function boot(){
		$listModule = array_map('basename', File::directories(__DIR__));

        $libs = glob(__DIR__.'/../Libraries/*');
        if(!empty($libs)) {
            foreach ( $libs as $item ) {
                if ( file_exists( $item ) ) {
                    include $item;
                }
            }
        }

        include __DIR__.'/../Gateways/BaseGateway.php';

        $helpers = glob(__DIR__.'/../Helpers/*');
        if(!empty($helpers)) {
            foreach ( $helpers as $item ) {
                if ( file_exists( $item ) ) {
                    include $item;
                }
            }
        }

        foreach ($listModule as $module) {
            $configs = glob(__DIR__.'/'.$module.'/Config/*');

            if(!empty($configs)) {
                foreach ( $configs as $item ) {
                    if ( file_exists( $item ) ) {
                        $files = explode('.', basename($item));
                        $this->mergeConfigFrom($item, $files[0]);
                    }
                }
            }

            $this->loadRoutesFrom(__DIR__.'/'.$module.'/Routes/web.php');

			if(is_dir(__DIR__.'/'.$module.'/Views')) {
				$this->loadViewsFrom(__DIR__.'/'.$module.'/Views', $module);
			}
		}
	}
	public function register(){}
}