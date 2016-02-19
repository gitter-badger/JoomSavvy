<?php

    ini_set('DISPLAY_ERRORS',FALSE);
    use Joomla\Registry\Registry;

    /**
     * Created by Joseph Cardwell.
     * User: Ironman
     * Date: 9/29/2015
     * Time: 6:46 AM
     */

    class Sp4kModulesApiControllersPayments extends JControllerBase
    {

        public function execute(){
            if($this->input->getMethod() == 'POST'){

                $jsonInput = new JInputJSON();
                $postdata = $jsonInput->getArray();
                $model = new Sp4kModulesPaymentModelsApi(
                    new Registry($postdata)
                );

                //$model->execute();//initialize the model with the data we've injected
                $model->update();
                $response = $model->item;

            }elseif($this->input->getMethod() == 'GET'){

                if($this->input->get('id',false)) {

                    $model = new Sp4kModulesPaymentModelsApi(
                        new Registry($this->input->getArray())
                    );

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

                        $joins[] = ['type'=>'left','condition'=>'#__sp4k_product_items as p on p.id = registration.product_id'];

                        $product_name = new stdClass();
                        $product_name->direction = $filters->order->product;
                        $product_name->alias = 'p';
                        $filters->order->title = $product_name;
                        unset($filters->order->product);
                    }

                    if(isset($filters->order->child)){

                        $joins[] = ['type'=>'left','condition'=>'#__sp4k_child_items as child on child.id = registration.child_id'];

                        $child_name = new stdClass();
                        $child_name->direction = $filters->order->child;
                        $child_name->alias = 'child';
                        $filters->order->name = $child_name;
                        unset($filters->order->child);
                    }


                    $state = new Joomla\Registry\Registry([
                        'limit'=>$limit,
                        'filters'=>$filters,
                        'paging'=>$paging,
                        'count'=>$count,
                        'joins'=>$joins
                    ]);


                    $model = new Sp4kModulesPaymentModelsItems($state);

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
