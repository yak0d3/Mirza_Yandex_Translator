<?php

namespace yak0d3\Mirza;

use Exception;
use yak0d3\Mirza\MirzaClient as MirzaClient;

class Mirza
{
    /**
     * The variable that will contain the MirzaClient instance.
     *
     * @var MirzaClient
     */
    protected $client;

    /**
     * Construct.
     *
     * @param MirzaClient $MirzaClient
     */
    public function __construct(MirzaClient $MirzaClient)
    {
        $this->client = $MirzaClient;
    }

    /**
     * Translates string to the given language.
     *
     * @param string $text
     * @param string $lang
     *
     * @return string
     */
    public function translate(string $text, string $lang)
    {
        return $this->client->translate($text, $lang);
    }

    /**
     * Translates an array of text to the given language
     * The array can be associative or sequential
     *  If the array is associative, an array with the same
     * index names will be returned in a json encoded string.
     *  If the $assoc param is set to `true` and the given array
     * is sequential an exception will be thrown.
     *
     * @param array  $textArray
     * @param string $lang
     * @param bool   $assoc
     *
     * @throws Exception if the target language is not supported
     *
     * @return string
     */
    public function translateArray(array $textArray, string $lang, $assoc = false)
    {
        if (!$this->isSupportedLang($lang)) {
            throw new Exception('The target language is not supported. Run getSupportedLanguages() to get the list of supported languages.');
        }
        $keys = array_keys($textArray);
        $translated = [];

        $index = 0;
        if ($assoc) {
            $this->isAssoc($textArray);
        }
        foreach ($textArray as $key => $text) {
            $translated[$key] = [
                'originalText'   => $text,
                'translatedText' => $this->client->translate($text, $lang),
            ];
            $index++;
        }

        return json_encode($translated);
    }

    /**
     * Translate a string to multiple languages.
     *
     * @param string $text
     * @param array  $langs
     *
     * @throws Exception if one or more target languages are not supported
     *
     * @return string
     */
    public function translateTo(string $text, array $langs)
    {
        $notSupported = [];
        foreach ($langs as $lang) {
            if (!$this->isSupportedLang($lang)) {
                array_push($notSupported, $lang);
            }
        }
        if (count($notSupported) > 0) {
            throw new Exception('The following languages are not supported: '.implode("\n", $notSupported));
        }
        $textLang = $this->detectLanguage($text);
        $translatedText = ['originalText' => $text, 'originalLanguage' => $textLang, 'text' => []];
        foreach ($langs as $lang) {
            try {
                $translatedText['text'][$lang] = $this->client->translate($text, $lang);
            } catch (Exception $e) {
                $translatedText['text'][$lang] = '';
            }
        }
        unset($translatedText['text'][$textLang]);

        return json_encode($translatedText);
    }

    /**
     * Detects the language of the provided string
     * An exception will be thrown if the language could not be found.
     *
     * @param string $text
     * @param bool   $langName
     *
     * @throws Exception if the language name is not found
     *
     * @return string
     */
    public function detectLanguage(string $text, $langName = false)
    {
        $langCode = $this->client->detectLanguage($text);
        if ($langName) {
            $allLanguages = $this->client->getLanguages();

            if (array_key_exists($langCode, $allLanguages)) {
                return $allLanguages->$langCode;
            } else {
                throw new Exception('Language name could not be found.');
            }
        } else {
            return $langCode;
        }
    }

    /**
     * Returns the list of all supported languages.
     *
     * @return string
     */
    public function getSupportedLanguages()
    {
        return json_encode($this->client->getLanguages());
    }

    /**
     * Translates a string to all supported languages
     * This may take some time and cause a timeout exception.
     *
     * @param string $text
     *
     * @return string
     */
    public function translateToAll(string $text)
    {
        $langs = $this->client->getLanguages(true);
        $textLang = $this->detectLanguage($text);
        $translatedText = ['originalText' => $text, 'originalLanguage' => $textLang, 'text' => []];
        unset($langs[array_search($textLang, $langs)]);

        foreach ($langs as $lang) {
            try {
                $translatedText['text'][$lang] = $this->client->translate($text, $lang);
            } catch (Exception $e) {
                $translatedText['text'][$lang] = '';
            }
        }

        return json_encode($translatedText);
    }

    /**
     * Generates the "Powered by Yandex.Translate" link.
     *
     * @param string $color
     * @param string $fontsize
     *
     * @return string
     */
    public function yandex_rights(string $color = '#fff', string $fontsize = '14px')
    {
        $copyrights = 'Powered by Yandex.Translate';

        return "<a href='https://translate.yandex.com/' target='_blank' style='font-size:$fontsize;color:$color;'>".$copyrights.'</a>';
    }

    /**
     * Generates an HTML `<select>` with the list of all supported languages.
     *
     * @return string
     */
    public function languages_select()
    {
        $select = '<select>';
        $option = '<option value="_lang_code_">_lang_name_</option>';
        foreach ($this->client->supportedLanguages as $langCode => $langName) {
            $optionTemp = str_replace('_lang_code_', $langCode, $option);
            $optionTemp = str_replace('_lang_name_', $langName, $optionTemp);
            $select .= $optionTemp;
        }
        $select .= '</select>';

        return $select;
    }

    /**
     * Checks if the array given is associative.
     *
     * @param array $array
     *
     * @throws Exception if the array given is not associative
     *
     * @return boolean
     */
    private function isAssoc(array $array)
    {
        if ([] == $array || array_keys($array) === range(0, count($array) - 1)) {
            throw new Exception('Argument 1 given to translateArray is a sequential array, an associative array is expected.');
        }

        return true;
    }

    /**
     * Checks if the language given is supported.
     *
     * @param string $lang
     *
     * @return boolean
     */
    public function isSupportedLang(string $lang)
    {
        if (!in_array($lang, json_decode(json_encode($this->client->supportedLanguages), true))) {
            return false;
        } else {
            return true;
        }
    }
}
