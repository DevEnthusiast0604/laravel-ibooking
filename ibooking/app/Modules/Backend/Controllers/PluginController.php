<?php
/**
 * Created by PhpStorm.
 * User: Jream
 * Date: 5/12/2020
 * Time: 11:33 PM
 */

namespace App\Modules\Backend\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;

class PluginController extends Controller
{
    //private $url = 'https://plugins.booteam.co/index.php';
    private $url = 'http://localhost/ext/plugins/index.php';

    public function updatePluginAction(Request $request)
    {
        $params = $request['params'];
        if(!empty($params)){
            $params = json_decode(base64_decode($params), true);
            if(isset($params['plugin'])){
                $plugin = $params['plugin'];
                $folder = $params['folder'];
                $download = send_curl($this->url, [
                    'action' => 'install',
                    'ext' => $plugin
                ]);
                if (!empty($download)) {
                    $content = @file_get_contents($download);
                    if (strpos($http_response_header[0], "200")) {
                        $file_path = app_path('Plugins/' . trim($folder));
                        $public_file_path = public_path('plugins/' . trim($plugin));
                        rmdir_recursive($public_file_path);
                        rmdir_recursive($file_path);

                        $file_name = app_path('Plugins/' . trim($plugin) . '.zip');
                        $saved = copy($download, $file_name);
                        if ($saved) {
                            $zip = new \ZipArchive();
                            $zip->open($file_name, \ZipArchive::CREATE);
                            $extracted = $zip->extractTo(app_path('Plugins/'));
                            $zip->close();
                            File::delete($file_name);
                            if ($extracted) {

                                Artisan::call('plugin:link');

                                return response()->json([
                                    'status' => true,
                                    'title' => __('System Alert'),
                                    'message' => __('The Plugin has been updated successfully'),
                                    'reload' => true
                                ]);
                            }
                        }
                    }
                    return response()->json([
                        'status' => false,
                        'title' => __('System Alert'),
                        'message' => __('Can not update this plugin. Please try again!'),
                        'reload' => true
                    ]);
                }
            }
        }
        return response()->json([
            'status' => false,
            'message' => __('Data is invalid')
        ]);
    }

    public function installPluginAction(Request $request)
    {
        $params = $request['params'];
        if(!empty($params)){
            $params = json_decode(base64_decode($params), true);
            if(isset($params['plugin'])){
                $plugin = $params['plugin'];
                $download = send_curl($this->url, [
                    'action' => 'install',
                    'ext' => $plugin
                ]);
                if (!empty($download)) {
                    $content = @file_get_contents($download);
                    if (strpos($http_response_header[0], "200")) {
                        $file_name = app_path('Plugins/' . trim($plugin) . '.zip');
                        $saved = copy($download, $file_name);
                        if ($saved) {
                            $zip = new \ZipArchive();
                            $zip->open($file_name, \ZipArchive::CREATE);
                            $extracted = $zip->extractTo(app_path('Plugins/'));
                            $zip->close();
                            File::delete($file_name);
                            if ($extracted) {

                                Artisan::call('plugin:link');

                                return response()->json([
                                    'status' => true,
                                    'title' => __('System Alert'),
                                    'message' => __('The Plugin has been installed successfully'),
                                    'button' => [
                                        'text' => __('Activate'),
                                        'action' => dashboard_url('active-plugin'),
                                        'class' => [
                                            'remove' => 'btn-primary',
                                            'add' => 'btn-success'
                                        ]
                                    ]
                                ]);
                            }
                        }
                    }
                    return response()->json([
                        'status' => false,
                        'title' => __('System Alert'),
                        'message' => __('Can not install this plugin. Please try again!'),
                        'reload' => true
                    ]);
                }
            }
        }
        return response()->json([
            'status' => false,
            'message' => __('Data is invalid')
        ]);
    }

    public function newPluginView()
    {
        $resp = send_curl($this->url, [
            'action' => 'all'
        ]);
        $plugins = json_decode($resp, true);

        $folders = glob(app_path('Plugins/*'), GLOB_ONLYDIR);
        if (!empty($folders)) {
            foreach ($folders as $k => $folder) {
                $currentPlugins = strtolower(basename($folder));
                if(isset($plugins[$currentPlugins])){
                    unset($plugins[$currentPlugins]);
                }
            }
        }

        return $this->getView($this->getFolderView('plugins.new'), ['plugins' => $plugins]);
    }

