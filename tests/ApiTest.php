<?php

use GingTeam\FptAi\TextToSpeech;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\Exception\TransportException;

test('test #1', function () {
    $tts = new TextToSpeech('7O6g86hZ51Sy0nVhMVIaU8LyV5DDfOrh');

    /* @var TestCase $this */
    $this->assertIsString($tts->speak('Chào em, anh đứng đây từ chiều'));
});

it('throws exception #1', function () {
    $tts = new TextToSpeech('invalid');

    $tts->speak('Failed');
})->throws(TransportException::class, 'Invalid authentication credentials.');
