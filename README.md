<meta property="og:image" content="https://i.imgur.com/DlgRhkN.png" /> 
<meta property="og:image:secure_url" content="https://i.imgur.com/DlgRhkN.png" />

![Mirza Yandex Translator Logo](https://i.imgur.com/DlgRhkN.png)

<p align="center">
	<a href="https://travis-ci.org/yak0d3/Mirza_Yandex_Translator"><img src="https://img.shields.io/travis/yak0d3/Mirza_Yandex_Translator.svg" alt="build"></a>
	<a href="https://github.com/yak0d3/Mirza_Yandex_Translator"><img src="https://img.shields.io/librariesio/github/yak0d3/Mirza_Yandex_Translator.svg" alt="Dependencies"></a>
	<a href="https://scrutinizer-ci.com/g/yak0d3/Mirza_Yandex_Translator/"><img src="https://img.shields.io/scrutinizer/g/yak0d3/Mirza_Yandex_Translator.svg" alt="Code Quality"></a>
	<a href="https://github.com/yak0d3/Mirza_Yandex_Translator/releases"><img src="https://img.shields.io/github/release/yak0d3/Mirza_Yandex_Translator.svg"></a>
	<a href="https://github.com/yak0d3/Mirza_Yandex_Translator/blob/master/LICENSE"><img src="https://poser.pugx.org/yak0d3/mirza_yandex_translator/license"></a>

</p>

 ## Mirza Yandex Translator For Laravel
>***Mirza Translator*** gives you the ability to easily translate and manipulate text using the Yandex.Translate API.


## Table Of Contents
1. <a href="#quick-start"> Quick Start</a>
2. <a href="#quick-usage-guide"> Quick Usage Guide</a>
3. <a href="#docs"> Documentation</a>
4. <a href="#methods"> Methods</a>
5. <a href="#blade-directives"> Blade Directives</a>
6. <a href="#IssuesAndSuggestions"> Issues & Suggestions</a>
7. <a href="#license"> License</a>

<div id="quick-start"><h2>Quick Start</h2></div>

Let's set this up real quick in *just* three mere steps!

 - Navigate to your Laravel installation folder via the terminal/cmd and run `composer require yak0d3/Mirza_Yandex_Translator` or add `"yak0d3/Mirza_Yandex_Translator": "^1.0.0"` manually to your `composer.json`.


 - Publish the configuration file using one of the following methods: 
	 1. Run `php artisan vendor:publish --provider="yak0d3\Mirza_Yandex_TranslatorT\MirzaServiceProvider"` 
	2. Run `php artisan vendor:publish` and type the number behind ``yak0d3\Mirza_Yandex_TranslatorT\MirzaServiceProvider`` then press `Enter`
 ![Publish configuration using php artisan vendor:publish](https://i.imgur.com/PgxsRjI.gif)
 
 - Add environment variable to your `.env` file with the name `YANDEX_API` and set its value to your own Yandex.Translate API Key. (***e.g.***  `YANDEX_API=MY_YANDEX_API_KEY`)
 
> ***Note***: *You can get your FREE API Key from the [Yandex.Translate Developers Dashboard](https://translate.yandex.com/developers/keys)*
<div id="quick-usage-guide"><h2> Quick Usage Guide </h2></div>

The *quick usage guide* is only meant to explain the basic usage of this package, for the list of methods and its relative information (Parameters, Return Type etc..) jump to the <a href="#">methods section</a> or jump to the <a href="#">directives sections</a> to view the list of available `blade` directives.
- #### Detect Language: <br> `Mirza::detectLanguage('Welcome');` <br> *Output:* `en`
- #### Translate text: <br>  `Mirza::translate('Hello','es');` <br> *Output:* `"Hola"`
 
- #### Translate to Multiple Languages: <br> `Mirza::translateTo('Hello World!',['es', 'tr', 'fr']')` <br> <br>*Output:* 
	```php
	{
	 "originalText": "Hello World!",
	 "originalLanguage": "en",
	 "text": {
	 "es": "Hola Mundo!",
	 "tr": "Merhaba D\u00fcnya!",
	 "fr": "Bonjour Tout Le Monde!"
	 }
	}
	```

> ***Note:*** *You can decode this string by using the [`json_decode`](http://https://php.net/manual/en/function.json-decode.php) function.*
 - #### Translate an Array of Text: <br> `$textArray = ['Hello','My Dear','Friend'];`<br> `Mirza::translateArray($textArray,'fr');` <br> *Output:* 
```php
[
 {
 "originalText": "Hello",
 "translatedText": "Bonjour"
 },
 {
 "originalText": "My dear",
 "translatedText": "Mon cher"
 },
 {
 "originalText": "Friend",
 "translatedText": "Ami"
 }
]
```
>***Note:*** *You can decode this string by using the [`json_decode`](http://https://php.net/manual/en/function.json-decode.php) function.*

Still not getting it? Take a look at the <a href="#docs">Documentation</a> below and the confusion will go away!

<div id="docs"><h2> Documentation <small> (With Examples)</small></h2></div>

Let's admin it, not everyone in here will find it easy to start using this package, so let's try to understand what's happening together.
This section will cover the usage of each and every method provided by *Mirza Yandex Translator*, here is the table of contents:

1.  <a href="#docs-translate">The `translate` method</a>
 2.  <a href="#docs-translateArray">The `translateArray` method</a>
	 1.  <a href="#docs-translateArray-sequential">Using sequential arrays</a>
	 2.  <a href="#docs-translateArray-assoc">Using associative arrays</a>
 3.  <a href="#docs-translateTo">The `translateTo` method</a>
 4.  <a href="#docs-detectLanguage">The `detectLanguage` method</a>
	 1.  <a href="#docs-detectLanguage-code">Return language code</a>
	 2.  <a href="#docs-detectLanguage-name">Return language name</a>
 5.  <a href="#docs-getSupportedLanguages">The `getSupportedLanguages` method</a>
 6.  <a href="#docs-translateToAll">The `translateToAll` method</a>
 7.  <a href="#docs-blade-directives">Blade directives</a>
	 1.  <a href="#docs-directives-translate">`@translate` directive</a>
	 2. <a href="#docs-directives-rights">`@yandex_rights` directive</a>
	 3. <a href="#docs-directives-select">`@languages_select` directive</a>
##
<div id="docs-translate"> 1. <strong>The <code>translate</code> method</strong></div>
As you have already expected, for sure there is a `translate` method for a translator package, this method takes two parameters; the text and the ISO code of the target language.

***Example:*** 
```php
	$es_translation = Mirza::translate('Hello World!', 'es); //The first param is the text, the second one is the ISO code of the language
	echo $es_translation; //This will output "Hola Mundo!"
```
	
<div id="docs-translateArray">2.   <strong>The <code>translateArray</code> method</strong></div>

>***Note*** that all `json` strings needs to be decoded using the PHP [`json_decode`](http://php.net/manual/en/function.json-decode.php) function.
>***Tip:*** To return a PHP array set the second argument of `json_decode` to `true` (*e.g.* `json_decode($jsonString, true);` ). <br>If you prefer manipulating `json objects`, leave the second argument empty or set it to `false`.
	
 <div id="docs-translateArray-sequential"></div>

`Mirza::translateArray(['Hello', 'My Dear', 'Friend'],'fr');` this method translates  a given array of text into which is in our case this array `['Hello', 'My Dear', 'Friend']` and translates it to a given language which is French in our example. <br> This function returns a `json encoded` string like the following:
```php
[
 {
 "originalText": "Hello",
 "translatedText": "Bonjour"
 },
 {
 "originalText": "My dear",
 "translatedText": "Mon cher"
 },
 {
 "originalText": "Friend",
 "translatedText": "Ami"
 }
] 
```
As you can see, the output `json string` is in the same order of the input array, now we can access each of these elements by decoding the string like so:
```php
	$jsonString = Mirza::translateArray(['Hello', 'My Dear', 'Friend'],'fr'); //The json string
	$translationsArray = json_decode($jsonString, true); //Our PHP Array
	$first_translation = $translationsArray[0]['translatedText'];
	$second_translation = $translationsArray[1]['translatedText'];
	$third_translation = $translationsArray[2]['translatedText'];
```
<div id="docs-translateArray-assoc"></div>

Easy, right? But it could get easier if you set the the $assoc parameter to true so you are able to access your string translations by their index names (that you have set manually). 
**No body** is getting confused in here, here is an example:
```php
$textArray = [
	'header' => "Welcome to the Mirza Documentation Page",
	'body' => "The body is too long to be put in this item",
	'footer' => "Thank you for reading this!"
]; //Our associative text array
$jsonString = Marzi::translate($textArray,'es', true); //Notice that i have set $assoc (third param) to `true`
$translationsArray = json_decode($jsonString, true);
//Now you can access the translations by their old index names
	$header = $translationsArray['header']['translatedText'];
	$body = $translationsArray['body']['translatedText'];
	$footer = $translationsArray['footer']['translatedText'];
```
> **Note:** If you set `$assoc` to `true` and provide a sequential array an exception will be thrown.

<div id="docs-translateTo">3. <strong>The <code>translateTo</code> method:</strong></div>

This method is (maybe) the reverse version of the previous function, instead of taking an `array` of strings, this method takes one `string` and translates it to an array of languages.
***Example:***
```php
	$jsonString = Mirza::translateTo('My awesome text', ['ar', 'tr', 'de']);
``` 
The above example will return `json string` with the following structure:
```php
[
	{
		"originalText":"My awesome text",
		"originalLanguage": "en",
		"text":{
			"ar":"\u0628\u0644\u062f\u064a \u0627\u0644\u0646\u0635 \u0631\u0647\u064a\u0628\u0629",
			"tr":"M\u00fcthi\u015f metin",
			"de":"Meine wunderbare text"
		}

	}
]
```
> You may have noticed that some of the characters are in Unicode format, no worries if you `echo` it later on it will be displayed correctly.

Now we can easily decode this `json string` and access our data like so:
```php
	$translations = json_decode($jsonString, true); //Our PHP array
	$originalText = $translations['originalText'];
	$originalLanguage = $translations['originalLanguage'];
	$ar_translation = $translations['text']['ar'];
	$tk_translation = $translations['text']['tr'];
	$de_translation = $translations['text']['de'];
```
<div id="docs-detectLanguage">4. <strong>The <code>detectLanguage</code> method</strong></div> 

 You sometimes need to detect in which language a text is written, the `detectLanguage` method is made just for this matter!
As mentioned in the <a href="#">methods table</a>, this method takes one required parameter and one optional.
The optional parameter (`boolean $name`) lets us switch between returning the language ISO code or the language name.

***Example:***
- <div id="docs-detectLanguage-code">Return language code:</div>
```php
//Leave the $name param empty or set it to `false`
//To return the language ISO code
$lang = Mirza::detectLanguage('Hello World!');
echo $lang; //Outputs "en"
```
- <div id="docs-detectLanguage-name">Return language name:</div>
```php
//Setthe $name param to `true`
//To return the language ISO code
$lang = Mirza::detectLanguage('Hello World!', true);
echo $lang; //Outputs "English"
```
<div id="docs-getSupportedLanguages">5. <strong>The <code>getSupportedLanguages</code> method</strong></div>

This method takes no parameters (it should, but that will be added in a later version) and if executed it returns the list of all the supported languages.

***Example:***
```php
//Save the json encoded string to the `$supportedLanguages` variable
$supportedLanguages = Mirza::getSupportedLanguages();
echo $supportedLanguages; 
/* Outputs the json string in the following format:
	[
		{ 'lang_code' => 'lang_name' },
		{ 'lang_code' => 'lang_name' },
	]
*/
```
I didn't want to include the whole output because it is so long, but if you are still curious about it, i was prepared for this! Here is a screenshot:
	![The list of supported Yandex.Translate languages](https://i.imgur.com/rPm9o6u.png)
	
Let's [decode](https://http://php.net/manual/en/function.json-decode.php) this `json string` and play a little bit!
```php
//Decode json string and wrap it into a PHP array
$langsArray = json_decode($supportedLanguages, true);
```
Let's say we have a language code, but we don't know to what language it refers, this line would help us a lot in such a case:
```php
echo $langsArray['tr']; //Outputs "Turkish"
```
Now supposing that we have a language name, but we doesn't know the ISO code, *EASY PEASY!* We can do it with the PHP [`array_flip`](http://php.net/manual/en/function.array-flip.php) function
```php
$flippedArray = array_flip($langsArray); 
/* The values are now keys! Cool right? */
$languageCode = $flippedArray['Sinhalese'];
echo $languageCode; //Outputs "si"
```
<div id="docs-translateToAll">6. <strong>The <code>translateToAll</code> method</strong></div>
I don't know what you might use this method for, but i thought it would be nice to include such a feature. As mentioned in the method name, this method translates a given string to all of the supported languages.

***Example:***
```php
//Save the json string to a variable
$myStringInAllLanguages = Mirza::translateToAll('My string');
echo $myStringInAllLanguages; 
/*Outputs a similar string to the `translateTo` method but 
with all supported languages*/
```
<div id="docs-blade-directives">7. <strong>Blade Directives</strong></div>

- <code id="docs-directives-translate">@translate</code>: Allows you to translate a given text to a given language on the go 

	 ***Example:***
	```html
	@translate('Welcome', 'fr') <!-- Outputs "Bienvenue" -->
	```

- <code id="docs-directives-rights">@yandex_rights</code>: If you have read the <a href="#" target="_blank">Yandex.Translate requirements for the use of translation results</a> you'd know that this directive will be very useful. <br> You have to specify the `color` as the first argument and the `font-size` as the second  one.

	***Example:***
	```html
		@yandex_rights('black', '16px');
		<!-- Output -->
		<a href='https://translate.yandex.com/' target='_blank' style='font-size:16px;color:black;'>Powered by Yandex.Translate</a>
	```
	- <code id="docs-directives-select">@languages_select</code>: Generates an HTML `<select>` with the list of all supported languages.
	
	***Example:***
	```html
	 @languages_select
	 <!-- Output -->
	 <select>
		<option value="lang_code">Lang_Name</option>
		<option value="lang_code">Lang_Name</option>
		<option value="lang_code">Lang_Name</option>
	</select>
	```
<div id="methods"><h2>Methods</h2></div>

Everything in **Mirza** is meant to be easy and readable, just by taking a look at the *source code* you will understand what's happening in no time. 
But don't worry, i have saved you the struggle and made a table containing the the list of methods that ***Mirza Translator*** provides.

|Method| Parameters | Returns | Throws | Description
|--|--|--|--|--|
|translate| `string $text`<br>`string $lang` <br> *Optional:* `string $format [html\|plain] (Default: "Plain")` | String | ***Exception:*** If text couldn't be translated. | Translates a given `$text` to a given `$lang` (language)
|translateTo| `string $text` <br> `array $langs` | String (json) | ***Exception:***  If one or more languages aren't supported. | Translate a given `$text` to multiple `$langs` (languages)
 |translateArray | `array $textArray` <br> `string $lang` <br> *Optional:* `bool $assoc (Default: false)` | String (json) | ***Exception:*** <br>1. If target language is not supported.<br>2. If `$assoc` is set to `true` and the given array is not associative. | Translates a `$textArray` (array of text) to a given `$lang` (language) <br> ***Note:*** If `$assoc` is set to `true`, the returned json string will have the same index names
 | detectLanguage | `string $text` <br> *Optional:* `bool $langName` | String  |***Exception:*** <br> 1. If language code is not found. <br> 2. If language name is not found | Detects the language of a given `$text` and returns the language code <br> ***Note:*** If `$langName` is set to `true`, the language full name will be returned instead.
 | getSupportedLanguages | *None* | String (json) | ***Exception:*** If an unknown error occurs while trying to fetch the list of supported functions | Returns a json string containing the list of all supported languages
 | translateToAll | `string $text` | String (json) | *None* |  Translates a string (`$text`) to all supported languages. <br> ***Note:*** This may take a while and cause a `PHP max_execution_time TIMEOUT Exception`
 | yandex_rights | *Optional:* `string $color (Default: #fff)` <br> `string $fontsize (Default: 14px) ` | String | *None* | Returns the string of the "Powered By Yandex.Translate" link string. Also called via `blade` directive <a href="#">`@yandex_rights`</a>. <br> ***Note:*** Please refer to [Yandex Translate: Requirements for the use of translation results](https://tech.yandex.com/translate/doc/dg/concepts/design-requirements-docpage/) to know more about font-size, color and placing requirements.
 | languages_select | *None* | String | *None* | Returns the string of an `HTML` ` <select>` tag with the list of all available languages. <br> Also called via `blade` directive <a href="#">`@languages_select` </a> 

<div id="blade-directives"><h2> Blade Directives</h2></div>

|Directive| Parameters  | Description
|--|--|--|
| `@yandex_rights` |  *Optional:* `string $color (Default: #fff)` <br> `string $fontsize (Default: 14px) `  | Generates an HTML link for the "Powered By Yandex.Translate" text. | 
| `@languages_select` | *None* | Generates an `HTML` ` <select>` tag with the list of all available languages.
| `@translate` | `string $text` <br> `string $lang` | Translate a given `$text` string to a given `$lang` (language)

<div id="IssuesAndSuggestions"><h2> Issues & Suggestions</h2></div>

***Mirza*** has been tested by only one person (obviously me 😃), which means that problems might occur with others, if something went wrong with your *Mirza* installation or you think something is still missing, please let me know by submitting a <a href="https://github.com/yak0d3/Mirza_Yandex_Translator/issues/new">new issue</a>.
<div id="license"><h2> License</h2></div>
<img src="https://i.imgur.com/NxptUsq.png" alt="Mirza Yandex Translator MIT License">

