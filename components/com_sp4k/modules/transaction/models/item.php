<?php
    
    /**
     * Created by Joseph Cardwell.
     * User: Ironman
     * Date: 11/7/2015
     * Time: 7:36 AM
     */

    class Sp4kModulesTransactionModelsItem extends Sp4kBaseModel
    {
        /** @var  Sp4kAppsSubscriptionItem */
        public $item;

        public function execute()
        {
            $this->item = Sp4kAppsTransactionApp::getInsance(
                new Joomla\Registry\Registry($this->state->toObject())
            )->getItem();
        }

        public function update(){
            $this->item->update();
        }
    }