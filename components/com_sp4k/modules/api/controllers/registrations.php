        <?php
    /**
     * Created by PhpStorm.
     * User: Ironman
     * Date: 9/4/2015
     * Time: 7:33 AM
     */


    class Sp4kModulesApiControllersRegistrations extends JControllerBase
    {
        public function execute(){
            if($this->input->getMethod() == 'POST'){

                $jsonInput = new JInputJSON();
                $model = new Sp4kModulesRegistrationModelsItem(
                    new Joomla\Registry\Registry($jsonInput->getArray())
                );

                $model->execute();//initialize the model with the data we've injected
                $model->update();
                $response = $model->item;

            }elseif($this->input->getMethod() == 'GET'){

                if($this->input->get('id',false) !==false) {

                    $model = new Sp4kModulesRegistrationModelsItem(
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


                    $model = new Sp4kModulesRegistrationModelsItems($state);

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
