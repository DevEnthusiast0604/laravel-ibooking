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

class ThemeController extends Controller
{
    //private $url = 'https://plugins.booteam.co/index.php';
    private $url = 'http://localhost/ext/themes/index.php';

    public function updateThemeAction(Request $request)
    {
        $params = $request['params'];
        if(!empty($params)){
            $params = json_decode(base64_decode($params), true);
            if(isset($params['theme'])){
                $theme = $params['theme'];
                $folder = $params['folder'];
                $download = send_curl($this->url, [
                    'action' => 'install',
                    'ext' => $theme
                ]);
                if (!empty($download)) {
                    $content = @file_get_contents($download);
                    if (strpos($http_response_header[0], "200")) {
                        $file_path = app_path('Themes/' . trim($folder));
                        $public_file_path = public_path('themes/' . trim($theme));
                        rmdir_recursive($public_file_path);
                        rmdir_recursive($file_path);

                        $file_name = app_path('Themes/' . trim($theme) . '.zip');
                        $saved = copy($download, $file_name);
                        if ($saved) {
                            $zip = new \ZipArchive();
                            $zip->open($file_name, \ZipArchive::CREATE);
                            $extracted = $zip->extractTo(app_path('Themes/'));
                            $zip->close();
                            File::delete($file_name);
                            if ($extracted) {

                                Artisan::call('theme:link');

                                return response()->json([
                                    'status' => true,
                                    'title' => __('System Alert'),
                                    'message' => __('The Theme has been updated successfully'),
                                    'reload' => true
                                ]);
                            }
                        }
                    }
                    return response()->json([
                        'status' => false,
                        'title' => __('System Alert'),
                        'message' => __('Can not update this theme. Please try again!'),
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

    public function installThemeAction(Request $request)
    {
        $params = $request['params'];
        if(!empty($params)){
            $params = json_decode(base64_decode($params), true);
            if(isset($params['theme'])){
                $theme = $params['theme'];
                $download = send_curl($this->url, [
                    'action' => 'install',
                    'ext' => $theme
                ]);
                if (!empty($download)) {
                    $content = @file_get_contents($download);
                    if (strpos($http_response_header[0], "200")) {
                        $file_name = app_path('Themes/' . trim($theme) . '.zip');
                        $saved = copy($download, $file_name);
                        if ($saved) {
                            $zip = new \ZipArchive();
                            $zip->open($file_name, \ZipArchive::CREATE);
                            $extracted = $zip->extractTo(app_path('Themes/'));
                            $zip->close();
                            File::delete($file_name);
                            if ($extracted) {

                                Artisan::call('theme:link');

                                return response()->json([
                                    'status' => true,
                                    'title' => __('System Alert'),
                                    'message' => __('The Theme has been installed successfully'),
                                    'button' => [
                                        'text' => __('Activate'),
                                        'action' => dashboard_url('active-theme'),
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
                        'message' => __('Can not install this theme. Please try again!'),
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

    public function newThemeView()
    {
        $resp = send_curl($this->url, [
            'action' => 'all'
        ]);
        $themes = json_decode($resp, true);

        $folders = glob(app_path('Themes/*'), GLOB_ONLYDIR);
        if (!empty($folders)) {
            foreach ($folders as $k => $folder) {
                $currentThemes = strtolower(basename($folder));
                if(isset($themes[$currentThemes])){
                    unset($themes[$currentThemes]);
                }
            }
        }

        return $this->getView($this->getFolderView('themes.new'), ['themes' => $themes]);
    }

    public function deactivateThemeAction(Request $request)
    {
        $params = $request['params'];
        if(!empty($params)){
            $params = json_decode(base64_decode($params), true);
            if(isset($params['theme'])){
                update_opt('current_theme', '');
                return response()->json([
                    'status' => true,
                    'message' => __('Deactivate theme successfully!'),
                    'reload' => true
                ]);
            }
        }
        return response()->json([
            'status' => false,
            'message' => __('Data is invalid')
        ]);
    }

    public function deleteThemeAction(Request $request)
    {
        $params = $request['params'];
        if(!empty($params)){
            $params = json_decode(base64_decode($params), true);
            if(isset($params['theme'])){
                $theme = $params['theme'];
                $folder = $params['folder'];
                $file_path = app_path('Themes/' . trim($folder));
                $public_file_path = public_path('themes/' . trim($theme));
                rmdir_recursive($public_file_path);
                rmdir_recursive($file_path);

                $active = get_opt('current_theme', '', false);
                if($active == $theme){
                    update_opt('current_theme', '');
                }

                return response()->json([
                    'status' => true,
                    'title' => __('System Alert'),
                    'message' => __('The Theme has been removed successfully'),
                    'reload' => true
                ]);
            }
        }
        return response()->json([
            'status' => false,
            'message' => __('Data is invalid')
        ]);
    }

    public function activeThemeAction(Request $request)
    {
        $params = $request['params'];
        if(!empty($params)){
            $params = json_decode(base64_decode($params), true);
            if(isset($params['theme'])){
                $currentRoute = $params['currentRoute'];
                update_opt('current_theme', $params['theme']);
                $responData = [
                    'status' => true,
                    'message' => __('Active theme successfully!'),
                ];

                if($currentRoute == 'themes'){
                    $responData['reload'] = true;
                }elseif($currentRoute == 'theme.new'){
                    $responData['redirect'] = route('themes');
                }
                return response()->json($responData);
            }
        }
        return response()->json([
            'status' => false,
            'message' => __('Data is invalid')
        ]);
    }

    public function themeView(Request $request)
    {
        $folders = glob(app_path('Themes/*'), GLOB_ONLYDIR);
        $themes = [];
        if (!empty($folders)) {
            foreach ($folders as $k => $folder) {
                $themeSlug = strtolower(basename($folder));
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
                        $theme = [];
                        if (!empty($txt)) {
                            foreach ($txt as $v) {
                                if (strpos($v, ': ') !== false) {
                                    $arr = explode(': ', $v);
                                    if (count($arr) == 2) {
                                        $theme[trim($arr[0])] = trim($arr[1]);
                                    }
                                }
                            }
                        }
                        $themes[$themeSlug] = $theme;
                    }
                }
                $themes[$themeSlug]['slug'] = $themeSlug;
                $themes[$themeSlug]['folderName'] = basename($folder);
                if (file_exists($folder . '/Assets/screenshot.png')) {
                    $themes[$themeSlug]['screenshot'] = asset('themes/' . $themeSlug . '/screenshot.png');
                }
            }
        }
        $active = get_opt('current_theme', '', false);
        if (isset($themes[$active])) {
            $currentTheme = $themes[$active];
            unset($themes[$active]);
            $themes = [$active => $currentTheme] + $themes;
        }

        //Get server version
        $resp = send_curl($this->url, [
            'action' => 'version',
            'exts' => json_encode(array_keys($themes))
        ]);
        $serverVersion = json_decode($resp, true);
        return $this->getView($this->getFolderView('themes.index'), ['themes' => $themes, 'active' => $active, 'version' => $serverVersion]);
    }
}