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
use Illuminate\Support\Str;
use Madnest\Madzipper\Madzipper;
use Illuminate\Support\Facades\DB;

class ImportController extends Controller
{
    private $old_url = [
        'https://data.booteam.co',
        'http://data.booteam.co',
        'https://ibooking.booteam.co',
        'http://ibooking.booteam.co'
    ];

    private function resetData()
    {
        $tables = [
            'gmz_space', 'gmz_space_availability', 'gmz_tour', 'gmz_tour_availability', 'gmz_apartment', 'gmz_hotel', 'gmz_room', 'gmz_room_availability', 'gmz_apartment_availability', 'gmz_car', 'gmz_car_availability', '	gmz_comment', 'gmz_coupon', 'gmz_earnings', 'gmz_language', 'gmz_media', 'gmz_menu', 'gmz_menu_structure', 'gmz_notification', 'gmz_options', 'gmz_order', 'gmz_page', 'gmz_post', 'gmz_term', 'gmz_term_relation', 'gmz_withdrawal', 'gmz_agent', 'gmz_agent_availability', 'gmz_agent_relation', 'gmz_beauty', 'gmz_beauty_availability'
        ];
        foreach ($tables as $table) {
            DB::statement("DELETE FROM {$table}");
        }
    }

    private function insertSQL($filenames)
    {
        if (!empty($filenames)) {
            foreach ($filenames as $file) {
                if (file_exists(public_path("sql/gmz_" . $file . ".sql"))) {
                    $sql = file_get_contents(public_path("sql/gmz_" . $file . ".sql"));
                    if ($sql) {
                        if ($file == 'menu_structure') {
                            foreach ($this->old_url as $url) {
                                $sql = str_replace($url, url('/'), $sql);
                            }
                        }
                        $statements = array_filter(array_map('trim', explode('INSERT INTO', $sql)));
                        foreach ($statements as $stmt) {
                            if (!empty($stmt)) {
                                DB::insert("INSERT INTO " . $stmt);
                            }
                        }
                    }
                }
            }
        }
    }

    public function importDataAction(Request $request)
    {
        $check_text = $request->post('check_text', '');
        if (!empty($check_text) && !is_null($check_text)) {
            if ($check_text == 'Import') {
                $this->resetData();
                $this->resetData();
                $file_names = ['post', 'page', 'hotel', 'room', 'room_availability', 'apartment', 'apartment_availability', 'space', 'space_availability', 'tour', 'tour_availability', 'car', 'car_availability', 'menu', 'menu_structure', 'comment', 'coupon', 'earnings', 'language', 'media', 'notification', 'options', 'order', 'term', 'term_relation', 'withdrawal', 'agent', 'agent_availability', 'agent_relation', 'beauty', 'beauty_availability'];
                $this->insertSQL($file_names);
                $installedFile = storage_path('gmz_imported');
                $date = date("Y-m-d h:i:sa");
                if (!file_exists($installedFile)) {
                    $message = sprintf(__('Your site has been imported at %s'), $date) . "\n";
                    file_put_contents($installedFile, $message);
                } else {
                    $message = sprintf(__('Your site has been imported at %s'), $date) . "\n";
                    file_put_contents($installedFile, $message . PHP_EOL, FILE_APPEND | LOCK_EX);
                }
                return redirect()->back()->with('success', __('Importing demo data successfully'));
            }
        }
        return redirect()->back()->with('error', __('Checking text is invalid'));
    }

    public function importDataView()
    {
        return $this->getView($this->getFolderView('import.data'));
    }

    public function deleteFontIconAction(Request $request)
    {
        $icon_key = $request->post('iconKey');
        if (!empty($icon_key)) {
            $fontFile = public_path('fonts/fonts.php');
            @include $fontFile;

            if (isset($fonts) && !empty($fonts)) {
                if (isset($fonts[$icon_key])) {
                    unset($fonts[$icon_key]);
                    $myfile = fopen($fontFile, "w");
                    @ob_start();
                    var_export($fonts);
                    $content = @ob_get_clean();
                    fwrite($myfile, '<?php $fonts = ' . $content . '; ?>');
                    fclose($myfile);

                    return response()->json([
                        'status' => 1,
                        'message' => __('Delete icon successfully')
                    ]);
                }
            }
        }

        return response()->json([
            'status' => 0,
            'message' => __('Delete icon failed')
        ]);
    }

