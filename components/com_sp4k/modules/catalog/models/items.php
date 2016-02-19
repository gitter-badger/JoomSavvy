<?php
    /**
     * Created by PhpStorm.
     * User: Ironman
     * Date: 2/16/2016
     * Time: 2:28 PM
     */

    class Sp4kModulesCatalogModelsItems extends JModelBase
    {

        /** @var  Sp4kAppsProductsItems */
        public $items;

        public function execute()
        {

            if( $product_id = $this->state->get('product_id',false) ){

                $this->setProduct();


                //todo clean this up. we are moving to show venues for showing venues, and showChildren for showing children but for now show children means show venues.
                if
                (
                    ( $this->product->config->display == 'children' )
                        ||
                    ( $this->product->config->display == 'venues' )
                ){
                    //if venue_id is set, we are showing child products for that venue
                    if($this->state->get('venue_id',false)){
                        $this->setVenueItems();
                    }else{
                        $this->setVenues();
                    }
                }
            }else{
                //build data for configurator.
            }


        }

        private function setProduct(){
            $this->product = Sp4kAppsProductApp::getInstance(
                new Registry([
                    'id'=>$this->state->get('id')
                ])
            )->getItem();

            return $this;
        }

        /**
         * Show venues for the selected parent product.
         */
        private function setVenues(){

            $products = Sp4kAppsProductApp::getInstance(
                new Registry(['filters'=>['parent_id'=>$this->state->get('id')]])
            )->getItems();

            foreach($products as $product){
                //build array of unique venue ids
                $venues[$product->venue_id] = $product->venue_id;
            }

            $this->venues = Sp4kAppsVenueApp::getInstance(
                new Registry(
                    ['keys'=>$venues]
                )
            )->getItems();
        }

        /**
         * Show the child products for the selected venue
         */
        private function setVenueItems(){
            $this->products = Sp4kAppsProductApp::getInstance(
                new Registry(
                    [
                        'filters'=>[
                            'parent_id'=>(int)$this->state->get('id'),
                            'venue_id'=>(int)$this->state->get('venue_id')
                        ]
                    ]
                )
            )->getItems();
        }
    }