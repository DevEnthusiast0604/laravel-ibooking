<?php
/**
 * Created by PhpStorm.
 * User: Jream
 * Date: 5/12/2020
 * Time: 11:33 PM
 */
namespace App\Modules\Backend\Controllers;

use App\Services\AgentService;
use App\Services\CommentService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\Paginator;

class AgentController extends Controller{
   private $service;

   public function __construct() {
      $this->service = AgentService::inst();
   }

   public function newAgentView($service){
      if (!in_array($service, get_services_enabled(), true)){
         return redirect()->route('dashboard');
      }
      $this->service->deletePostTemp();
      $id = $this->service->storeNewPost($service);
      $postData = $this->service->getPostById($id)->getAttributes();
      $postData['post_type'] = GMZ_SERVICE_AGENT;

      return $this->getView( $this->getFolderView('services.agent.edit'), [
         'serviceData' => $postData,
         'title' => __('Add new agent'),
         'new' => true
      ]);
   }
   public function editAgentView($service, $id){
      $postData = $this->service->storeTermData($id);
      if($postData) {
         $postData = $postData->getAttributes();
         $postData['post_type'] = GMZ_SERVICE_AGENT;
         return $this->getView($this->getFolderView('services.agent.edit'), [
            'serviceData' => $postData,
            'title' => __('Edit agent'),
            'new' => false
         ]);
      }
      return response()->redirectTo(dashboard_url('all-agents'));
   }

   public function saveAgentAction(Request $request){
      $response = $this->service->savePost($request);
      return response()->json($response);
   }

   public function allAgentView(Request $request, $service){
      if (!in_array($service, get_services_enabled(), true)){
         return redirect()->route('dashboard');
      }

      $this->service->deletePostTemp();
      $status = $request->get('status', '');
      $where = [];
      $where['post_type'] = $service;
      $post_status = admin_config( 'agent_status');

      if(!empty($status) && in_array($status, array_keys($post_status))){
         $where['status'] = $status;
      }


      $allPosts = $this->service->getPostsPagination(10, $where);
      Paginator::useBootstrap();
      return $this->getView($this->getFolderView('services.agent.all'), ['allPosts' => $allPosts]);
   }

   public function deleteAgentAction(Request $request){
      $response = $this->service->deletePost($request);
      return response()->json($response);
   }
}