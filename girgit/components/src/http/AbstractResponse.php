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
 
class AbstractResponse
{
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
    protected $_type;
    protected $_body;
    protected $_code;
    protected $_message;
    protected $_headers;
    
    protected function __construct()
    {
        $this->_type    = 'text/html';
        $this->_body    = '';
        $this->_code    = 200;
        $this->_message = 'OK';
        $this->_headers = [];
    }
    
    protected function _setJson($content)
    {
        if(!is_array($content)) {
            throw new InvalidArgumentException('Please provide an array for JSON response content');
        }
        
        $this->_type = 'application/json'; 
        $this->_body = json_encode($content);
    }
    
    protected function _setHtml($content)
    {
        if(!is_string($content) && !is_numeric($content)) {
            throw new InvalidArgumentException('Please provide a string for HTML response content');
        }
        
        $this->_type = 'text/html'; 
        $this->_body = $content;
    }
    
    protected function _setCode($code)
    {
        $code = (int) $code;

        if($code < 100 || $code >= 600) {
            throw new InvalidArgumentException(sprintf("Invalid HTTP status code: %s supplied", $code));
        }
        
        $this->_code = $code;
        $this->_message = isset(self::$_statusMessages[$code]) ? self::$_statusMessages[$code] : 'unknown status';
    }
    
    protected function _setHeaders($headers)
    {
        if(!is_array($headers)) {
            throw new InvalidArgumentException('Supplied headers must be an array of Name => Value pairs');
        }
        
        foreach($headers as $name => $value) {
            $this->_headers[(string) $name] = (string) $value;
        }
    }
    
    protected function _setMessage($message)
    {
        $this->_message = (string) $message;
    }
    
    protected function _sendHeaders()
    {
        header(sprintf("HTTP/1.1 %s %s", $this->_code, $this->_message));
        header(sprintf("Content-Type: %s; charset=UTF-8", $this->_type));
        
        foreach($this->_headers as $name => $value) {
            header(sprintf("%s: %s", $name, $value));
        }
    }
    
    protected function _sendContent()
    {
        echo $this->_body;
    }
}