<?php
/**
 * Created by PhpStorm.
 * User: Ironman
 * Date: 7/16/2015
 * Time: 1:17 PM
 */


class AccountControllersDisplay extends AccountControllersBase
{
    public function execute()
    {

        $viewRequest = $this->app->input->getCmd('view','account');

        if(!JobsHelpersAuthorize::link($viewRequest)){
            $redirect = ( $redirect = $this->app->input->getArray() )
                ? http_build_query($redirect,'&')
                : $_SERVER['HTTP_REFERER'];

            JFactory::$application->redirect(
                '/index.php?option=com_users&view=login&redirect='.base64_encode($redirect)
            );
        }

        $viewParts = explode('.',$viewRequest);

        $viewLayoutName = '';
        if(count($viewParts)>2){
            $viewLayoutName = array_pop($viewParts);
        }


        array_walk($viewParts,function($v,$k)use(&$viewName){
            $viewName .= ucfirst($v);
        });
        /** @var JModelBase $modelName */
        $modelName = 'AccountModels'.ucfirst($viewParts[0]);

        $formatName = ucfirst($this->app->input->getCmd('format','html'));

        $viewName = 'AccountViews'.$viewName.$formatName;
        /** @var JViewHtml $view */
        $view = new $viewName(
            new $modelName(
                new Joomla\Registry\Registry(
                    $this->app->input->getArray()
                )
            )
        );
        
        if($viewLayoutName !='')$view->setLayout($viewLayoutName);

        echo $view->render();
    }
}