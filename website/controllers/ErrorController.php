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
     * Class ErrorController
     * Brief Handle errors through Zend
     *
     * @author <a href="mailto: ankur@Company.com">Ankur Raiyani</a>
     * @copyright Krisil, Co., 02-08-2014
     *
     * Date : 04-08-2014
     **/
    class ErrorController extends Zend_Controller_Action
    {

        public function errorAction()
        {

            $errors = $this->_getParam('error_handler');
            $this->_helper->viewRenderer->setNoRender(true);
            if(DEVELOP_VERSION)
            {
                echo '<pre>';
                print_r($errors);die;
            }

            switch ($errors->type) {

                case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ROUTE:
                case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:
                    $this->_helper->layout()->disableLayout();
                    $this->view->message = '404: Not Found';
                    $this->getResponse()->setHttpResponseCode(404);
                    $this->render('error');
                    break;
                case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:
                    // 404 error -- controller or action not found
                    $this->getResponse()->setHttpResponseCode(404);
                    $this->view->message = '404: Not Found';
                    //$this->view->message = $errors->exception;//'Application error';
                    $this->render('error');
                    break;
                default:
                    // application error
                    $this->getResponse()->setHttpResponseCode(500);
                    $this->view->message = '500: An unexpected error occurred';
                    $this->render('error');
                    //$this->view->message = $errors->exception;//'Application error';
                    break;
            }

            // Log exception, if logger available
            if ($log = $this->getLog()) {
                $log->crit($this->view->message, $errors->exception);
            }

            // conditionally display exceptions
            if ($this->getInvokeArg('displayExceptions') == true) {
                $this->view->exception = $errors->exception;
            }

            $this->view->request   = $errors->request;

        }

        public function getLog()
        {
            $bootstrap = $this->getInvokeArg('bootstrap');
            if (!$bootstrap->hasPluginResource('Log')) {
                return false;
            }
            $log = $bootstrap->getResource('Log');
            return $log;
        }


    }
?>
