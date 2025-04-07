<?php
    if(empty($data)){
        return false;
    }
    $task = $data['task'];
    $link = $data['link'];

?>
<div class="widget widget-five">
    <div class="widget-content">

        <div class="header">
            <div class="header-body">
                <h6>{{__("Pending Tasks")}}</h6>
                <p class="meta-date"></p>
            </div>
            <div class="task-action">
                <div class="dropdown  custom-dropdown">
                    <a class="dropdown-toggle" href="#" role="button" id="pendingTask" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-horizontal"><circle cx="12" cy="12" r="1"></circle><circle cx="19" cy="12" r="1"></circle><circle cx="5" cy="12" r="1"></circle></svg>
                    </a>

                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="pendingTask">
                        <a class="dropdown-item" href="{{$link['refund']}}">{{__('View orders need refund')}}</a>
                        <a class="dropdown-item" href="{{$link['confirm']}}">{{__('view orders need confirmation')}}</a>
                        <a class="dropdown-item" href="{{$link['withdrawal']}}">{{__('view pending withdrawal')}}</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="w-content">
            <div class="">
                <p class="task-left">{{$task['total']}}</p>
                <p class="task-completed"><span>{{$task['order_refund']}} {{__('Orders need refund')}}</span></p>
                <p class="task-hight-priority mb-1"><span>{{$task['order_confirm']}} {{__('Orders need confirmation')}}</span></p>
                <p class="text-dark mb-0"><span>{{$task['withdrawal_pending']}} {{__('Pending withdrawal')}}</span></p>
            </div>
        </div>
    </div>
</div>