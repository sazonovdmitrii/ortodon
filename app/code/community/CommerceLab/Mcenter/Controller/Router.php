<?php
/**
 * CommerceLab Co.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the CommerceLab License Agreement
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://commerce-lab.com/LICENSE.txt
 *
 * @category   CommerceLab
 * @package    CommerceLab_Mcenter
 * @copyright  Copyright (c) 2012 CommerceLab Co. (http://commerce-lab.com)
 * @license    http://commerce-lab.com/LICENSE.txt
 */

class CommerceLab_Mcenter_Controller_Router extends Mage_Core_Controller_Varien_Router_Abstract
{
    public function initControllerRouters($observer)
    {
        $front = $observer->getEvent()->getFront();
        $mcenter = new CommerceLab_Mcenter_Controller_Router();
        $front->addRouter('clmcenter', $mcenter);
    }

    public function match(Zend_Controller_Request_Http $request)
    {

        if (!Mage::app()->isInstalled()) {
            Mage::app()->getFrontController()->getResponse()
                ->setRedirect(Mage::getUrl('install'))
                ->sendResponse();
            exit;
        }

        $route = Mage::helper('clmcenter')->getRoute();

        $identifier = $request->getPathInfo();

        if (substr(str_replace("/", "", $identifier), 0, strlen($route)) != $route) {
            return false;
        }


        $identifier = substr_replace($request->getPathInfo(), '', 0, strlen("/" . $route. "/"));
        $identifier = str_replace(Mage::helper('clmcenter')->getMcenteritemUrlSuffix(), '', $identifier);

        if (substr($request->getPathInfo(), 0, 7) !== '/clmcenter') {
            if ($identifier == '') {
                $request->setModuleName('clmcenter')
                    ->setControllerName('index')
                    ->setActionName('mainview');
                    return true;
            } elseif (substr($identifier, 0, 9) === 'category/') {
                $len = strcspn($identifier, '/');
                $key = substr($identifier, ($len+1));
                $request->setModuleName('clmcenter')
                    ->setControllerName('index')
                    ->setActionName('index')
                    ->setParam('category', $key);
                return true;
            } elseif (substr($identifier, 0, 4) === 'rss/') {
                $request->setModuleName('clmcenter')
                    ->setControllerName('rss')
                    ->setActionName('index');
                return true;
            } elseif ($pos = strpos($identifier, '/print/article/')) {
                $param = substr($identifier, $pos+15);
                $param = trim(str_replace('/', '', $param));
                $request->setModuleName('clmcenter')
                ->setControllerName('mcenteritem')
                    ->setActionName('print')
                    ->setParam('article', $param);
                return true;
            } elseif (substr($identifier, 0, 9) === 'mcenteritem/') {
                $str = str_replace('mcenteritem/view/id/', '', $identifier);
                $len = strcspn($identifier, '/');
                $id = substr($identifier, 0, $len);
                $request->setModuleName('clmcenter')
                    ->setControllerName('mcenteritem')
                    ->setActionName('view')
                    ->setParam('id', $id);
                return true;
            } elseif ($pos = strpos($identifier, '/q/')) {
                $param = substr($identifier, $pos+2);
                $param = trim(str_replace('/', '', $param));
                $request->setModuleName('clmcenter')
                ->setControllerName('index')
                    ->setActionName('index')
                    ->setParam('q', $param);
                return true;
            } elseif (substr($identifier, 0, 15) !== 'adminhtml_mcenter/' && strpos($identifier, '/')
                                       && substr($request->getPathInfo(), 0, strlen($route)+2) === '/'.$route.'/'
                                       && strpos($identifier, 'category/') === false) {
                $len = strcspn($identifier, '/');
                $category = substr($identifier, 0, $len);
                $key = substr($identifier, ($len+1));
                $request->setModuleName('clmcenter')
                    ->setControllerName('mcenteritem')
                    ->setActionName('view')
                    ->setParams(array('category' => $category, 'key' => $key));
                return true;
            } elseif (substr($request->getPathInfo(), 0, strlen($route)+2) === '/'.$route.'/') {
                $request->setModuleName('clmcenter')
                    ->setControllerName('mcenteritem')
                    ->setActionName('view')
                    ->setParam('key', $identifier);
                return true;
            }
        }

        return false;
    }
}