    public function deactivatePluginAction(Request $request)
    {
        $params = $request['params'];
        if(!empty($params)){
            $params = json_decode(base64_decode($params), true);
            if(isset($params['plugin'])){
                $actives = get_opt('current_plugins', []);
                if (($key = array_search($params['plugin'], $actives)) !== false) {
                    unset($actives[$key]);
                }
                update_opt('current_plugins', json_encode($actives));
                return response()->json([
                    'status' => true,
                    'message' => __('Deactivate plugin successfully!'),
                    'reload' => true
                ]);
            }
        }
        return response()->json([
            'status' => false,
            'message' => __('Data is invalid')
        ]);
    }

    public function deletePluginAction(Request $request)
    {
        $params = $request['params'];
        if(!empty($params)){
            $params = json_decode(base64_decode($params), true);
            if(isset($params['plugin'])){
                $plugin = $params['plugin'];
                $folder = $params['folder'];
                $file_path = app_path('Plugins/' . trim($folder));
                $public_file_path = public_path('plugins/' . trim($plugin));
                rmdir_recursive($public_file_path);
                rmdir_recursive($file_path);

                $actives = get_opt('current_plugins', []);
                if (($key = array_search($plugin, $actives)) !== false) {
                    unset($actives[$key]);
                }
                update_opt('current_plugins', json_encode($actives));

                return response()->json([
                    'status' => true,
                    'title' => __('System Alert'),
                    'message' => __('The Plugin has been removed successfully'),
                    'reload' => true
                ]);
            }
        }
        return response()->json([
            'status' => false,
            'message' => __('Data is invalid')
        ]);
    }

    public function activePluginAction(Request $request)
    {
        $params = $request['params'];
        if(!empty($params)){
            $params = json_decode(base64_decode($params), true);
            if(isset($params['plugin'])){
                $currentRoute = $params['currentRoute'];

                $actives = get_opt('current_plugins', []);
                if(!isset($actives[$params['plugin']])){
                    $actives[] = $params['plugin'];
                }
                update_opt('current_plugins', json_encode($actives));
                $responData = [
                    'status' => true,
                    'message' => __('Active plugin successfully!'),
                ];

                if($currentRoute == 'plugins'){
                    $responData['reload'] = true;
                }elseif($currentRoute == 'plugin.new'){
                    $responData['redirect'] = route('plugins');
                }
                return response()->json($responData);
            }
        }
        return response()->json([
            'status' => false,
            'message' => __('Data is invalid')
        ]);
    }

    public function pluginView(Request $request)
    {
        $folders = glob(app_path('Plugins/*'), GLOB_ONLYDIR);
        $plugins = [];
        if (!empty($folders)) {
            foreach ($folders as $k => $folder) {
                $pluginSlug = strtolower(basename($folder));
                if (file_exists($folder . '/index.php')) {
                    $indexContent = file_get_contents($folder . '/index.php');
                    $tokens = token_get_all($indexContent);
                    $comment = array(
                        T_COMMENT,
                        T_DOC_COMMENT
                    );
                    foreach ($tokens as $token) {
                        if (!in_array($token[0], $comment))
                            continue;
                        $txt = explode("\r\n", $token[1]);
                        $plugin = [];
                        if (!empty($txt)) {
                            foreach ($txt as $v) {
                                if (strpos($v, ': ') !== false) {
                                    $arr = explode(': ', $v);
                                    if (count($arr) == 2) {
                                        $plugin[trim($arr[0])] = trim($arr[1]);
                                    }
                                }
                            }
                        }
                        $plugins[$pluginSlug] = $plugin;
                    }
                }
                $plugins[$pluginSlug]['slug'] = $pluginSlug;
                $plugins[$pluginSlug]['folderName'] = basename($folder);
                if (file_exists($folder . '/screenshot.png')) {
                    $plugins[$pluginSlug]['screenshot'] = asset('plugins/' . $pluginSlug . '/screenshot.png');
                }
            }
        }
        $actives = get_opt('current_plugins', []);
        if(!empty($actives)){
            $activePlugins = [];
            foreach ($actives as $item){
                if(isset($plugins[$item])){
                    $activePlugins[$item] = $plugins[$item];
                    unset($plugins[$item]);
                }
            }
            $plugins = $activePlugins + $plugins;
        }

        //Get server version
        $resp = send_curl($this->url, [
            'action' => 'version',
            'exts' => json_encode(array_keys($plugins))
        ]);
        $serverVersion = json_decode($resp, true);
        return $this->getView($this->getFolderView('plugins.index'), ['plugins' => $plugins, 'actives' => $actives, 'version' => $serverVersion]);
    }
}