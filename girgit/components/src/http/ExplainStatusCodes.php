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

/**
 * This class is a static wrapper for publishing details about few HTTP response
 * codes in a friendly format.
 */
class ExplainStatusCodes
{
    /**
     * Represents an array of HTTP response codes and their details.
     *
     * @var array
     */
    private static $_codeMaps = [
        200 => [
            'message' => 'HTTP_OK',
            'details' => 'General success status code. This is the most common code. Used to indicate success.'
        ],
        201 => [
            'message' => 'HTTP_CREATED',
            'details' => 'Successful creation occurred (via either POST or PUT). Set the Location header to contain a link to the newly-created resource (on POST). Response body content may or may not be present.'
        ],
        204 => [
            'message' => 'HTTP_NO_CONTENT',
            'details' => 'Indicates success but nothing is in the response body, often used for DELETE and PUT operations.'
        ],
        400 => [
            'message' => 'HTTP_BAD_REQUEST',
            'details' => 'General error for when fulfilling the request would cause an invalid state. Domain validation errors, missing data, etc. are some examples.'
        ],
        401 => [
            'message' => 'HTTP_UNAUTHORIZED',
            'details' => 'Error code response for missing or invalid authentication token.'
        ],
        403 => [
            'message' => 'HTTP_FORBIDDEN',
            'details' => 'Error code for when the user is not authorized to perform the operation or the resource is unavailable for some reason (e.g. time constraints, etc.).'
        ],
        404 => [
            'message' => 'HTTP_NOT_FOUND',
            'details' => 'Used when the requested resource is not found, whether it doesn\'t exist or if there was a 401 or 403 that, for security reasons, the service wants to mask.'
        ],
        405 => [
            'message' => 'HTTP_METHOD_NOT_ALLOWED',
            'details' => 'Used to indicate that the requested URL exists, but the requested HTTP method is not applicable. For example, POST /users/12345 where the API doesn\'t support creation of resources this way (with a provided ID). The Allow HTTP header must be set when returning a 405 to indicate the HTTP methods that are supported. In the previous case, the header would look like "Allow: GET, PUT, DELETE".'
        ],
        409 => [
            'message' => 'HTTP_CONFLICT',
            'details' => 'Whenever a resource conflict would be caused by fulfilling the request. Duplicate entries, such as trying to create two customers with the same information, and deleting root objects when cascade-delete is not supported are a couple of examples.'
        ],
        500 => [
            'message' => 'HTTP_INTERNAL_SERVER_ERROR',
            'details' => 'Never return this intentionally. The general catch-all error when the server-side throws an exception. Use this only for errors that the consumer cannot address from their end.'
        ],
        503 => [
            'message' => 'HTTP_SERVICE_UNAVAILABLE',
            'details' => 'Usually a temporary error message shown by an API.'
        ],
    ];
    
    /**
     * Call this static method to pretty print the details of an HTTP code. If code
     * is not provided, it publishes the complete details for all defined codes.
     *
     * @access  public
     *
     * @param  int  $code  The HTTP response code
     *
     * @return  void
     */
    public static function explain($code = null)
    {
        if($code !== null) {
            self::_publishOne((int) $code);
        } else {
            self::_publishAll();
        }
    }
    
    /**
     * Internal helper, called when details of a single HTTP code is required.
     *
     * @access private
     *
     * @return void
     */
    private static function _publishOne($code)
    {
        if(!isset(self::$_codeMaps[$code])) {
            self::_formatInfo('Sorry, we have no explanation available for the request code!!', true);
            return;
        }
        
        self::_formatInfo(print_r(self::$_codeMaps[$code], 1));
    }
    
    /**
     * Internal helper, called when details of a all defined HTTP codes is required.
     *
     * @access private
     *
     * @return void
     */
    private static function _publishAll()
    {
        self::_formatInfo(print_r(self::$_codeMaps, 1));
    }
    
    /**
     * Internal helper, that when called upon, pretty prints the information text.
     *
     * @access  private
     *
     * @param  string   $text     The text to pretty print.
     * @param  boolean  $isError  Whether the asked HTTP code is defined, if not, pass true.
     *
     * @return void
     */
    private static function _formatInfo($text, $isError = false)
    {
        $html = '';
        
        if($isError === true) {
            $style = "background: wheat; border: 2px solid #cd5c5c";
        } else {
            $style = "background: #000; color: #eee; border: 2px solid #09f";
        }
        
        $html .= '<pre style="padding: 1rem; max-width: 750px; margin: 50px auto; border-radius: 3px; overflow-x: scroll; ' . $style . '">';
        $html .= $text;
        $html .= '</pre>';
        
        echo $html;
    }
}