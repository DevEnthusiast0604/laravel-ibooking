<?php
/**
 * Created by PhpStorm.
 * User: JreamOQ ( jreamoq@gmail.com )
 * Date: 12/10/20
 * Time: 14:23
 */
namespace App\Http\Middleware;

use Closure;

class Locale
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(is_multi_language()){
            $lang = $request->get('lang', '');
            if(!session_id()){
                session_start();
            }

            if(!empty($lang)){
                $langs = get_languages();
                if(!empty($langs)){
                    if(in_array($lang, $langs)){
                        $_SESSION['gmz_language'] = $lang;
                    }
                }
            }else{
                $currentSectionLang = '';
                if(isset($_SESSION['gmz_language'])){
                    $currentSectionLang = $_SESSION['gmz_language'];
                }else{
                    if(isset($_COOKIE['gmz_language'])){
                        $currentSectionLang = $_COOKIE['gmz_language'];
                    }
                }
                $langs = get_languages();
                if(empty($currentSectionLang)){
                    if(!empty($langs)){
                        $_SESSION['gmz_language'] = $langs[0];
                    }
                }else{
                    if(!empty($langs)){
                        if(!in_array($currentSectionLang, $langs)){
                            if(isset($_SESSION['gmz_language'])){
                                unset($_SESSION['gmz_language']);
                            }
                        }else {
                            $_SESSION['gmz_language'] = $currentSectionLang;
                        }
                    }
                }
            }

            if(isset($_SESSION['gmz_language'])){
                $language = $_SESSION['gmz_language'];
                setcookie("gmz_language", $language);
            }else{
                $lang_option = get_option('site_language', '');
                if(empty($lang_option)){
                    $lang_option = config('app.locale');
                }
                $language = $lang_option;
            }
        }else{
            $lang_option = get_option('site_language', '');
            if(empty($lang_option)){
                $lang_option = config('app.locale');
            }
            $language = $lang_option;
        }

        app()->setLocale($language);

        return $next($request);
    }
}