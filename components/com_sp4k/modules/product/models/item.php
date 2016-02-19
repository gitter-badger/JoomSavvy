<?php
/**
 * Created by PhpStorm.
 * User: Ironman
 * Date: 9/5/2015
 * Time: 10:35 AM
 */


    class Sp4kModulesProductModelsItem extends JModelBase
    {
        /** @var  Sp4kAppsProductItem */
        public $item;

        public function execute()
        {
            if($this->state->get('config',false)) {
                if($exdates = $this->state->get('config.exdate',false)) {
                    foreach ($exdates as $index => &$exdate) {
                        $exdate = $exdate / 100;
                    }
                    $this->state->set('config.exdates',$exdates);
                }

            }

            $this->state->set('plugins',false);
            $app = new Sp4kAppsProductApp(
                new Joomla\Registry\Registry($this->state->toObject())
            );





            $this->item = $app->getItem();
            //$this->item->config = $this->item->config;
        }

        public function update(){

            //look up
            $this->item->update();
        }
    }