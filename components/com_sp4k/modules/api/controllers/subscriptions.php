<?php
    /**
     * Created by PhpStorm.
     * User: Ironman
     * Date: 9/4/2015
     * Time: 7:33 AM
     */


    class Sp4kModulesApiControllersSubscriptions extends JControllerBase
    {
        public function execute(){
            if($this->input->getMethod() == 'POST'){

                $jsonInput = new JInputJSON();
                $model = new Sp4kModulesSubscriptionModelsItem(
                    new Joomla\Registry\Registry($jsonInput->getArray())
                );

                $model->execute();//initialize the model with the data we've injected
                $model->update();
                $response = $model->item;

            }elseif($this->input->getMethod() == 'GET'){

                if($this->input->get('id',false) !==false) {

                    $model = new Sp4kModulesSubscriptionModelsItem(
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

                    if(isset($filters->order->product)){

                        $joins[] = ['type'=>'left','condition'=>'#__sp4k_product_items as p on p.id = subscription.product_id'];

                        $product_name = new stdClass();
                        $product_name->direction = $filters->order->product;
                        $product_name->alias = 'p';
                        $filters->order->title = $product_name;
                        unset($filters->order->product);
                    }


                    $state = new Joomla\Registry\Registry([
                        'limit'=>$limit,
                        'filters'=>$filters,
                        'paging'=>$paging,
                        'count'=>$count,
                        'joins'=>$joins
                    ]);


                    $model = new Sp4kModulesSubscriptionModelsItems($state);

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
