<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class ShoppingCartPage extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->Model('Public_model');
    }

    public function index()
    {
        $data = array();
        $head = array();
        $arrSeo = $this->Public_model->getSeo('shoppingcart');
        $head['title'] = @$arrSeo['title'];
        $head['description'] = @$arrSeo['description'];
        $head['keywords'] = str_replace(" ", ",", $head['title']);
	    $data['shippingOrder'] = $this->Home_admin_model->getValueStore('shippingOrder');
	    $data['deliveryFee'] = $this->Home_admin_model->getValueStore('deliveryFee');
        $this->render('shopping_cart', $head, $data);
    }

}
