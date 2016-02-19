    <?php
    /**
     * Created by PhpStorm.
     * User: Ironman
     * Date: 8/12/2015
     * Time: 3:56 PM
     */

        require(JPATH_BASE.'/libraries/sp4k/lib/braintree/Braintree.php');


    class Sp4kAppsBraintreeApp extends Sp4kAppsAbstractApp
    {
        use Sp4kAppsBraintreeTrait;

        /** @var  Sp4kAppsPaypalItem */
        public $item;

        /** @var  Sp4kAppsPaypalItems[] */
        public $items;

        protected $_statekey = 'Sp4kAppsBraintreeApp';

        public function getItem()
        {
            return $this->item = $this->getItemInstance($this->getState());
        }

        public static function getToken(){

            Braintree_Configuration::environment('sandbox');
            Braintree_Configuration::merchantId('d6pzwpxx9xjggn3n');
            Braintree_Configuration::publicKey('2xrnyybt2ky53v6z');
            Braintree_Configuration::privateKey('2db58fa249275b116bc5ac918f12149c');
            return Braintree_ClientToken::generate();
        }


    }