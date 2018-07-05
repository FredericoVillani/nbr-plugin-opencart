
function NiobioUpdate(obj){
  var niobio_info_obj = document.getElementById('payment_status');
  var niobio_tx_conf = obj.tx_conf;
  var lang_payment_wait = obj.lang.text_payment_wait;
  var lang_payment_unconf = obj.lang.text_payment_unconf;
  var lang_payment_conf = obj.lang.text_payment_conf;
  var payment_tx_conf = obj.payment.tx_conf;
  if (niobio_info_obj.style.display == 'none') niobio_info_obj.style.display = 'inline';
  if (payment_tx_conf == 0){
    niobio_info_obj.innerHTML = ' (' + lang_payment_wait + ')';
  }
  if (payment_tx_conf < niobio_tx_conf && payment_tx_conf > 0){
    niobio_info_obj.innerHTML = ' (' + lang_payment_unconf + ': ' + payment_tx_conf + '/' + niobio_tx_conf + ')';
  }
  if (payment_tx_conf >= niobio_tx_conf){
    niobio_info_obj.innerHTML = ' (' + lang_payment_conf + ')';
  }
}

function NiobioUpdateInit(){
  if (document.getElementById('payment_status') != null && document.getElementById('niobio_payment_id') != null){
    var payment_id = document.getElementById('niobio_payment_id').innerHTML;
    $.ajax({
      type: "GET",
      url: 'index.php?route=payment/niobio/api&niobio_payment_id=' + payment_id,
      success: function(msg){
        var obj = jQuery.parseJSON(msg);
        if (obj.status){
          NiobioUpdate(obj);
        }
      }
    });
  }
  setTimeout(NiobioUpdateInit, 120000);
}

$(document).ready(function(){
  NiobioUpdateInit();
});

