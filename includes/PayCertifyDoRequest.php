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
        $this->_setPostString();
        $post_url = $this->_getPostUrl();
        $headers = array(
            "Authorization: Bearer $this->_api_token",
        );

        return $this->_handleResponse(
            wp_remote_post( $post_url, array(
                'method'      => 'POST',
                'httpversion' => '1.0',
                'blocking'    => true,
                'headers'     => $headers,
                'body'        => $this->_post_string,
                'cookies'     => array()
            ))
        );
    }
}

?>
