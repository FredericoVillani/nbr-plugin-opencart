<?php

class ControllerPaymentNiobio extends Controller {

  protected function index(){
    $this->load->library('niobio');
    $this->load->model('payment/niobio');
    $this->load->model('checkout/order');

    $order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

    $this->language->load('payment/niobio');
    $this->data['text_information'] = $this->language->get('text_information');
    $this->data['text_description'] = $this->language->get('text_description');
    $this->data['button_confirm'] = $this->language->get('button_confirm');
    $this->data['continue'] = $this->url->link('checkout/niobio');

    if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/niobio.tpl')){
      $this->template = $this->config->get('config_template') . '/template/payment/niobio.tpl';
      } else {
      $this->template = 'default/template/payment/niobio.tpl';
    }

    $this->session->data['niobio_wallet_address'] = $this->config->get('niobio_wallet_address');
    $this->session->data['niobio_payment_id'] = Niobio::genPaymentId();
    $this->session->data['niobio_order_id'] = $this->session->data['order_id'];
    $this->session->data['niobio_total'] = floatval($order_info['total']);
    $this->session->data['niobio_store_name'] = str_replace(' ', '%20', $order_info['store_name']);

    $this->render();
  }

  public function confirm(){
    $this->load->model('checkout/order');
    $this->load->model('payment/niobio');
    $comment  = $this->session->data['niobio_wallet_address'] . '<br />';
    $comment .= $this->session->data['niobio_payment_id'];
    $this->model_payment_niobio->addNiobioOrder($this->session->data['order_id'], $this->session->data['niobio_payment_id']);
    $this->model_checkout_order->confirm($this->session->data['order_id'], $this->config->get('niobio_order_status_id'), $comment, true);
  }

  public function api(){
    $this->load->library('niobio');
    $this->language->load('checkout/niobio');
    $niobio = new Niobio($this->config->get('niobio_wallet_address'),
                       $this->config->get('niobio_wallet_host'),
                       $this->config->get('niobio_wallet_port'),
                       $this->config->get('niobio_wallet_ssl'),
                       $this->config->get('niobio_wallet_type'));
    $niobio->setTxConf($this->config->get('niobio_wallet_tx_conf'));
    $status = array();
    $payment = array();
    if (isset($this->request->get['niobio_payment_id']) and isset($this->session->data['niobio_payment_id'])){
      if ($this->request->get['niobio_payment_id'] == $this->session->data['niobio_payment_id']){
        $status = $niobio->getStatus();
        if ($status['status']){
          $payment = $niobio->getStatusPayment($this->session->data['niobio_payment_id']);
          $args['status'] = true;
          $args['tx_conf'] = $this->config->get('niobio_wallet_tx_conf');
          $args['lang']['text_payment_wait'] = $this->language->get('text_payment_wait');
          $args['lang']['text_payment_unconf'] = $this->language->get('text_payment_unconf');
          $args['lang']['text_payment_conf'] = $this->language->get('text_payment_conf');
          $args['payment']['tx_conf'] = $payment['tx_conf'];
          echo json_encode($args);
        }
      }
    }
  }

  public function cron(){
    $this->load->model('payment/niobio');
    if ($this->config->get('niobio_status')){
      $this->model_payment_niobio->NiobioConfirmPayment();
      echo 'Niobio init ...';
      } else {
      echo 'Niobio disable!';
    }
  }

}

?>
