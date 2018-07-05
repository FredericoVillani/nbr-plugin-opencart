<?php

class ControllerPaymentNiobio extends Controller {

  private $error = array(); 

  public function index(){
    $this->language->load('payment/niobio');
    $this->document->setTitle($this->language->get('heading_title'));
    $this->load->model('setting/setting');

    if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()){
      $this->model_setting_setting->editSetting('niobio', $this->request->post);
      $this->session->data['success'] = $this->language->get('text_success');
      $this->redirect($this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'));
    }

    $this->data['heading_title'] = $this->language->get('heading_title');
    $this->data['text_all_zones'] = $this->language->get('text_all_zones');
    $this->data['text_none'] = $this->language->get('text_none');
    $this->data['wallet_address'] = $this->language->get('wallet_address');
    $this->data['wallet_type'] = $this->language->get('wallet_type');
    $this->data['wallet_type_simplewallet'] = $this->language->get('wallet_type_simplewallet');
    $this->data['wallet_type_walletd'] = $this->language->get('wallet_type_walletd');
    $this->data['wallet_type_gateway'] = $this->language->get('wallet_type_gateway');
    $this->data['wallet_host'] = $this->language->get('wallet_host');
    $this->data['wallet_port'] = $this->language->get('wallet_port');
    $this->data['wallet_ssl'] = $this->language->get('wallet_ssl');
    $this->data['niobio_text_wallet_tx_conf'] = $this->language->get('wallet_tx_conf');
    $this->data['niobio_text_yes'] = $this->language->get('niobio_yes');
    $this->data['niobio_text_no'] = $this->language->get('niobio_no');
    $this->data['niobio_text_enable'] = $this->language->get('niobio_enable');
    $this->data['niobio_text_disable'] = $this->language->get('niobio_disable');
    $this->data['niobio_text_order_status'] = $this->language->get('niobio_order_status');
    $this->data['niobio_text_order_payment_status'] = $this->language->get('niobio_order_payment_status');
    $this->data['niobio_text_status'] = $this->language->get('niobio_status');
    $this->data['niobio_text_sort_order'] = $this->language->get('niobio_sort_order');
    $this->data['niobio_text_geo_zone'] = $this->language->get('niobio_geo_zone');
    $this->data['button_save'] = $this->language->get('button_save');
    $this->data['button_cancel'] = $this->language->get('button_cancel');

    if (isset($this->error['warning'])){
      $this->data['error_warning'] = $this->error['warning'];
      } else {
      $this->data['error_warning'] = '';
    }

    $this->data['breadcrumbs'] = array();
    $this->data['breadcrumbs'][] = array(
      'text' => $this->language->get('text_home'),
      'href' => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      'separator' => false
    );
    $this->data['breadcrumbs'][] = array(
      'text' => $this->language->get('text_payment'),
      'href' => $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'),       		
      'separator' => ' :: '
    );
    $this->data['breadcrumbs'][] = array(
      'text' => $this->language->get('heading_title'),
      'href' => $this->url->link('payment/niobio', 'token=' . $this->session->data['token'], 'SSL'),
      'separator' => ' :: '
    );

    $this->data['action'] = $this->url->link('payment/niobio', 'token=' . $this->session->data['token'], 'SSL');
    $this->data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL');	

    $this->data['niobio_wallet_address'] = $this->config->get('niobio_wallet_address');
    $this->data['niobio_wallet_type'] = $this->config->get('niobio_wallet_type');
    $this->data['niobio_wallet_host'] = $this->config->get('niobio_wallet_host');
    $this->data['niobio_wallet_port'] = $this->config->get('niobio_wallet_port');
    $this->data['niobio_wallet_ssl'] = $this->config->get('niobio_wallet_ssl');
    $this->data['niobio_wallet_tx_conf'] = $this->config->get('niobio_wallet_tx_conf');
    $this->data['niobio_order_status_id'] = $this->config->get('niobio_order_status_id');
    $this->data['niobio_order_payment_status_id'] = $this->config->get('niobio_order_payment_status_id');
    $this->data['niobio_geo_zone_id'] = $this->config->get('niobio_geo_zone_id');
    $this->data['niobio_status'] = $this->config->get('niobio_status');
    $this->data['niobio_sort_order'] = $this->config->get('niobio_sort_order');

    $this->load->model('localisation/order_status');
    $this->data['niobio_order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
    $this->load->model('localisation/geo_zone');
    $this->data['niobio_geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

    $this->template = 'payment/niobio.tpl';
    $this->children = array(
      'common/header',
      'common/footer'
    );

    $this->response->setOutput($this->render());
  }

  public function install(){
    $this->load->model('payment/niobio');
    $this->load->model('setting/setting');
    $settings['niobio_wallet_address']		= '';
    $settings['niobio_wallet_type']		= '2';
    $settings['niobio_wallet_host']		= '52.59.232.98';
    $settings['niobio_wallet_port']		= '8888';
    $settings['niobio_wallet_ssl']		= '0';
    $settings['niobio_wallet_tx_conf']		= '6';
    $settings['niobio_order_status_id']		= '1';
    $settings['niobio_order_payment_status_id']	= '2';
    $settings['niobio_geo_zone_id']		= '0';
    $settings['niobio_status']			= '0';
    $settings['niobio_sort_order']		= '0';
    $this->model_payment_niobio->install();
    $this->model_setting_setting->editSetting('niobio', $settings);
  }

  public function uninstall(){
    $this->load->model('payment/niobio');
    $this->load->model('setting/setting');
    $this->model_setting_setting->deleteSetting('niobio');
    $this->model_payment_niobio->uninstall();
  }

  protected function validate(){
    if (!$this->user->hasPermission('modify', 'payment/niobio')){
      $this->error['warning'] = $this->language->get('error_permission');
    }

    if (!$this->error){
      return true;
      } else {
      return false;
    }
  }

}

?>
