<?php
    /**
     * Created by PhpStorm.
     * User: Ironman
     * Date: 8/16/2015
     * Time: 5:54 PM
     */

    class Sp4kModulesVenueModelsItems extends Sp4kBaseModel
    {
        /** @var  Sp4kAppsVenueItems[] */
        public $items;

        protected function execute()
        {
            //json_decode filters
            //$this->state->set('filters',
            //    (($filters = $this->state->get('filters',false)) ? json_decode($filters):false));
            $this->items = Sp4kAppsVenueApp::getInstance(
                $this->state
            )->getItems();
        }

        public function batch()
        {

        }

        public function save(){
            $this->items->save();
        }

        public function delete(){
            $this->items->delete();
        }
    }