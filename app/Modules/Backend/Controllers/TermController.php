<?php
/**
 * Created by PhpStorm.
 * User: Jream
 * Date: 5/12/2020
 * Time: 11:33 PM
 */

namespace App\Modules\Backend\Controllers;

use App\Services\TermService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\Paginator;
use TorMorten\Eventy\Facades\Eventy;

class TermController extends Controller
{
    private $service;
    private $partner_terms = [];

    public function __construct()
    {
        $this->service = TermService::inst();
        $this->partner_terms = Eventy::filter('gmz_partner_edit_terms', ['beauty-branch']);
    }

    public function editTermView($id, $taxonomy)
    {
        if (is_partner() && !in_array($taxonomy, $this->partner_terms)) {
            return redirect(dashboard_url('/'));
        }

        $taxonomy = $this->service->getTaxonomyByName($taxonomy);
        if (!is_null($taxonomy)) {
            $term_object = $this->service->getTermByID($id);

            if (is_partner()) {
                $author = $term_object->author;
                if ($author != get_current_user_id()) {
                    return redirect(dashboard_url('/'));
                }
            }


            if (!empty($term_object) && $term_object->taxonomy_id == $taxonomy->id) {
                $data = [
                    'action' => dashboard_url('edit-term'),
                    'tax_id' => $taxonomy->id,
                    'tax_name' => $taxonomy->taxonomy_name,
                    'term_id' => $id,
                    'term_object' => $term_object
                ];
                return $this->getView($this->getFolderView('term.edit'), ['taxonomy' => $taxonomy, 'data' => $data]);
            }
        }
        return redirect(dashboard_url('/'));
    }

    public function addTermView($taxonomy)
    {
        if (is_partner() && !in_array($taxonomy, $this->partner_terms)) {
            return redirect(dashboard_url('/'));
        }

        $taxonomy = $this->service->getTaxonomyByName($taxonomy);
        if (!is_null($taxonomy)) {
            $data = [
                'action' => dashboard_url('new-term'),
                'tax_id' => $taxonomy->id,
                'tax_name' => $taxonomy->taxonomy_name,
                'term_id' => '',
                'term_object' => []
            ];
            return $this->getView($this->getFolderView('term.new'), ['taxonomy' => $taxonomy, 'data' => $data]);
        }
        return redirect(dashboard_url('/'));
    }

    public function allTermView($taxonomy)
    {
        if (is_partner() && !in_array($taxonomy, $this->partner_terms)) {
            return redirect(dashboard_url('/'));
        }

        $taxonomy = $this->service->getTaxonomyByName($taxonomy);
        if (!is_null($taxonomy)) {
            $args = [
                'taxonomy_id' => $taxonomy->id,
                'parent' => 0
            ];
            if (is_partner()) {
                $args['author'] = get_current_user_id();
            }
            $termLimit = Eventy::filter('gmz_term_limit', 10);
            $terms = $this->service->getPostsPagination($termLimit, $args);
            Paginator::useBootstrap();
            return $this->getView($this->getFolderView('term.all'), ['taxonomy' => $taxonomy, 'terms' => $terms]);
        }
        return redirect(dashboard_url('/'));
    }

    public function newTermAction(Request $request)
    {
        if (is_partner()) {
            $taxonomy = $request->post('taxonomy_name');
            if (!in_array($taxonomy, $this->partner_terms)) {
                return response()->json([
                    'status' => false,
                    'message' => __('Can not add this term')
                ]);
            }
        }
        $reponse = $this->service->newTerm($request);
        return response()->json($reponse);
    }

    public function editTermAction(Request $request)
    {
        if (is_partner()) {
            $taxonomy_name = $request->post('taxonomy_name');
            if (!in_array($taxonomy_name, $this->partner_terms)) {
                return response()->json([
                    'status' => false,
                    'message' => __('Can not edit this term')
                ]);
            } else {
                $term_object = $this->service->getTermByID($request->post('term_id'));
                $author = $term_object->author;
                if ($author != get_current_user_id()) {
                    return response()->json([
                        'status' => false,
                        'message' => __('Can not edit this term')
                    ]);
                }
            }
        }
        $response = $this->service->editTerm($request);
        return response()->json($response);
    }

    public function deleteTermAction(Request $request)
    {
        $params = $request->post('params', '');
        if (!empty($params)) {
            $params = json_decode(base64_decode($params), true);
            if (is_partner()) {
                if (!in_array($params['taxName'], $this->partner_terms)) {
                    return response()->json([
                        'status' => false,
                        'message' => __('Can not delete this term')
                    ]);
                } else {
                    $term_object = $this->service->getTermByID($params['termID']);
                    $author = $term_object->author;
                    if ($author != get_current_user_id()) {
                        return response()->json([
                            'status' => false,
                            'message' => __('Can not delete this term')
                        ]);
                    }
                }
            }
        }

        $response = $this->service->deleteTerm($request);
        return response()->json($response);
    }
}