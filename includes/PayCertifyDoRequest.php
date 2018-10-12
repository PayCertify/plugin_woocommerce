<?php

abstract class PayCertifyDoRequest {

    protected $_api_token;
    protected $_post_string;
    protected $_post_url;
    

    /**
     * Get the post url.
     */

    abstract protected function _getPostUrl();

    /**
     * Set the _post_string
     */
    abstract protected function _setPostString();

    /**
     * Handle the response string
     */
    abstract protected function _handleResponse($string);

    /**
     * Constructor.
     *
     * @param string $api_token       
     */
    public function __construct($payCertify) {
        $this->payCertify = $payCertify;
        $this->_api_token = $this->payCertify->getSetting('api_token');
    }


    /**
     * Return the post string.
     *
     * @return string
     */
    public function _post_string() {
        return $this->_post_string;
    }

    /**
     * Posts the request to PayCertify & returns response.
     *
     * @return PayCertify_Response
     */
    protected function _sendRequest() {

        $headers = array(
            "Authorization: Bearer $this->_api_token",
        );

        $this->_setPostString();

        $post_url = $this->_getPostUrl();
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_URL, $post_url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $this->_post_string);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_VERBOSE, 1);
        //curl_setopt($ch, CURLOPT_HEADER, 1);
        $response = curl_exec($ch);
        $curl_error = curl_error($ch);
        curl_close($ch);

        return $this->_handleResponse($response);
    }

}

?>
