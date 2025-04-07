<?php

namespace App\Services;

use App\Repositories\LanguageRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;

class LanguageService extends AbstractService
{
    private static $_inst;
    protected $repository;

    public static function inst()
    {
        if (empty(self::$_inst)) {
            self::$_inst = new self();
        }
        return self::$_inst;
    }

    public function __construct()
    {
        $this->repository = LanguageRepository::inst();
    }

    public function updateTranslation(Request $request)
    {
        $fields = $request->post('fields', '');
        if (empty($fields)) {
            return [
                'status' => 0,
                'title' => __('System Alert'),
                'message' => __('Translate failed')
            ];
        }

        $fields = json_decode($fields, true);

        if (isset($fields[0])) {
            $lang = $fields[0]['value'];
            unset($fields[0]);
        }
        if (empty($lang))
            $lang = 'none';

        $langs = config('locales.languages');

        if ($lang == 'none' || !isset($langs[$lang])) {
            return [
                'status' => 0,
                'title' => __('System Alert'),
                'message' => __('Please choose language before translating')
            ];
        }

        $translate = [];
        if (!empty($fields)) {
            foreach ($fields as $item) {
                $key = $item['name'];
                $spos = strpos($key, '_');
                if ($spos) {
                    $key = substr($key, 0, $spos);
                }
                $key = base64_decode($key);
                $translate[$key] = $item['value'];
            }
        }

        if (empty($translate) || !is_array($translate)) {
            return [
                'status' => 0,
                'title' => __('System Alert'),
                'message' => __('Translate failed')
            ];
        }

        $json_content = json_encode($translate, JSON_PRETTY_PRINT);
        $lang_files = resource_path("lang/" . $lang . ".json");
        $inserted = file_put_contents($lang_files, $json_content);
        if ($inserted) {
            return [
                'status' => 1,
                'title' => __('System Alert'),
                'message' => __('Translated successfully')
            ];
        } else {
            return [
                'status' => 0,
                'title' => __('System Alert'),
                'message' => __('Translate failed')
            ];
        }
    }

    private function scanAllFiles($dir)
    {
        $root = scandir($dir);
        $result = [];
        foreach ($root as $value) {
            if ($value === '.' || $value === '..') {
                continue;
            }
            if (is_file("$dir/$value")) {
                $result[] = "$dir/$value";
                continue;
            }
            foreach ($this->scanAllFiles("$dir/$value") as $value1) {
                $result[] = $value1;
            }
        }
        return $result;
    }

