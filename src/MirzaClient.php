<?php
/**
 * Mirza Yandex Translator For Laravel
 * Mirza makes it easy to translate and manipulate text using the Yandex.Translate API.
 * 
 * @version 1.0.0
 * @author yak0d3 <contact.raedyak@gmail.com>
 * @license MIT
 * @link https://github.com/yak0d3/Mirza_Yandex_Translator
 * @copyright 2018 Yak0d3
 */

namespace yak0d3\mirza_yandex_translator;

use Exception;

class MirzaClient{
    /**
     * Yandex Translation API Key Variable
     * Publish the configuration using `php artisan vendor:publish`, 
     * then set the YANDEX_KEY environment variable (inside of the .env file) to your own Yandex.Translate API Key
     * 
     * @var string
     */
    private $key;
    /**
     * The list of supported languages variable
     *
     * @var string
     */
    public $supportedLanguages;
    public function __construct($key){
        $this->isValidKey($key);
        $this->key = $key;
        $this->supportedLanguages = $this->getLanguages(true);
    }
    /**
     * Validates if the API Key
     *
     * @param string $key
     * @return boolean
     * @throws Exception if the key is invalid
     */
    private function isValidKey($key){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,"https://translate.yandex.net/api/v1.5/tr.json/detect");
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS,"text=YTranslator&key=".$key);
        $response = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        $goodKey = false;
        $errorMsg = false;
        switch ($httpcode) {
            case 200:
                $goodKey = true;
                $errorMsg = false;
                break;
            case 401:
                $errorMsg = 'Invalid API key.';
                break;
            case 402:
                $errorMsg = 'Blocked API key.';
                break; 
            case 403:
                $errorMsg = 'Exceeded the daily limit on the amount of translated text.';
                break;
            
            default:
                $errorMsg = 'An unexpected error has occured while trying to verify the API Key.';
                break;
        }
        if(!$goodKey){
            throw new \Exception($errorMsg);
        }
        
    }
    /**
     * Translates a given text to a given language
     *
     * @param string $text
     * @param string $lang
     * @param string $format [plain|html]
     * @return string
     * @throws Exception if the string could not be translated
     */
    public function translate($text, $lang, $format = 'plain'){
            
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,"https://translate.yandex.net/api/v1.5/tr.json/translate");
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS,"text=".urlencode($text)."&lang=".$lang."&format=".$format."&key=".$this->key);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = json_decode(curl_exec($ch));
        curl_close($ch);
        if(array_key_exists('text',$response))
            return $response->text[0];
        else
            throw new Exception('This text could not be translated: the string you entered or the language code are maybe invalid. Run getSupportedLanguages() to get the list of supported languages.');
    }
    /**
     * Detects the language of a given text and returns the language code.
     *
     * @param string $text
     * @return string
     * @throws Exception if it couldn't detect the language
     */
    public function detectLanguage($text){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,"https://translate.yandex.net/api/v1.5/tr.json/detect");
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS,"text=".urlencode($text)."&key=".$this->key);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = json_decode(curl_exec($ch));
        curl_close($ch);
        
        if(array_key_exists("lang",$response) && $response->lang != null)
            return $response->lang;
        else
            throw new Exception('Could not get the language code: the entered string may not be valid.');
    }
    /**
     * Returns the list of supported languages
     * If `$codes` is set to true, only language code will be returned
     * 
     * @param boolean $codes
     * @return string
     * @throws Exception if an unknown error occures while trying to fetch the list of supported languages
     */
    public function getLanguages($codes = false){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,"https://translate.yandex.net/api/v1.5/tr.json/getLangs");
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS,"ui=en&key=".$this->key);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = json_decode(curl_exec($ch));
        curl_close($ch);
        if(array_key_exists("langs",$response))
            return $codes ? array_keys(json_decode(json_encode($response->langs),true)) : $response->langs;
        else
            throw new Exception('An unknown error has occured while trying to fetch the list of supported languages.');
    }

}
