<?php
class Ec_B2bUploadfileModuleFrontController extends ModuleFrontController
{
    public function initContent()
    {
        $this->ajax = true;
        parent::initContent();
    }
    // displayAjax for FrontEnd Invoke the ajax action
    // ajaxProcess for BackEnd Invoke the ajax action

    public function displayAjaxUploadfile()
    {
        $var1 = Tools::getValue('var1');
        $var2 = Tools::getValue('var2');
        $var3 = Tools::getValue('var3');
//echo "DUPA";
        header('Content-Type: application/json');
        die(Tools::jsonEncode(['var1'=> $_FILES]));
    }
}