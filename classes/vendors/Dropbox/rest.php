<?php
/**
 * A simple JSON REST request abstraction layer that is used by the DropboxClient and DropboxSession modules.
 */
class DropboxRESTClient
{
    /**
     * Handle for the current cURL session
     * @var
     */
    private $curl = null;

    /**
     * Default cURL settings
     * @var
     */
    protected $curlDefaults = array(
        // BOOLEANS
        CURLOPT_AUTOREFERER    => true,     // Update referer on redirects
        CURLOPT_FAILONERROR    => false,    // Return false on HTTP code > 400
        CURLOPT_FOLLOWLOCATION => true,     // Follow redirects
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FRESH_CONNECT  => true,     // Don't use cached connection
        CURLOPT_FORBID_REUSE   => true,     // Close connection

        // INTEGERS
        CURLOPT_TIMEOUT        => 10,       // cURL timeout
        CURLOPT_CONNECTTIMEOUT => 10,       // Connection timeout

        // STRINGS
        CURLOPT_ENCODING       => "",       // "identity", "deflate", and "gzip"
        CURLOPT_USERAGENT      => "Dropbox REST PHP Client/1.0"
    );

    /**
     * Basic constructor
     *
     * Checks for cURL and initialize options
     * @return void
     */
    function __construct() {
        if (!function_exists("curl_init")) {
            throw new Exception("cURL is not installed on this system");
        }

        $this->curl = curl_init();
        if (!is_resource($this->curl) || !isset($this->curl)) {
            throw new Exception("Unable to create cURL session");
        }

        $success = curl_setopt_array( $this->curl, $this->curlDefaults );
        if ($success !== true) {
            throw new Exception("cURL Error: " . curl_error($this->curl));
        }
    }

    /**
     * Closes the current cURL connection
     */
    public function close() {
        @curl_close($this->curl);
    }

    function __destruct() {
        $this->close();
    }

    /**
     * Returns last error message
     * @return string  Error message
     */
    public function error() {
         return curl_error($this->curl);
    }

    /**
     * Returns last error code
     * @return int
     */
    public function errno() {
         return curl_errno($this->curl);
    } // end function

    public function get($url, $headers = array(), $raw = false) {
        return $this->request($url, "GET", $headers, null, null, $raw);
    }

    public function post($url, $params = array(), $headers = array(), $raw = false) {
        return $this->request($url, "POST", $headers, $params, null, $raw);
    }

    public function put($url, $body, $headers = array(), $raw = false) {
        return $this->request($url, "PUT", $headers, null, $body, $raw);
    }

    public function request($url, $method = "GET", $headers = array(), $params = array(), $body = null, $raw = false) {
        // Set the URL
        curl_setopt($this->curl, CURLOPT_URL, $url);

        // Set the method and related options
        switch ($method) {
            case "PUT":
                curl_setopt($this->curl, CURLOPT_PUT, true);

                //The file to PUT must be set with CURLOPT_INFILE and CURLOPT_INFILESIZE.
                if ($f = @fopen($body, "r")) {
                    curl_setopt($this->curl, CURLOPT_INFILE, $f);
                    curl_setopt($this->curl, CURLOPT_INFILESIZE, filesize($body));
                    $headers["Content-Length"] = filesize($body);
                }
                else {
                    throw new Exception("Unable to load resource '$body'");
                }
            break;

            case "POST":
                curl_setopt($this->curl, CURLOPT_POST, true);
            break;

            case "GET":
            default:
            break;
        }

        // Set the headers
        if (!empty($headers) && is_array($headers)) {
            // An array of HTTP header fields to set, in the format
            //array("Content-type: text/plain", "Content-length: 100")
            curl_setopt($this->curl, CURLOPT_HTTPHEADER, $headers);
        }

        if (!empty($params) && is_array($params)) {
            curl_setopt($this->curl, CURLOPT_POSTFIELDS, $params);
        }
		curl_setopt($this->curl, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, 1);
        // Retrieve HTTP response headers
        curl_setopt($this->curl, CURLOPT_HEADER, true);

        $response = curl_exec($this->curl);//echo"<pre>";print_r($response);die;
        $info = curl_getinfo($this->curl);

        // Close any open resource handle
        if (isset($f) && is_resource($f)) {
            @fclose($f);
        }
		
	/*	if($url = 'https://api.dropbox.com/1/search/dropbox/Apps/AccountEdge/Shijith/DesktopChanges?oauth_consumer_key=mr2d1ak16ac9ykh&oauth_token=0mptl2ccukfjue9&oauth_signature_method=HMAC-SHA1&oauth_version=1.0&oauth_timestamp=1361447803&oauth_nonce=7376d67197dca054a9c64b64eea1d22e&query=Handshake&file_limit=1000&include_deleted=0&oauth_signature=Rj%2FYs46qC8pj6TAaEyDIkT7XfQs%3D'){
			echo"<pre>";print_r($info);die;
		}*/
        
        $status = $info["http_code"];//echo $status;die;
        $header = substr($response, 0, $info["header_size"]);
        $body = substr( $response, $info["header_size"]);
		//echo "<pre>";print_r($body);die;
       if ($status > 400) {

            if ($raw === true) {
                $body = json_decode($body, true);
            }

            $error = (!empty($body['error'])) ? $body["error"] : "Unknown error";
            throw new Exception($error, $status);
        }

        // Parse response headers
        $response_headers = array();
        $lines = explode("\r\n", $header);
        array_shift($lines);
        foreach ($lines as $line) {
            // Skip empty lines
            if ("" == trim($line)) {
                continue;
            }
            @list($k, $v) = explode(": ", $line, 2);
            $response_headers[strtolower($k)] = $v;
        }

        return array("code" => $status, "body" => $body, "headers" => $response_headers);
    }
}
