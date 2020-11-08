<?php

/**
 * Copyright since 2007 PrestaShop SA and Contributors
 * PrestaShop is an International Registered Trademark & Property of PrestaShop SA
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/OSL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to https://devdocs.prestashop.com/ for more information.
 *
 * @author    PrestaShop SA and Contributors <contact@prestashop.com>
 * @copyright Since 2007 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 */
declare(strict_types=1);



use PrestaShop\PrestaShop\Adapter\SymfonyContainer;
use PrestaShop\Module\Ec_Xmlfeed\Entity;

class Ec_B2b extends Module
{
    private $saveinvoiceaddress=0;

    public function __construct()
    {
        $this->name = 'ec_b2b';
        $this->author = 'kmkm2';
        $this->version = '1.0.0';
        $this->need_instance = 0;

        $this->bootstrap = true;
        parent::__construct();

        $this->displayName = $this->trans('B2B', array(), 'Modules.Ec_B2b.Admin');
        $this->description = $this->trans(
            'Moduł do B2B',
            array(),
            'Modules.Ec_B2b.Admin'
        );

        $this->ps_versions_compliancy = array('min' => '1.7.1.0', 'max' => _PS_VERSION_);
    }

    public function install()
    {



        return parent::install()  && $this->registerHook('actionCartSave') && $this->registerHook('displayAfterCarrier')&& $this->registerHook('displayHeader')&& $this->registerHook('actionCarrierProcess');


    }

    public function hookdisplayHeader($params){
   if(isset($this->context->controller->php_self) && $this->context->controller->php_self=='order'){ //to add JS only at cart site

        $this->context->controller->addCSS(($this->_path).'views/uploadfile.css');



        $this->context->controller->addJS(($this->_path).'views/js/ec_b2b.js');
        $this->context->controller->addJS(($this->_path).'views/js/jquery.uploadfile.min.js',false);



      }
    }

public function hookactionCarrierProcess($params){

}

    private function getInvoiceAddressId($id_customer, $active = true)
    {
        if (!$id_customer) {
            return false;
        }
        $cache_id = 'getInvoiceAddressId' . (int) $id_customer . '-' . (bool) $active;
        if (!Cache::isStored($cache_id)) {
            $result = (int) Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue(
                '
				SELECT `id_address`
				FROM `' . _DB_PREFIX_ . 'address`
				WHERE `id_customer` = ' . (int) $id_customer . ' AND `deleted` = 0' . ($active ? ' AND `active` = 1' : '').' ORDER BY `id_address` ASC'
            );
            Cache::store($cache_id, $result);

            return $result;
        }

        return Cache::retrieve($cache_id);
    }


    public function hookDisplayAfterCarrier($params){




        $this->context->smarty->assign([
            'my_module_name' => Configuration::get('MYMODULE_NAME'),
            'my_module_link' => $this->context->link->getModuleLink('mymodule', 'display')
        ]);

        return $this->display(__FILE__, 'views/displayAfterCarrier.tpl');



    }


    function hookActionCartSave($params){


//die('hookActionValidateOrder');


if(    !$this->saveinvoiceaddress) {
    $this->saveinvoiceaddress=1;
    $this->context->cart->id_address_invoice = (int)$this->getInvoiceAddressId( $this->context->cart->id_customer);
    //$this->context->cart->id_address_delivery = 7;
    $this->context->cart->update();
}



    }


}
