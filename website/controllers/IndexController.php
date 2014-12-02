<?php
    /**
     * Company.com, v 1.0.0, Tue 05 Aug 2014 14:34:56
     *
     * Company.com, Web application company
     * http://www.Company.com
     *
     * Copyright (c) 2014 Krisil, Co.
     * All right reserved.
     *
     * Date : 04 Aug 2014
     */

    /**
     * Class IndexController
     * Brief Manage Home page related activities
     *
     * @author <a href="mailto: ankur@Company.com">Ankur Raiyani</a>
     * @copyright Krisil, Co., 02-08-2014
     *
     * Date : 05-08-2014
     **/
    class IndexController extends Resource_Controller_Frontend
    {
        public function init()
        {
            parent::init();
        }

        public function indexAction()
        {
            $this->view->headTitle(parent::$Translate->_('Index_Index_Page_Title', $this->view->Lang));
        }
    }
