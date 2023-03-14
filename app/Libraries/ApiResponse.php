<?php

namespace App\Libraries;

use Illuminate\Http\Response;

/**
 * Description constants of Api
 * @author bachtiarpanjaitan <bachtiarpanjaitan0@gmail.com>
 * @since 
 */
class ApiResponse {
    
    private static $_includeDatas = [];
    private static $_httpStatusCode = Response::HTTP_OK;
    private static $_httpMessage = null;
    private static $_appendMessage = null;
    private static $_appendMessageDir = 'last';
    private static $_withHttpMessage = true;
    
    /**
     * membuat array data untuk json respon
     * @author bachtiarpanjaitan <bachtiarpanjaitan0@gmail.com>
     * @since 
     * @modified 11 April 2021
     * 
     * @param boolean $status
     * @param string $message
     * @param mixed $data
     * @return array
     */
    public static function make(
        bool $status, 
        string $message = 'OK', 
        $data = null
    ): array {
        $customMessage = !empty(self::$_httpMessage) ? self::$_httpMessage : null;
        if (empty($customMessage)) {
            if (self::$_httpStatusCode !== Response::HTTP_OK) {
                
                if (self::$_withHttpMessage) {
                    $customMessage = Response::$statusTexts[self::$_httpStatusCode];
                
                    if (!empty(self::$_appendMessage)) {

                        //set direction
                        if (strtolower(self::$_appendMessageDir) === 'first') {
                            $httpMsg = Response::$statusTexts[self::$_httpStatusCode];
                            $customMessage = self::$_appendMessage . " {$httpMsg}";
                        } else {
                            $customMessage.= ". " . self::$_appendMessage;
                        }
                    }
                } else {
                    $customMessage = self::$_appendMessage;
                }
                
            }
        }
        
        $preResponse = [
            'status' => $status,
            'code' => self::$_httpStatusCode,
            'message' => !empty($customMessage) ? $customMessage : $message,
            'data' => $data
        ];
        if (self::$_includeDatas) {
            if (!is_null($data)) {
                $preResponse = array_merge($preResponse, [
                    'includes' => self::$_includeDatas
                ]);
            }
        }
        
        return $preResponse;
    }
    
    /**
     * jika anda punya data tambahan yang ingin disertakan pada respon json
     * @author bachtiarpanjaitan <bachtiarpanjaitan0@gmail.com>
     * @since 
     * @param array $data
     */
    public static function setIncludeData(array $data) {
        self::$_includeDatas = $data;
    }
    
    /**
     * set http status code
     * @param int $httpStatusCode
     */
    public static function setStatusCode(int $httpStatusCode, bool $withHttpMessage = true) {
        self::$_httpStatusCode = $httpStatusCode;
        self::$_withHttpMessage = $withHttpMessage;
    }
    
    /**
     * set http status message by http status code
     * @param string $httpMessage
     */
    public static function setMessage(string $httpMessage) {
        self::$_httpMessage = $httpMessage;
    }
    
    /**
     * set http status message by http status code
     * @param string $httpMessage
     */
    public static function setAppendMessage(string $appendMsg, string $direction = 'last') {
        self::$_appendMessage = $appendMsg;
    }
    
    /**
     * set http status message by http status code
     * @param string $httpMessage, 'first' or 'last'
     */
    public static function setAppendMessageDir(string $direction) {
        self::$_appendMessageDir = $direction;
    }
    
}
