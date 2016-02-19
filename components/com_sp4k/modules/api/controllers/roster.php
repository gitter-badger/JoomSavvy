<?php
    
    /**
     * Created by Joseph Cardwell.
     * User: Ironman
     * Date: 12/15/2015
     * Time: 12:26 PM
     */

    class Sp4kModulesApiControllersRoster extends JControllerBase
    {
        public function execute(){


            //push an update on product attendance.
            if($this->input->getMethod() == 'POST'){

                $jsonInput = new JInputJSON();
                $model = new Sp4kModulesRosterModelsItem(
                    new Joomla\Registry\Registry($jsonInput->getArray())
                );

                $model->execute();//initialize the model with the data we've injected
                $model->update();
                $response = $model->item;


            //get the list of registrations for a product.
            }elseif($this->input->getMethod() == 'GET'){

                if($product_id = $this->input->get('product',false)) {//a class

                    $date = $this->input->get('date',strtotime('today')*1000) / 1000;

                    $model = new Sp4kModulesRosterModelsItems(
                        new Joomla\Registry\Registry(['id'=>$product_id,'date'=>$date])
                    );

                    $model->execute();
                    $response = $model->items;

                }else{   //if there is no idea this should be an error.

                }
            }

            echo json_encode($response);
        }
    }