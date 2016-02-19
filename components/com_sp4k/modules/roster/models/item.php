    <?php
    
    /**
     * Created by Joseph Cardwell.
     * User: Ironman
     * Date: 12/18/2015
     * Time: 6:56 PM
     */

    class Sp4kModulesRosterModelsItem extends JModelBase
    {
        public function execute()
        {

            $product_id = (int)$this->state->get('product_id');
            $date = $this->state->get('date', strtotime('today')*1000)/1000;

            $sql = "select * from #__sp4k_roster_items where product_id = $product_id AND date =$date";
            $roster = JFactory::getDbo()->setQuery($sql)->loadObject();


            if(!$roster){//on first load, build the roster

                $roster = new stdClass();
                $roster->product_id = (int)$this->state->get('product_id');
                $roster->date = $date;
                $roster->roster = new stdClass();

                $sql = "SELECT * FROM z_sp4k_registration_items WHERE product_id = $product_id AND status='active'" ;
                $registrations = JFactory::getDbo()->setQuery($sql)->loadAssocList();

                foreach($registrations as $registration){
                    $roster->roster->{$registration['child_id']} = 0;
                }

                $roster->roster = json_encode($roster->roster);
                JFactory::getDbo()->insertObject('#__sp4k_roster_items',$roster,'id');

            }

            $roster->roster = json_decode($roster->roster);
            $roster->roster->{$this->state->get('child_id')} = (int)$this->state->get('state');
            $roster->roster = json_encode($roster->roster);

            JFactory::getDbo()->updateObject('#__sp4k_roster_items',$roster,'id');
        }
    }