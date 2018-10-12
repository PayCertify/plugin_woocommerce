<?php

class PayCertifyDoSale extends PayCertifyDoRequest {

    const POST_URL = "https://gateway-api.paycertify.com/api/";
    const TEST_URL = "https://qa-gateway-api.paycertify.com/api/";

    private $_post_fields = array();

    public function _wc_logger($msg) {
        $log = new WC_Logger();
        $log->add('response', $msg);
    }

    protected function _getPostUrl() {
        //return self::SALE_URL;
        return $this->_post_url;
    }

    public function capturePayment() {
        $url = "transactions/sale";
        $this->_setPostUrl($url);
        return $this->_sendRequest();
    }

    public function doRefund($txn_id) {
        $url = "transactions/".$txn_id . "/refund";
        $this->_setPostUrl($url);
        return $this->_sendRequest();
    }

    public function chargeLater() {
        // To be implemented
    }

    public function _setPostUrl($url) {
        if ($this->payCertify->getSetting('test_mode_enabled') == 'yes') {
            $this->_post_url = self::TEST_URL . $url;
        } else {
            $this->_post_url = self::POST_URL . $url;
        }
    }

    public function setFields($fields) {
        $array = (array) $fields;
        foreach ($array as $key => $value) {
            $this->setField($key, $value);
        }
    }

    public function setField($name, $value) {
        $this->_post_fields[$name] = $value;
    }

    protected function _setPostString() {

        $this->_post_string = "";
        $this->_post_string = http_build_query($this->_post_fields) . "\n";
    }

    protected function _handleResponse($json_response) {

        $result = array();

        if ($json_response) {
            $response = json_decode($json_response);
            
            if ($response->error) {
				$this->_wc_logger(print_r($response, true));
                $errors = $response->error->message->base;
                $result['error'] = $errors;
            }
            if ($response->transaction->events[0]) {
                $result['event'] = $response->transaction->events[0];
                $result['txn_id'] = $response->transaction->id;
            }

            return $result;
        }
    }

}

?>