    public function importFontAction(Request $request)
    {
        if ($request->hasFile('fonts')) {
            $fontFile = public_path('fonts/fonts.php');
            @include $fontFile;

            if (!isset($fonts)) {
                $fonts = [];
            }

            $start_count = count($fonts);

            $fontUploads = $request->file('fonts');

            $count_ext = 0;

            $new_icons = [];

            if (!empty($fontUploads)) {
                foreach ($fontUploads as $uploadRequest) {
                    $filenameWithExt = $uploadRequest->getClientOriginalName();
                    $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                    $extension = $uploadRequest->getClientOriginalExtension();

                    $folderTime = 'zipfonts' . time();
                    $publicPath = storage_path('app/' . $folderTime);
                    $fileNameToStore = $filename . '.' . $extension;
                    $uploadRequest->storeAs($folderTime, $fileNameToStore);

                    if (!in_array($extension, ['zip', 'svg'])) {
                        $count_ext++;
                    }

                    if ($extension == 'zip') {
                        $zipper = new Madzipper();
                        $zipper->make($publicPath . '/' . $fileNameToStore)->extractTo($publicPath);
                        $zipper->close();
                    }

                    $files = glob_recursive($publicPath . '/*');
                    foreach ($files as $file) {
                        if (is_file($file)) {
                            $tmp_name = basename($file);
                            $tmp = explode('.', $tmp_name);
                            $file_extension = end($tmp);
                            $fontFilePath = $file;
                            if ($file_extension == 'svg') {
                                if ($extension == 'zip') {
                                    $fontFileName = Str::slug($filename . '_' . $tmp[0]);
                                } else {
                                    $fontFileName = Str::slug($tmp[0]);
                                }
                                $fontFileName = str_replace('-', '_', $fontFileName);
                                if (!isset($fonts[$fontFileName])) {
                                    $content = file_get_contents($fontFilePath);
                                    if (!empty($content)) {
                                        preg_match_all('/<svg(.*)<\/svg>/s', $content, $icon);

                                        $icon = $icon[0][0];
                                        $test_icon = substr($icon, 0, strpos($icon, '>'));
                                        if (strpos($test_icon, 'width') === false) {
                                            $icon = str_replace('<svg', '<svg width="24px"', $icon);
                                        }
                                        if (strpos($test_icon, 'height') === false) {
                                            $icon = str_replace('<svg', '<svg height="24px"', $icon);
                                        }

                                        if (strpos($icon, 'fill') === false) {
                                            $icon = str_replace('<g', '<g fill="#000000"', $icon);
                                        }

                                        $test_icon = substr($icon, 0, strpos($icon, '>'));

                                        $test_icon = preg_replace('/width="[0-9.a-z]*"/', 'width="24px"', $test_icon);
                                        $test_icon = preg_replace('/width=""/', 'width="24px"', $test_icon);
                                        $test_icon = preg_replace('/height="[0-9.a-z]*"/', 'height="24px"', $test_icon);
                                        $test_icon = preg_replace('/height=""/', 'height="24px"', $test_icon);
                                        $icon = $test_icon . substr($icon, strpos($icon, '>'));

                                        $icon = preg_replace('/<title>.*<\/title>/', '', $icon);

                                        $icon = preg_replace('/(id="[a-zA-Z0-9-_]*")/', '', $icon);
                                        $icon = str_replace('xmlns="http://www.w3.org/2000/svg"', '', $icon);
                                        $icon = str_replace('xmlns:xlink="http://www.w3.org/1999/xlink"', '', $icon);

                                        if (!isset($fonts[$fontFileName])) {
                                            $fonts[$fontFileName] = $icon;
                                            $new_icons[$fontFileName] = $icon;
                                        }
                                    }
                                }
                            }
                        }
                    }
                    rmdir_recursive($publicPath);
                }
            }

            if ($count_ext == count($fontUploads)) {
                return response()->json([
                    'status' => 0,
                    'message' => view('Backend::components.alert', [
                        'type' => 'danger',
                        'message' => __('Please select .ZIP or .SVG file format')
                    ])->render()
                ]);
            }

            $end_count = count($fonts);

            if ($end_count > $start_count) {
                $myfile = fopen($fontFile, "w");
                @ob_start();
                var_export($fonts);
                $content = @ob_get_clean();
                fwrite($myfile, '<?php $fonts = ' . $content . '; ?>');
                fclose($myfile);

                $number_uploaded = $end_count - $start_count;

                $message = _n(__('%s icon has been upload successfully'), __('%s icons has been upload successfully'), $number_uploaded);
                return response()->json([
                    'status' => 1,
                    'message' => view('Backend::components.alert', ['type' => 'success', 'message' => $message])->render(),
                    'icons' => $new_icons,
                    'delete_action' => dashboard_url('delete-font-icon')
                ]);
            } else {
                return response()->json([
                    'status' => 0,
                    'message' => view('Backend::components.alert', ['type' => 'danger', 'message' => __('No icons imported')])->render()
                ]);
            }
        }

        return response()->json([
            'status' => 0,
            'message' => view('Backend::components.alert', ['type' => 'danger', 'message' => __('You need choose font file before importing')])->render()
        ]);
    }

    public function importFontView(Request $request)
    {
        return $this->getView($this->getFolderView('import.font'));
    }
}