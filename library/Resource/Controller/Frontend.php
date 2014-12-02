<?php

    /**
     * Company.com, v 1.0.0, Tue 05 Aug 2014 14:34:56
     *
     * Company.com, Web application company
     * http://www.Company.com
     *
     * Copyright (c) 2014 Comapny, Co.
     * All right reserved.
     *
     * Date : 04 Aug 2014
     */

    /**
     * Class Resource_Controller_Frontend
     * Brief To manage request from website module
     *
     * @author <a href="mailto: ankur@Company.com">Ankur Raiyani</a>
     * @copyright Company, Co., 02-08-2014
     *
     * Date : 04-08-2014
     **/
    class Resource_Controller_Frontend extends Resource_Controller_Action
    {
        /**
         * Public static variable
         * @var Zend_Translate
         */
        public static $Translate;
        public static $TimeZone;

        /**
         * 	Public variable
         * 	@var Zend_Session
         */
        public $session;

        /**
         * 	Public variable
         * 	@var Zend_Db_Adapter_Mysqli
         */
        public $Database;

        /**
         * 	Static variable
         */
        static $Lang;

        public $Users;
        public $UsersProfile;

        public $DisabledControllers = array(
            'index', 'rpc'
        );

        public function init()
        {
            global $Database;

            try {

                $this->initCustomView();
            }
            catch (Exception $e) {

                throw $e;
            }

        }

        protected function initCustomView()
        {
            // Initialize database object
            global $Database;
            $this->Database = $Database;

            // Start session
            Zend_Session::start();

            // Retrive requested module name and controller name
            $Module = $this->getRequest()->getModuleName();
            $Controller = $this->getRequest()->getControllerName();
            $Action = $this->getRequest()->getActionName();

            $this->view->module = $Module;
            $this->view->controller = $Controller;
            $this->view->action = $Action;

            ///////////////////////////////////////////////////////////
            /* 	Placed to resolve server problems				     */

            $Parameters = $this->getRequest()->getParams();

            if(isset($Parameters[0]))
            {
                parse_str($Parameters[0], $UserParams);

                if($_GET)
                {
                    $_GET = array_merge($Parameters, $UserParams);
                }
                else
                {
                    $_POST = array_merge($Parameters, $UserParams);
                }
            }
            ///////////////////////////////////////////////////////////

            /* To Initiate Translation module */
            if(DEVELOP_VERSION)
            {
                Resource_Cache::clearTag('ProjectManagement_Front');
            }
            $this->initTranslation($Module, $Controller);

            if($Module == "website" && in_array(strtolower($Controller), $this->DisabledControllers))
            {
                if($Action == 'index')
                {
                    $this->_helper->layout->disableLayout();
                }
                /*else
                {
                    $options = array(
                        'layout'     => 'home',
                        'layoutPath' => APPLICATION_PATH.'/layouts/',
                        'contentKey' => 'content',  // ignored when MVC not used
                    );

                    $layout = Zend_Layout::startMvc($options);
                }*/
            }
            else
            {
                if(isset($_GET['skin']) && (!isset($_SESSION['theme_skin']) || isset($_SESSION['theme_skin']) && $_SESSION['theme_skin'] != $_GET['skin']))
                {
                    $_SESSION['theme_skin'] = $_GET['skin'];
                }
                $options = array(
                    'layout'     => 'default',
                    'layoutPath' => APPLICATION_PATH.'/layouts/',
                    'contentKey' => 'content',  // ignored when MVC not used
                );

                $layout = Zend_Layout::startMvc($options);

                $this->checkAuthentication($Module, $Controller);
            }
        }

        /**
         * 	Function checkAuthentication
         * 	Authenticate admin that it's already login or not
         *
         * 	Date: 02 Sept 2014
         * 	@author Ankur Raiyani
         */

        public function checkAuthentication($Module, $Controller)
        {
            if(!($Module == "website" && $Controller ==  "login"))
            {
                if(!Zend_Session::namespaceIsset("WebsiteNamespace"))
                {
                    $this->_redirect(SITE_URL);
                }
                else
                {
                    $UModel = new Users_Model();
                    $this->session = new Zend_Session_Namespace('WebsiteNamespace');
                    if ($UModel->getUserState($this->session->UserObject['user_id']))
                    {
                        $this->Users = $this->session->UserObject;
                        $this->view->Username = $this->Users['userfirstname']. " ".$this->Users['userlastname'];
                        $this->view->Userid = $this->Users['user_id'];

                        $this->UsersProfile = $UModel->getUserProfile($this->Users['user_id']);
                        $this->view->UsersProfile = $this->UsersProfile;
                        //e_l($this->view->UsersProfile);
                        unset($UModel);

                        // YOG: Set time to zone to local variable
                        self::$TimeZone = $this->session->TimeZone;
                        // YOG: Set time zone for all controller
                        $this->view->TimeZone = self::$TimeZone;

                        if(isset($this->session->Lang) && $this->session->Lang != NULL)
                        {
                            self::$Lang = $this->session->Lang;
                            self::$Translate->setLocale(self::$Lang);
                            $this->view->Lang = $this->session->Lang;
                        }
                    }
                    else
                    {
                        if(Zend_Session::namespaceIsset("WebsiteNamespace"))
                        {
                            Zend_Session::namespaceUnset("WebsiteNamespace");
                            unset($this->session);
                        }
                        $this->_redirect(SITE_URL);
                    }
                }

            }
            else if($Module == "website" && $Controller ==  "login")
            {
                if(Zend_Session::namespaceIsset("WebsiteNamespace"))
                {
                    if($this->getRequest()->getActionName() != "logout" && $this->getRequest()->getParam("username","") != '')
                    {
                        echo json_encode(array('result' => "Success"));die;
                    }

                    if($this->getRequest()->getActionName() != "logout")
                    {
                        $this->_redirect(SITE_URL.'dashboard/');
                    }
                }
            }
            else
            {
                $this->_redirect(SITE_URL);
            }
        }

        /**
         * 	Function initTranslation
         * 	Fetch Language keys and it's values from language file and from
         * 	database and add into language translation
         *
         * 	Date: 10-09-2014
         * 	@author Ankur Raiyani
         */

        public static function initTranslation($Module, $Controller)
        {
            $Language = new Language_Model();
            $_LangCache = Resource_Cache::getDefaultCache();
            Zend_Translate::setCache($_LangCache);

            try
            {
                $Lang = isset($_COOKIE['Language']) ? $_COOKIE['Language'] : $Language->getLanguageCode();
                // Set default language.
                $LangFile = LANGUAGE_PATH. "/".$Lang.".php";
                $_Translate = new Zend_Translate(
                    array(
                        'adapter' => 'array',
                        'content' => $LangFile,
                        'locale'  => $Lang,
                        'tag'     => 'ProjectManagement_Front'
                    )
                );

                $LanguageList = $Language->listAllLanguages();

                if(count($LanguageList) > 0)
                {
                    foreach ($LanguageList as $Index => $Value)
                    {
                        // Load Language base file
                        $LangFile = LANGUAGE_PATH. "/".$Value["langcode"].".php";
                        $_Translate->addTranslation(
                            array(
                                'content' => $LangFile,
                                'locale'  => $Value["langcode"]
                            )
                        );

                        // Load Language Definition key
                        $LangDefinition = $Language->getLanguageDefinition($Value["langid"], ucfirst($Module));
                        if(count($LangDefinition) > 0)
                        {
                            $_Translate->addTranslation(
                                array(
                                    'content' => $LangDefinition,
                                    'locale'  => $Value["langcode"]
                                )
                            );
                        } unset($LangDefinition);

                        // Load Module language file
                        $LangFile = LANGUAGE_PATH. "/".$Module."/".$Value["langcode"]."/".$Controller.".php";
                        if(file_exists($LangFile))
                        {
                            $_Translate->addTranslation(
                                array(
                                    'content' => $LangFile,
                                    'locale'  => $Value["langcode"]
                                )
                            );
                        }
                        unset($LangFile);
                    }
                }

                // Language log writer.
                // Create a log instance
                $_LogWriter = new Zend_Log_Writer_Stream( LANGUAGE_DIRECTORY .  '/lang_website.log' );
                $_Log = new Zend_Log($_LogWriter);

                // YOG: Attach it to the translation instance
                $_Translate->setOptions(
                    array(
                        'log'             => $_Log,
                        'logMessage'      => "Missing '%message%' within locale '%locale%'",
                        'logUntranslated' => true
                    )
                );


                // Set Locale Language
                $_Translate->setLocale($Lang);
                self::$Translate = $_Translate;
                Zend_Registry::set('Zend_Translate', $_Translate);

                self::$Lang = $Lang;
            }
            catch (Exception $exception)
            {
                throw $exception;
            }
        }

        function __call($method, $args)
        {
            throw new Exception("Action does not exist"); // This is done by default
            // Just do whatever you want to do in this function (like redirecting)
        }

    }
