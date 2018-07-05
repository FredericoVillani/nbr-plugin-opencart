<?php

class ModelPaymentNiobio extends Model {

  public function getMethod($address, $total){
    $this->language->load('payment/niobio');

    $query = NULL;
    $sql_q = "";
    $sql_q  = "SELECT * FROM `" . DB_PREFIX . "zone_to_geo_zone` WHERE";
    $sql_q .= " `geo_zone_id` = " . (int)$this->config->get('niobio_geo_zone_id') . " AND";
    $sql_q .= " `country_id` = " . (int)$address['country_id'] . " AND";
    $sql_q .= " (`zone_id` = " . (int)$address['zone_id'] . " OR zone_id = 0)";
    $query = $this->db->query($sql_q)->rows;

    if ($total > 0 and ($this->config->get('niobio_geo_zone_id') == 0 or count($query) == 1)){
      $status = true;
      } else {
      $status = false;
    }

    $method_data = array();

    if ($status){
      $method_data = array(
        'code' => 'niobio',
        'title' => $this->language->get('text_title'),
        'sort_order' => $this->config->get('niobio_sort_order')
      );
    }

    return $method_data;
  }

  public function addNiobioOrder($order_id, $payment_id){
    $query = NULL;
    $sql_q = "";
    $sql_q  = "INSERT INTO `" . DB_PREFIX . "order_niobio` (`order_id`, `payment_id`)";
    $sql_q .= " VALUES (" . (int)$order_id . ", '" . $payment_id . "')";
    $this->db->query($sql_q);
    return true;
  }

  public function getNiobioPaymentId($order_id){
    $query = NULL;
    $sql_q = "";
    $sql_q  = "SELECT `payment_id` FROM `" . DB_PREFIX . "order_niobio` WHERE";
    $sql_q .= " `order_id` = " . (int)$order_id;
    $query = $this->db->query($sql_q)->rows;
    $payment_id = '';
    if (count($query) == 1){
      $status = true;
      foreach ($query as $result){
        $payment_id = $result['payment_id'];
      }
    }
    return $payment_id;
  }

  public function NiobioConfirmPayment(){
    $this->load->library('niobio');
    $this->load->model('checkout/order');
    $this->language->load('payment/niobio');
    $niobio = new Niobio($this->config->get('niobio_wallet_address'),
                       $this->config->get('niobio_wallet_host'),
                       $this->config->get('niobio_wallet_port'),
                       $this->config->get('niobio_wallet_ssl'),
                       $this->config->get('niobio_wallet_type'));
    $niobio->setTxConf($this->config->get('niobio_wallet_tx_conf'));

    $query = NULL;
    $sql_q = "";
    $sql_q  = "SELECT `order_id` FROM `" . DB_PREFIX . "order`";
    $sql_q .= " WHERE `payment_code` = 'niobio' AND";
    $sql_q .= " `order_status_id` = " . (int)$this->config->get('niobio_order_status_id');
    $query = $this->db->query($sql_q)->rows;
    $data = array();
    $r_n = 0;
    $logger = new Log('Niobio.log');
    if (count($query) > 0){
      foreach ($query as $result){
        if (isset($result['order_id'])){
          $order_id = $result['order_id'];
          $payment_id = $this->getNiobioPaymentId($order_id);
          if ($payment_id != ''){
            $payinfo = $niobio->getStatusPayment($payment_id);
            $logger->write('Payment candidate: order - ' . $order_id . ', payment_id - ' . $payment_id);
            if (is_array($payinfo)){
              if ($payinfo['status']){
                $order_info = $this->model_checkout_order->getOrder($order_id);
                if ($payinfo['amount'] >= $order_info['total']){
                  $comm = '';
                  $comm = $this->language->get('text_payment') . $order_id;
                  $this->model_checkout_order->update($order_id, $this->config->get('niobio_order_payment_status_id'), $comm, true);
                  $data[$r_n]['order_id'] = $order_id;
                  $data[$r_n]['order_id'] = $payment_id;
                  $logger->write('Payment confirm: order - ' . $order_id . ', payment_id - ' . $payment_id);
                  $r_n++;
                  } else {
                  $logger->write('Payment error: order - ' . $order_id . ', payment_id - ' . $payment_id);
                }
              }
            }
          }
        }
      }
    }
    return $data;
  }

}

?>
