<?php

/**
 * @package Girgit
 * @license MIT
 * @author Pradeep T. <pt21388@gmail.com>
 * @copyright Copyright (c) 2016, Pradeep T.
 *
 * This file is part of the Girgit package. For the full copyright and license
 * information, please view the LICENSE file that was distributed with this source
 * code.
 */

namespace Girgit\Http;

use InvalidArgumentException;

/**
 * The abstract class for Girgit\Http\Response.
 *
 * Method Prototypes:
 *
 * protected function __construct()
 * protected function _setJson($content)
 * protected function _setHtml($content)
 * protected function _setCode($code)
 * protected function _setHeaders($headers)
 * protected function _setMessage($message)
 * protected function _sendHeaders()
 * protected function _sendContent()
 */
class AbstractResponse
{
    /**
     * HTTP response codes and messages
     *
     * @var array
     */
    protected static $_statusMessages = [
        // Success 2xx
        200 => 'OK',                   
        201 => 'Created',              
        204 => 'No Content',
        
        // Failure 4xx
        400 => 'Bad Request',
        401 => 'Unauthorized',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        409 => 'Conflict',        
        
        // Server Error 5xx
        500 => 'Internal Server Error',
        503 => 'Service Unavailable'
    ];
    
    /**
     * Represents HTTP Content-Type
     *
     * @var string
     */
    protected $_type;
    
    /**
     * Represents HTTTP response body
     *
     * @var string
     */
    protected $_body;
    
    /**
     * Represents HTTP response status code
     *
     * @var int
     */
    protected $_code;
    
    /**
     * Represents HTTP response status message
     *
     * @var string
     */
    protected $_message;
    
    /**
     * Represents custom HTTP response headers provided by client
     */
    protected $_headers;
    
    /**
     * Class contructor
     *
     * Invoked by the extending class, it initializes few properties to their
     * defaults.
     *
     * @access  protected
     *
     * @return  void
     */
    protected function __construct()
    {
        $this->_type    = 'text/html';
        $this->_body    = '';
        $this->_code    = 200;
        $this->_message = 'OK';
        $this->_headers = [];
    }
    
    /**
     * This method sets the HTTP response content and type for JSON format.
     *
     * @access  protected
     *
     * @param  array  $content  The array to be encoded as JSON response content
     *
     * @throws  InvalidArgumentException
     *
     * @return  void
     */
    protected function _setJson($content)
    {
        if(!is_array($content)) {
            throw new InvalidArgumentException('Please provide an array for JSON response content');
        }
        
        $this->_type = 'application/json'; 
        $this->_body = json_encode($content);
    }
    
    /**
     * This method sets the HTTP response content and type for HTML format.
     *
     * @access  protected
     *
     * @param  string  $content  The HTML string
     *
     * @throws  InvalidArgumentException
     *
     * @return  void
     */
    protected function _setHtml($content)
    {
        if(!is_string($content) && !is_numeric($content)) {
            throw new InvalidArgumentException('Please provide a string for HTML response content');
        }
        
        $this->_type = 'text/html'; 
        $this->_body = $content;
    }
    
    /**
     * This method sets the HTTP response code and status message when the code is
     * provided by the client.
     *
     * @access  protected
     *
     * @param  int  $code  The HTTP response code
     *
     * @throws  InvalidArgumentException
     *
     * @return  void
     */
    protected function _setCode($code)
    {
        $code = (int) $code;

        if($code < 100 || $code >= 600) {
            throw new InvalidArgumentException(sprintf("Invalid HTTP status code: %s supplied", $code));
        }
        
        $this->_code = $code;
        $this->_message = isset(self::$_statusMessages[$code]) ? self::$_statusMessages[$code] : 'unknown status';
    }
    
    /**
     * This method sets the HTTP response headers supplied by the client.
     *
     * @access  protected
     *
     * @param  array  $headers  An array of $name => $value header pairs.
     *
     * @throws  InvalidArgumentException
     *
     * @return  void
     */
    protected function _setHeaders($headers)
    {
        print_r($headers);
        
        if(!is_array($headers)) {
            throw new InvalidArgumentException('Supplied headers must be an array of Name => Value pairs');
        }
        
        foreach($headers as $name => $value) {
            $this->_headers[(string) $name] = (string) $value;
        }
    }
    
    /**
     * This method sets the HTTP response message supplied by the client.
     *
     * @access  protected
     *
     * @param  string  $message  The HTTP response message
     *
     * @throws  InvalidArgumentException
     *
     * @return  void
     */
    protected function _setMessage($message)
    {
        $this->_message = (string) $message;
    }
    
    /**
     * This method sends all HTTP headers.
     *
     * @access  protected
     *
     * @return  void
     */
    protected function _sendHeaders()
    {
        header(sprintf("HTTP/1.1 %s %s", $this->_code, $this->_message));
        header(sprintf("Content-Type: %s; charset=UTF-8", $this->_type));
        
        foreach($this->_headers as $name => $value) {
            header(sprintf("%s: %s", $name, $value));
        }
    }
    
    /**
     * This method outputs the HTTP response body.
     *
     * @access  protected
     *
     * @return  void
     */
    protected function _sendContent()
    {
        echo $this->_body;
    }
}