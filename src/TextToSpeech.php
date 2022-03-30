<?php

declare(strict_types=1);

namespace GingTeam\FptAi;

use Symfony\Component\HttpClient\Exception\TransportException;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class TextToSpeech
{
    public const API_URL = 'https://api.fpt.ai/hmi/tts/v5';

    /** @var array<array-key, mixed> */
    private array $headers;

    private HttpClientInterface $client;

    /**
     * @param array{
     *  voice?: string,
     *  speed?: int,
     *  format?: string,
     *  callback_url?: string
     * } $options
     */
    public function __construct(string $apiKey, array $options = [])
    {
        $this->headers = ['api_key' => $apiKey] + $options;
        $this->client = HttpClient::create();
    }

    /**
     * @throws \InvalidArgumentException
     * @throws TransportException
     */
    public function speak(string $messages): string
    {
        $length = \strlen($messages);
        if ($length < 3 || $length > 5000) {
            throw new \InvalidArgumentException('The message must be between 3 and 5000 characters.');
        }

        $response = $this->client->request('POST', self::API_URL, [
            'headers' => $this->headers,
            'body' => $messages,
        ]);

        try {
            $statusCode = $response->getStatusCode();
            /** @var string[] $content */
            $content = $response->toArray(false);
        } catch (TransportExceptionInterface $e) {
            throw new TransportException('Could not reach the remote fpt.ai server.');
        }

        if (200 !== $statusCode) {
            throw new TransportException(sprintf('%s.', $content['message']));
        }

        return $content['async'];
    }
}
