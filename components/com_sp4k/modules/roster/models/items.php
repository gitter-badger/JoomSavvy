<?php
    
    /**
     * Created by Joseph Cardwell.
     * User: Ironman
     * Date: 12/18/2015
     * Time: 6:55 PM
     */

    use Joomla\Registry\Registry;

    class Sp4kModulesRosterModelsItems extends JModelBase
    {
        public function execute()
        {
            $this->load();
        }

        public function load(){
            $date = (int)$this->state->get('date', strtotime('today'));
            $product_id = (int)$this->state->get('id');

            $sql = "SELECT * FROM z_sp4k_registration_items WHERE product_id = $product_id AND status='active'" ;

            $registrations = JFactory::getDbo()->setQuery($sql)->loadAssocList();

            foreach($registrations as &$registration){
                $registration['child'] = Sp4kAppsChildApp::getInstance(
                    new Registry(['id'=>$registration['child_id']])
                )->getItem();
            }


            $sql = "select * from #__sp4k_roster_items where product_id = $product_id AND date =$date";
            $roster = JFactory::getDbo()->setQuery($sql)->loadAssoc();

            if($roster){
                //build attendees against roster
                $attendees = json_decode($roster['roster']);

            }else{
                $attendees = 0;
                //just build the roster with the registrants
            }

            foreach($registrations as &$registration){
                if($roster){
                    $registration['attending'] = $attendees->{$registration['child_id']};
                }else{
                    $registration['attending'] = 0;
                }
            }

            $this->items = $registrations;
        }

        public function save(){
            if($this->state->get('data')['id']){
                JFactory::getDbo()->updateObject($this->state->get('data'));
            }else{
                JFactory::getDbo()->insertObject($this->state->get('data'));
            }
        }
    }