<?php
    /**
     * Created by PhpStorm.
     * User: Ironman
     * Date: 8/12/2015
     * Time: 3:56 PM
     */


    class Sp4kAppsCartApp extends Sp4kAppsAbstractApp
    {
        use Sp4kAppsCartTrait;

        /** @var  Sp4kAppsCart */
        public $item;

        /** @var  Sp4kAppsCart */
        public $items;

        protected $_statekey = 'Sp4kAppsCartApp';
    }