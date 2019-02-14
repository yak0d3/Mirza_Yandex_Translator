<?php

namespace yak0d3\Mirza;

use Exception;

class MirzaClient
{
    /**
     * Yandex.Translate API Key
     *
     * @var string
     */
    private $key;

    /**
     * The list of supported languages variable.
     *
     * @var string
     */
    public $supportedLanguages;

    /**
     * Create a new MirzaClient instance
     *
     * @param string $key
     */
    public function __construct(string $key)
    {
        $this->validateApiKey($key);
        $this->key = $key;
        $this->supportedLanguages = $this->getLanguages(true);
    }

    /**
     * Validates if the API Key.
     *
     * @param string $key
     *
     * @throws Exception
     *
     * @return boolean
     */
    private function validateApiKey(string $key)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://translate.yandex.net/api/v1.5/tr.json/detect');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, 'text=YTranslator&key='.$key);
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
        if (!$goodKey) {
            throw new \Exception($errorMsg);
        }

        return true;
    }

    /**
     * Translates a given text to a given language.
     *
     * @param string $text
     * @param string $lang
     * @param string $format
     *
     * @throws Exception
     *
     * @return string
     */
    public function translate(string $text, string $lang, string $format = 'plain')
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://translate.yandex.net/api/v1.5/tr.json/translate');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, 'text='.urlencode($text).'&lang='.$lang.'&format='.$format.'&key='.$this->key);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = json_decode(curl_exec($ch));
        curl_close($ch);
        if (array_key_exists('text', $response)) {
            return $response->text[0];
        } else {
            throw new Exception('This text could not be translated: the string you entered or the language code are maybe invalid. Run getSupportedLanguages() to get the list of supported languages.');
        }
    }

    /**
     * Detects the language of a given text and returns the language code.
     *
     * @param string $text
     *
     * @throws Exception
     *
     * @return string
     */
    public function detectLanguage(string $text)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://translate.yandex.net/api/v1.5/tr.json/detect');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, 'text=' . urlencode($text) . '&key=' . $this->key);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = json_decode(curl_exec($ch));
        curl_close($ch);

        if (array_key_exists('lang', $response) && $response->lang != null) {
            return $response->lang;
        } else {
            throw new Exception('Could not get the language code: the entered string may not be valid.');
        }
    }

    /**
     * Returns the list of supported languages
     * If `$codes` is set to true, only the
     * language code will be returned.
     *
     * @param bool $codes
     *
     * @throws Exception
     *
     * @return string
     */
    public function getLanguages(bool $codes = false)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://translate.yandex.net/api/v1.5/tr.json/getLangs');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, 'ui=en&key='.$this->key);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = json_decode(curl_exec($ch));
        curl_close($ch);

        if (array_key_exists('langs', $response)) {
            return $codes ? array_keys(json_decode(json_encode($response->langs), true)) : $response->langs;
        } else {
            throw new Exception('An unknown error has occured while trying to fetch the list of supported languages.');
        }
    }
}
