<?php
namespace common\helpers;
/**
 * A basic CURL wrapper
 *
 * See the README for documentation/examples or http://php.net/curl for more information about the libcurl extension for PHP
 *
 * @package curl
 * @author Sean Huber <shuber@huberry.com>
**/
class MyCurl {
    
    /**
     * The file to read and write cookies to for requests
     *
     * @var string
    **/
    public $cookie_file;
    
    /**
     * Default option add to any request to server
     * 
     **/
    protected  $default_options;
    /**
     * Determines whether or not requests should follow redirects
     *
     * @var boolean
    **/
    public $follow_redirects = true;
    
    /**
     * An associative array of headers to send along with requests
     *
     * @var array
    **/
    public $headers = array();
    
    /**
     * An associative array of CURLOPT options to send along with requests
     *
     * @var array
    **/
    public $options = array();
    
    /**
     * The referer header to send along with requests
     *
     * @var string
    **/
    public $referer;
    
    /**
     * The user agent to send along with requests
     *
     * @var string
    **/
    public $user_agent;
    
    /**
     * Stores an error string for the last request if one occurred
     *
     * @var string
     * @access protected
    **/
    protected $error = '';
    
    /**
     * Stores resource handle for the current CURL request
     *
     * @var resource
     * @access protected
    **/
    protected $request;
    
    /**
     * Initializes a Curl object
     *
     * Sets the $cookie_file to "curl_cookie.txt" in the current directory
     * Also sets the $user_agent to $_SERVER['HTTP_USER_AGENT'] if it exists, 'Curl/PHP '.PHP_VERSION.' (http://github.com/shuber/curl)' otherwise
    **/
    function __construct($default_options = array()) {
    	$this->default_options = $default_options;
        $this->cookie_file = dirname(__FILE__).DIRECTORY_SEPARATOR.'curl_cookie.txt';
        $this->user_agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : 'Curl/PHP '.PHP_VERSION.' (http://github.com/shuber/curl)';
    }
    
    /**
     * Makes an HTTP DELETE request to the specified $url with an optional array or string of $vars
     *
     * Returns a MyCurlResponse object if the request was successful, false otherwise
     *
     * @param string $url
     * @param array|string $vars 
     * @return MyCurlResponse object
    **/
    function delete($url, $vars = array()) {
        return $this->request('DELETE', $url, $vars);
    }
    
    /**
     * Returns the error string of the current request if one occurred
     *
     * @return string
    **/
    function error() {
        return $this->error;
    }
    
    /**
     * Makes an HTTP GET request to the specified $url with an optional array or string of $vars
     *
     * Returns a MyCurlResponse object if the request was successful, false otherwise
     *
     * @param string $url
     * @param array|string $vars 
     * @return MyCurlResponse
    **/
    function get($url, $vars = array()) {
    	if(!empty($this->default_options)){
    		$url .= (stripos($url, '?') !== false) ? '&' : '?';
    		$url .= (is_string($this->default_options)) ? $this->default_options : http_build_query($this->default_options, '', '&');
    	}
        if (!empty($vars)) {
            $url .= (stripos($url, '?') !== false) ? '&' : '?';
            $url .= (is_string($vars)) ? $vars : http_build_query($vars, '', '&');
        }
        return $this->request('GET', $url);
    }
    
    /**
     * Makes an HTTP HEAD request to the specified $url with an optional array or string of $vars
     *
     * Returns a MyCurlResponse object if the request was successful, false otherwise
     *
     * @param string $url
     * @param array|string $vars
     * @return MyCurlResponse
    **/
    function head($url, $vars = array()) {
        return $this->request('HEAD', $url, $vars);
    }
    
    /**
     * Makes an HTTP POST request to the specified $url with an optional array or string of $vars
     *
     * @param string $url
     * @param array|string $vars 
     * @return MyCurlResponse|boolean
    **/
    function post($url, $vars = array()) {
        return $this->request('POST', $url, $vars);
    }
    
    /**
     * Makes an HTTP PUT request to the specified $url with an optional array or string of $vars
     *
     * Returns a MyCurlResponse object if the request was successful, false otherwise
     *
     * @param string $url
     * @param array|string $vars 
     * @return MyCurlResponse|boolean
    **/
    function put($url, $vars = array()) {
        return $this->request('PUT', $url, $vars);
    }
    
