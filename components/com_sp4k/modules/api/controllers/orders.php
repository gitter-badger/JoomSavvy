<?php
    /**
     * Created by PhpStorm.
     * User: Ironman
     * Date: 9/4/2015
     * Time: 7:33 AM
     */


    class Sp4kModulesApiControllersOrders extends JControllerBase
    {
        public function execute(){
            if($this->input->getMethod() == 'POST'){

                $jsonInput = new JInputJSON();
                $model = new Sp4kModulesOrderModelsItem(
                    new Joomla\Registry\Registry($jsonInput->getArray())
                );

                $model->execute();//initialize the model with the data we've injected
                $model->update();
                $response = $model->item;

            }elseif($this->input->getMethod() == 'GET'){

                if($this->input->get('id',false) !==false) {

                    $model = new Sp4kModulesOrderModelsItem(
                        new Joomla\Registry\Registry($this->input->getArray())
                    );

                    $model->execute();
                    $response = $model->item;

                }else{
                    $limit      =
                        ($limit = $this->input->get('limit',false,'RAW'))
                            ?
                            json_decode($limit)
                            :
                            null;

                    $filters    =
                        ($filters = $this->input->get('filters',false,'RAW'))
                            ?
                            json_decode($filters)
                            :
                            null;

                    $paging = $this->input->getBool('paging',false);
                    $count = $this->input->getBool('count',false);
                    $joins = false;

                    if(isset($filters->orderTotal)){
                        if(!isset($filters->orderTotal->min) && isset($filters->orderTotal->max)){

                            $filters->total = [
                                'operator'=>' <= ',
                                'value'=>$filters->orderTotal->max,
                                'skipquote'=>true
                            ];

                        }elseif(isset($filters->orderTotal->min) && !isset($filters->orderTotal->max)){

                            $filters->total = [
                                'operator'=>' >= ',
                                'value'=>$filters->orderTotal->min,
                                'skipquote'=>true
                            ];

                        }elseif( isset($filters->orderTotal->min) && isset($filters->orderTotal->max) ) {

                            $filters->total = [
                                'operator'=>' BETWEEN ',
                                'value'=>(int)$filters->orderTotal->min.' AND '.(int)$filters->orderTotal->max,
                                'skipquote'=>true
                            ];

                        }

                        unset($filters->orderTotal);
                    }

                    if(isset($filters->orderDate)){
                        if( !isset($filters->orderDate->min) && isset($filters->orderDate->max) && $filters->orderDate->max >0){
                            $filters->created = [
                                'operator'=> ' <= ',
                                'value'=>$filters->orderDate->max,
                                'skipquote'=>true
                            ];
                        }elseif( isset($filters->orderDate->min) && !isset($filters->orderDate->max) ){
                            $filters->created = [
                                'operator'=> ' >= ',
                                'value'=>$filters->orderDate->max,
                                'skipquote'=>true
                            ];
                        }elseif(isset($filters->orderDate->min) && isset($filters->orderDate->max) && $filters->orderDate->max >0){
                            $filters->created = [
                                'operator'=>' BETWEEN ',
                                'value'=>(int)$filters->orderDate->min.' AND '.(int)$filters->orderDate->max,
                                'skipquote'=>true
                            ];
                        }

                        unset($filters->orderDate);
                    }

                    if(isset($filters->parent_id) || isset($filters->order->l_name)){
                        if(isset($filters->parent_id)){
                            $joins[] = ['type'=>'inner','condition'=>'#__sp4k_parent_items as p on p.account_id = order.account_id'];

                            $filters->id = ['value'=>$filters->parent_id,'alias'=>'p'];
                            unset($filters->parent_id);
                        }else{
                            $joins[] = ['type'=>'left','condition'=>'#__sp4k_parent_items as p on p.account_id = order.account_id'];
                        }

                        if(isset($filters->order->l_name)){
                            $l_name = new stdClass();
                            $l_name->direction = $filters->order->l_name;
                            $l_name->alias = 'p';
                            $filters->order->l_name = $l_name;
                        }
                    }



                    $state = new Joomla\Registry\Registry([
                        'limit'=>$limit,
                        'filters'=>$filters,
                        'paging'=>$paging,
                        'count'=>$count,
                        'joins'=>$joins
                    ]);

                    $model = new Sp4kModulesOrderModelsItems($state);

                    if($count){

                        $response = [];
                        $response['items'] = $model->items;
                        $response['count'] = $model->count;

                    }else{
                        $response = $model->items;
                    }
                }
            }

            echo json_encode($response);
        }
    }
