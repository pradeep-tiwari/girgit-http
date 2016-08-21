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

use BadMethodCallException;
use Girgit\Http\AbstractResponse;
use Girgit\Http\ExplainStatusCodes;
use Girgit\Http\BadPropertyCallException;

/**
 * This class provides an object oriented layer on top of traditional approaches to
 * dealing with HTTP response creation in PHP.
 *
 * Instead of trying to be too flexible by providing multiple ways for doing the same
 * thing, we will stick to few limited interfaces (aka public methods) to shorten the
 * learning curve and avoiding confusion.
 *
 * This class extends the core functionalities from Girgit\Http\AbstractResponse and
 * exposes following methods:
 *
 * Response   public   function   json(array $arr)
 * Response   public   function   html(string $html)
 * Response   public   function   code(int $code)
 * Response   public   function   message(string $message)
 * Response   public   function   headers(array $headers)
 * void       public   function   send(void)
 * void       public   function   explain(int $code)
 *
 * This class exposes following properties automagically:
 *
 * @property  string  'type'    The response content-type
 * @property  string  'body'    The response content
 * @property  int     'code'    The status code
 * @property  string  'message' The HTTP status message
 * @property  array   'headers' An array representing custom set headers
 */
class Response extends AbstractResponse
{
    /**
     * Class contructor
     *
     * It calls its parent constructor to make some intializations for the rest of
     * the class.
     *
     * @access public
     */
    public function __construct()
    {
        parent::__construct();
    }
    
    /**
     * Automagically calls public methods json(), html(), code(), message(), and
     * headers()
     *
     * @access  public
     * 
     * @param   string  $method  The name of the method set by PHP
     * @param   array   $args    An array representing passed argumenets set by PHP
     *
     * @throws  BadMethodCallException
     *
     * @return  Response
     */
    public function __call($method, $args)
    {
        $formattedMethod = '_set' . ucfirst($method);
        $args   = isset($args[0]) ? $args[0] : null;
        
        if(!method_exists(__CLASS__, $formattedMethod)) {
            throw new BadMethodCallException(sprintf("Response object has no such METHOD :: [[ %s ]]", $method));
        }
        
        call_user_func_array([$this, $formattedMethod], [$args]);

        return $this;
    }
    
    /**
     * Automagically calls public properties json, html, code, message, and headers.
     *
     * @access  public
     * 
     * @param   string  $property  The name of the property set by PHP
     * 
     * @throws  BadPropertyCallException
     *
     * @return  mixed
     */
    public function __get($property)
    {
        $formattedProperty = '_' . $property;
        
        if(!property_exists(__CLASS__, $formattedProperty)) {
            throw new BadPropertyCallException(sprintf("Response object has no such PROPERTY :: [[ %s ]]", $property));   
        }
        
        return $this->$formattedProperty;
    }
    
    /**
     * This method is called to finally send the response to the client. It delegates
     * this responsibility to two extended protected methods.
     *
     * @access  public
     *  
     * @return  void
     */
    public function send()
    {
        $this->_sendHeaders();
        $this->_sendContent();
    }
    
    /**
     * This method pretty prints the detailed information about most frequently used
     * HTTP status code. When no HTTP code is passed, it prints all status code and
     * their details. This method utilizes Girgit\Http\ExplainStatusCodes.
     *
     * @access  public
     *
     * @param  int  $code  The HTTP code.
     *
     * @return  void
     */
    public function explain($code = null)
    {
        ExplainStatusCodes::explain($code);
    }
}