    private function scanAllTranslateText($strings)
    {
        $folders = [
            app_path(),
        ];
        foreach ($folders as $folder) {
            $view_path = $folder;
            $files = $this->scanAllFiles($view_path);
            if (!empty($files)) {
                foreach ($files as $file) {
                    if (file_exists($file)) {
                        $content = file_get_contents($file);
                        preg_match_all("/__\('(.*)'\)|__\( '(.*)' \)|__\('(.*)',|__\(\"(.*)\",|__\(\"(.*)\"\)|trans\('(.*)'\)|trans\(\"(.*)\"\)|ilangs\('(.*)'\)|ilangs\(\"(.*)\"\)|trans_choice\('(.*)'\)|trans_choice\(\"(.*)\"\)/U",
                            $content,
                            $output, PREG_PATTERN_ORDER);
                        for ($i = 1; $i <= 11; $i++) {
                            if (isset($output[$i])) {
                                foreach ($output[$i] as $k => $v) {
                                    if (!empty(trim($v))) {
                                        if (!in_array(trim($v), $strings)) {
                                            array_push($strings, trim($v));
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        return $strings;
    }

    public function scanTranslation(Request $request)
    {
        $strings = [];
        $strings = $this->scanAllTranslateText($strings);

        if (!empty($strings)) {
            $strings = implode("\r\n", $strings);
            $lang_files = resource_path("lang/boo.lang");
            $inserted = file_put_contents($lang_files, $strings);

            $request_lang = $request->post('lang');
            $url = dashboard_url('translation');
            if (!empty($request_lang) && $request_lang != 'none') {
                $url = add_query_arg('lang', $request_lang, $url);
            }

            if ($inserted) {
                return [
                    'status' => 1,
                    'title' => __('System Alert'),
                    'message' => 'Scan successfully',
                    'redirect' => $url
                ];
            } else {
                return [
                    'status' => 0,
                    'title' => __('System Alert'),
                    'message' => __('Scan failed')
                ];
            }
        }
        return [
            'status' => 0,
            'title' => __('System Alert'),
            'message' => __('No text translation')
        ];
    }

    private function getContentTranslateFile($file_path, $json = false)
    {
        $gmz_files = resource_path($file_path);
        $strings = [];
        if (file_exists($gmz_files)) {
            $file_content = trim(file_get_contents($gmz_files));
            if (!empty($file_content)) {
                if ($json) {
                    $strings = json_decode($file_content, true);
                } else {
                    $hasRN = strpos($file_content, "\r\n");
                    if ($hasRN) {
                        $strings = explode("\r\n", $file_content);
                    } else {
                        $strings = explode("\n", $file_content);
                    }
                }
            }
        }
        return $strings;
    }

    public function getDataTranslation(Request $request)
    {
        $langs = config('locales.languages');
        $lang = $request->get('lang', 'none');
        $site_language = get_option('site_language', 'none');

        if ($site_language != 'none' && $lang == 'none') {
            $lang = $site_language;
        }

        $strings = $this->getContentTranslateFile("lang/boo.lang");
        if ($lang != 'none' && isset($langs[$lang])) {
            $trans = $this->getContentTranslateFile("lang/" . $lang . ".json", true);
        } else {
            $trans = [];
        }

        return [
            'strings' => $strings,
            'translation' => $trans,
            'langs' => $langs,
            'lang' => $lang
        ];
    }

    public function sortLanguage(Request $request)
    {
        $data = $request->post('data');
        if (!empty($data)) {
            foreach ($data as $key => $val) {
                $this->repository->updateByWhere(['code' => $key], ['priority' => $val]);
            }

            $this->clearLangsCache();
            return [
                'status' => 1,
                'title' => __('System Alert'),
                'message' => __('Update successfully')
            ];
        }
        return [
            'status' => 0,
            'title' => __('System Alert'),
            'message' => __('Update failed')
        ];
    }

    public function deleteLanguage(Request $request)
    {
        $params = json_decode(base64_decode($request->post('params', [])), true);
        $language_id = isset($params['languageID']) ? $params['languageID'] : '';
        $language_hashing = isset($params['languageHashing']) ? $params['languageHashing'] : '';

        if (!gmz_compare_hashing($language_id, $language_hashing)) {
            return [
                'status' => 0,
                'title' => __(__('System Alert')),
                'message' => __('This Language is invalid')
            ];
        }

        $languageObject = $this->repository->find($language_id);

        if (!empty($languageObject) && is_object($languageObject)) {
            $deleted = $this->repository->delete($language_id);
            if ($deleted) {
                $this->clearLangsCache();
                return [
                    'status' => 1,
                    'title' => __(__('System Alert')),
                    'message' => __('This language is deleted'),
                    'reload' => true
                ];
            } else {
                return [
                    'status' => 0,
                    'title' => __(__('System Alert')),
                    'message' => __('Can not delete this language')
                ];
            }
        } else {
            return [
                'status' => 0,
                'title' => __(__('System Alert')),
                'message' => __('This language is invalid')
            ];
        }
    }

    public function changeStatus(Request $request)
    {
        $params = json_decode(base64_decode($request->post('params', [])), true);
        $status = $request->post('approve', 'on');

        $language_id = isset($params['languageID']) ? $params['languageID'] : '';
        $language_hashing = isset($params['languageHashing']) ? $params['languageHashing'] : '';

        if (!gmz_compare_hashing($language_id, $language_hashing)) {
            return [
                'status' => 0,
                'title' => __(__('System Alert')),
                'message' => __('This Language is invalid')
            ];
        }

        $data = [
            'status' => $status == 'yes' ? 'on' : 'off'
        ];

        $updated = $this->repository->update($language_id, $data);

        if ($updated) {
            $this->clearLangsCache();
            return [
                'status' => 1,
                'title' => __(__('System Alert')),
                'message' => __('Updated Successfully'),
            ];
        } else {
            return [
                'status' => 0,
                'title' => __(__('System Alert')),
                'message' => __('Can not update this language')
            ];
        }
    }

    private function clearLangsCache()
    {
        Cache::pull('gmz_langs_full');
        Cache::pull('gmz_langs_code');
    }

    public function updateLanguage(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'language' => 'required',
                'flag_name' => 'required',
                'name' => 'required'
            ],
            [
                'language.required' => __('Language is required'),
                'name.required' => __('Name is required'),
                'flag_name.required' => __('Flag is required')
            ]
        );
        if ($validator->fails()) {
            return [
                'status' => 0,
                'title' => __('System Alert'),
                'message' => $validator->errors()->first()
            ];
        }

        $lang_code = $request->get('language');
        $flag_name = $request->get('flag_name');
        $flag_code = $request->get('flag_code');
        $name = $request->get('name');
        $status = $request->get('status', 'off');
        $rtl = $request->get('rtl', 'no');

        $langs = config('locales.languages');

        $isEdit = false;

        if (!empty($langs) && isset($langs[$lang_code])) {
            $action = $request->get('action', '');
            if ($action == 'edit') {
                $id = $request->post('id', '');
                $check_exists = $this->repository->find($id);
                if (!is_null($check_exists)) {
                    $isEdit = true;
                    $check_exists_update = $this->repository->whereRaw("code = '{$lang_code}' AND id <> {$id}", true);
                    if ($check_exists_update) {
                        return [
                            'status' => 0,
                            'title' => __('System Alert'),
                            'message' => __('This language already exists')
                        ];
                    }

                    $res = $this->repository->update($id, [
                        'code' => $lang_code,
                        'name' => $name,
                        'flag_name' => $flag_name,
                        'flag_code' => $flag_code,
                        'status' => $status,
                        'rtl' => $rtl
                    ]);

                    if ($res) {
                        $this->clearLangsCache();
                        return [
                            'status' => 1,
                            'redirect' => dashboard_url('language'),
                            'title' => __('System Alert'),
                            'message' => __('Update language successfully')
                        ];
                    } else {
                        return [
                            'status' => 0,
                            'title' => __('System Alert'),
                            'message' => __('Can not edit this language')
                        ];
                    }
                } else {
                    return [
                        'status' => 0,
                        'title' => __('System Alert'),
                        'message' => __('Can not edit this language')
                    ];
                }
            }

            $check_exists = $this->repository->where([
                'code' => $lang_code
            ], true);
            if ($check_exists) {
                return [
                    'status' => 0,
                    'title' => __('System Alert'),
                    'message' => __('This language already exists')
                ];
            }

            $res = $this->repository->create([
                'code' => $lang_code,
                'name' => $name,
                'flag_name' => $flag_name,
                'flag_code' => $flag_code,
                'status' => $status,
                'rtl' => $rtl
            ]);

            if ($res) {
                $this->clearLangsCache();
                return [
                    'status' => 1,
                    'reload' => true,
                    'title' => __('System Alert'),
                    'message' => __('Create new language successfully')
                ];
            }
        }

        return [
            'status' => 0,
            'title' => __('System Alert'),
            'message' => !$isEdit ? __('Can not create this language') : __('Can not update this language')
        ];
    }

    public function getDataLanguage(Request $request)
    {
        $isEdit = false;
        $currentLang = [];

        $action = $request->get('action', '');

        if ($action == 'edit') {
            $id = $request->get('id', '');
            $check_exists = $this->repository->find($id);
            if ($check_exists) {
                $isEdit = true;
                $currentLang = $check_exists;
            } else {
                return [
                    'redirect' => true,
                    'routeName' => 'language'
                ];
            }
        }

        $allLanguages = $this->repository->all('priority', 'ASC');

        $countries_data = file_get_contents(public_path('vendors/countries/countries.json'));

        $countries_data = json_decode($countries_data, true);

        return [
            'allLanguages' => $allLanguages,
            'countryData' => $countries_data,
            'isEdit' => $isEdit,
            'currentLang' => $currentLang
        ];
    }
}