    /**
     * Makes an HTTP request of the specified $method to a $url with an optional array or string of $vars
     *
     * Returns a MyCurlResponse object if the request was successful, false otherwise
     *
     * @param string $method
     * @param string $url
     * @param array|string $vars
     * @return MyCurlResponse|boolean
    **/
    function request($method, $url, $vars = array()) {
        $this->error = '';
        $this->request = curl_init();
        if (is_array($vars)) $vars = http_build_query($vars, '', '&');
        
        $this->set_request_method($method);
        $this->set_request_options($url, $vars);
        $this->set_request_headers();

        $response = curl_exec($this->request);

        if ($response) {
            $response = new MyCurlResponse($response);
        } else {
            $this->error = curl_errno($this->request).' - '.curl_error($this->request);
        }

        curl_close($this->request);
        
        return $response;
    }
    
    /**
     * Formats and adds custom headers to the current request
     *
     * @return void
     * @access protected
    **/
    protected function set_request_headers() {
        $headers = array();
        foreach ($this->headers as $key => $value) {
            $headers[] = $key.': '.$value;
        }
        curl_setopt($this->request, CURLOPT_HTTPHEADER, $headers);
    }
    
    /**
     * Set the associated CURL options for a request method
     *
     * @param string $method
     * @return void
     * @access protected
    **/
    protected function set_request_method($method) {
        switch (strtoupper($method)) {
            case 'HEAD':
                curl_setopt($this->request, CURLOPT_NOBODY, true);
                break;
            case 'GET':
                curl_setopt($this->request, CURLOPT_HTTPGET, true);
                break;
            case 'POST':
                curl_setopt($this->request, CURLOPT_POST, true);
                break;
            default:
                curl_setopt($this->request, CURLOPT_CUSTOMREQUEST, $method);
        }
    }
    
    /**
     * Sets the CURLOPT options for the current request
     *
     * @param string $url
     * @param string $vars
     * @return void
     * @access protected
    **/
    protected function set_request_options($url, $vars) {

        curl_setopt($this->request, CURLOPT_URL, $url);
        if (!empty($vars)) curl_setopt($this->request, CURLOPT_POSTFIELDS, $vars);
        
        # Set some default CURL options
        curl_setopt($this->request, CURLOPT_HEADER, true);
        curl_setopt($this->request, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->request, CURLOPT_USERAGENT, $this->user_agent);
        curl_setopt($this->request, CURLOPT_SSL_VERIFYPEER, false);
        if ($this->cookie_file) {
            curl_setopt($this->request, CURLOPT_COOKIEFILE, $this->cookie_file);
            curl_setopt($this->request, CURLOPT_COOKIEJAR, $this->cookie_file);
        }
        if ($this->follow_redirects) curl_setopt($this->request, CURLOPT_FOLLOWLOCATION, true);
        if ($this->referer) curl_setopt($this->request, CURLOPT_REFERER, $this->referer);
        
        # Set any custom CURL options
        foreach ($this->options as $option => $value) {
            curl_setopt($this->request, constant('CURLOPT_'.str_replace('CURLOPT_', '', strtoupper($option))), $value);
        }
    }

    function curl_request_async($url, $params, $type='GET')
    {
    	foreach ($params as $key => &$val) {
    		if (is_array($val)) $val = implode(',', $val);
    		$post_params[] = $key.'='.urlencode($val);
    	}
    	$post_string = implode('&', $post_params);
    
    	$parts=parse_url($url);
    
    	$fp = fsockopen($parts['host'],
    			isset($parts['port'])?$parts['port']:80,
    			$errno, $errstr, 30);
    
    	// Data goes in the path for a GET request
    	if('GET' == $type) $parts['path'] .= '?'.$post_string;
    
    	$out = "$type ".$parts['path']." HTTP/1.1\r\n";
    	$out.= "Host: ".$parts['host']."\r\n";
    	$out.= "Content-Type: application/x-www-form-urlencoded\r\n";
    	$out.= "Content-Length: ".strlen($post_string)."\r\n";
    	$out.= "Connection: Close\r\n\r\n";
    	// Data goes in the request body for a POST request
    	if ('POST' == $type && isset($post_string)) $out.= $post_string;
    
    	fwrite($fp, $out);
    	fclose($fp);
    }
}