<?php

namespace yak0d3\Mirza\Tests;

use Exception;
use PHPUnit\Framework\TestCase as TestCase;
use yak0d3\Mirza\Mirza as Mirza;
use yak0d3\Mirza\MirzaClient as MirzaClient;

class MirzaTest extends TestCase
{
    protected $mirzaClient;
    protected $mirza;

    public function setUp()
    {
        parent::setUp();
        $this->mirzaClient = $this->getMockBuilder(MirzaClient::class)
                            ->disableOriginalConstructor()
                            ->disableArgumentCloning()
                            ->disallowMockingUnknownTypes()
                            ->getMock();
        $this->mirzaClient->supportedLanguages = [
            'ar',
            'en',
            'fr',
            'de',
            'es',
            'tk',
        ];
        $this->mirza = new Mirza($this->mirzaClient);
    }

    /** @test */
    public function check_if_translate_method_is_working()
    {
        $this->mirzaClient->expects($this->once())
                          ->method('translate')
                          ->willReturn('Hola');
        $response = $this->mirza->translate('Hello', 'es');
        $this->assertInternalType('string', $response);
        $this->assertEquals('Hola', $response);
    }

    /** @test */
    public function check_if_translate_array_of_text_method_is_working()
    {
        $this->mirzaClient->method('translate')
                          ->will($this->onConsecutiveCalls(
                                'Hola',
                                'Mundo'
                          ));
        $response = json_decode($this->mirza->translateArray(['Hello', 'World'], 'es'), true);
        $this->assertInternalType('array', $response);
        $this->assertArrayHasKey('originalText', $response[0]);
        $this->assertArrayHasKey('originalText', $response[1]);
        $this->assertArrayHasKey('translatedText', $response[0]);
        $this->assertArrayHasKey('translatedText', $response[1]);
        $this->assertEquals('Hello', $response[0]['originalText']);
        $this->assertEquals('Hola', $response[0]['translatedText']);
        $this->assertEquals('World', $response[1]['originalText']);
        $this->assertEquals('Mundo', $response[1]['translatedText']);
    }

    /** @test */
    public function check_if_translate_array_of_text_and_return_assoc_method_is_working()
    {
        $this->mirzaClient->method('translate')
                          ->will($this->onConsecutiveCalls(
                                'Hola',
                                'Mundo'
                          ));
        $response = json_decode($this->mirza->translateArray(['text1' => 'Hello', 'text2' => 'World'], 'es'), true);
        $this->assertInternalType('array', $response);
        $this->assertArrayHasKey('text1', $response);
        $this->assertArrayHasKey('text2', $response);
        $this->assertArrayHasKey('originalText', $response['text1']);
        $this->assertArrayHasKey('originalText', $response['text1']);
        $this->assertArrayHasKey('translatedText', $response['text2']);
        $this->assertArrayHasKey('translatedText', $response['text2']);
        $this->assertEquals('Hello', $response['text1']['originalText']);
        $this->assertEquals('Hola', $response['text1']['translatedText']);
        $this->assertEquals('World', $response['text2']['originalText']);
        $this->assertEquals('Mundo', $response['text2']['translatedText']);
    }

    /** @test */
    public function check_if_translate_to_multiple_languages_method_is_working()
    {
        $this->mirzaClient->method('translate')
                         ->will($this->onConsecutiveCalls(
                                'Hello',
                                'Hola',
                                'Salut',
                                'Hi'
                         ));
        $response = json_decode($this->mirza->translateTo('Merhaba', ['en', 'es', 'fr', 'de']), true);
        $this->assertInternalType('array', $response);
        $this->assertArrayHasKey('originalText', $response);
        $this->assertArrayHasKey('originalLanguage', $response);
        $this->assertArrayHasKey('text', $response);
        $this->assertArrayHasKey('en', $response['text']);
        $this->assertArrayHasKey('es', $response['text']);
        $this->assertArrayHasKey('fr', $response['text']);
        $this->assertArrayHasKey('de', $response['text']);
        $this->assertEquals('Hello', $response['text']['en']);
        $this->assertEquals('Hola', $response['text']['es']);
        $this->assertEquals('Salut', $response['text']['fr']);
        $this->assertEquals('Hi', $response['text']['de']);
    }

    /** @test */
    public function check_if_language_detection_method_returns_language_code()
    {
        $this->mirzaClient->expects($this->once())
                          ->method('detectLanguage')
                          ->willReturn('en');
        $response = $this->mirza->detectLanguage('Test');

        $this->assertEquals('en', $response);
    }

    /** @test */
    public function check_if_language_detection_method_returns_language_name()
    {
        $this->mirzaClient->expects($this->once())
                          ->method('getLanguages')
                          ->willReturn(json_decode(
                            '{"ar" : "Arabic", "en" : "English", "fr" : "French","de" : "Deutsch","es" : "Spanish","tk" : "Turkish"}'
                        ));
        $this->mirzaClient->expects($this->once())
                          ->method('detectLanguage')
                          ->willReturn('en');
        $response = $this->mirza->detectLanguage('Test', true);

        $this->assertEquals('English', $response);
    }

    /** @test */
    public function check_if_the_getSupportedLanguages_method_is_working()
    {
        $this->mirzaClient->expects($this->once())
        ->method('getLanguages')
        ->willReturn(json_decode(
          '{"ar" : "Arabic", "en" : "English", "fr" : "French","de" : "Deutsch","es" : "Spanish","tk" : "Turkish"}'
      ));
        $response = json_decode($this->mirza->getSupportedLanguages(), true);
        $this->assertInternalType('array', $response);
        $this->assertArrayHasKey('ar', $response);
        $this->assertArrayHasKey('en', $response);
        $this->assertArrayHasKey('fr', $response);
        $this->assertArrayHasKey('de', $response);
        $this->assertArrayHasKey('es', $response);
        $this->assertArrayHasKey('tk', $response);
    }

    /** @test */
    public function check_if_the_translate_to_all_languages_method_is_working()
    {
        $this->mirzaClient->expects($this->once())
                          ->method('getLanguages')
                          ->willReturn([
                            'en',
                            'fr',
                            'de',
                            'es',
                        ]);

        $response = json_decode($this->mirza->translateToAll('Hello'), true);
        $this->assertInternalType('array', $response);
        $this->assertArrayHasKey('originalText', $response);
        $this->assertArrayHasKey('originalLanguage', $response);
        $this->assertArrayHasKey('text', $response);
        $this->assertArrayHasKey('fr', $response['text']);
        $this->assertArrayHasKey('de', $response['text']);
        $this->assertArrayHasKey('es', $response['text']);
    }

    /** @test */
    public function check_if_it_throws_an_exception_if_one_or_more_languages_are_invalid()
    {
        $this->expectException(Exception::class);
        $this->mirza->translateTo('Test String', ['invalid_lang', 'en', 'another_invalid_lang', 'fr', 'es']);
    }

    /** @test */
    public function check_if_it_throws_an_exception_if_the_array_is_not_associative()
    {
        $this->expectException(Exception::class);
        $this->mirza->translateArray(['Text1', 'Text2'], 'en', true);
    }

    /** @test */
    public function check_if_it_throws_an_exception_if_the_lang_name_could_not_be_fetched()
    {
        $this->mirzaClient->expects($this->once())
                          ->method('getLanguages')
                          ->willReturn([
                            'en',
                            'fr',
                            'de',
                            'es',
                        ]);
        $this->expectException(Exception::class);
        $this->mirza->detectLanguage(',', true);
    }
}
