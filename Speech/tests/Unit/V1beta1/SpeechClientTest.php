<?php
/**
 * Copyright 2018 Google Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace Google\Cloud\Speech\Tests\Unit\V1beta1;

use Google\ApiCore\BidiStream;
use Google\ApiCore\Call;
use Google\ApiCore\Testing\MockBidiStreamingCall;
use Google\ApiCore\Transport\TransportInterface;
use Google\Cloud\Speech\V1beta1\AsyncRecognizeRequest;
use Google\Cloud\Speech\V1beta1\AsyncRecognizeResponse;
use Google\Cloud\Speech\V1beta1\RecognitionAudio;
use Google\Cloud\Speech\V1beta1\RecognitionConfig;
use Google\Cloud\Speech\V1beta1\RecognitionConfig_AudioEncoding;
use Google\Cloud\Speech\V1beta1\SpeechClient;
use Google\Cloud\Speech\V1beta1\StreamingRecognitionConfig;
use Google\Cloud\Speech\V1beta1\StreamingRecognitionResult;
use Google\Cloud\Speech\V1beta1\StreamingRecognizeRequest;
use Google\Cloud\Speech\V1beta1\StreamingRecognizeResponse;
use Google\Cloud\Speech\V1beta1\SyncRecognizeRequest;
use Google\Cloud\Speech\V1beta1\SyncRecognizeResponse;
use Google\LongRunning\Operation;
use Google\Protobuf\Any;
use GuzzleHttp\Promise\FulfilledPromise;
use PhpParser\Node\Arg;
use Prophecy\Argument;
use PHPUnit\Framework\TestCase;

/**
 * @group speech
 */
class SpeechClientTest extends TestCase
{
    /** @var SpeechClient */
    private $client;
    /** @var TransportInterface */
    private $transport;

    public function setUp()
    {
        $this->transport = $this->prophesize(TransportInterface::class);
        $this->client = new SpeechClient([
            'transport' => $this->transport->reveal(),
        ]);
    }

    private function createRecognitionConfig()
    {
        $encoding = RecognitionConfig_AudioEncoding::FLAC;
        $sampleRateHertz = 44100;
        $languageCode = 'en-US';
        $recognitionConfig = new RecognitionConfig();
        $recognitionConfig->setEncoding($encoding);
        $recognitionConfig->setSampleRate($sampleRateHertz);
        $recognitionConfig->setLanguageCode($languageCode);
        return $recognitionConfig;
    }

    /**
     * @dataProvider recognizeDataProvider
     */
    public function testRecognize($audio, $expectedRequestMessage)
    {
        $recognitionConfig = $this->createRecognitionConfig();

        $expectedResponse = new SyncRecognizeResponse();

        $this->transport->startUnaryCall(Argument::allOf(
            Argument::type(Call::class),
            Argument::which('getMethod', 'google.cloud.speech.v1beta1.Speech/SyncRecognize'),
            Argument::which('getMessage', $expectedRequestMessage)
        ),
            Argument::any()
        )
            ->shouldBeCalledTimes(1)
            ->willReturn(new FulfilledPromise($expectedResponse));

        $response = $this->client->syncRecognize($recognitionConfig, $audio);

        $this->assertSame($expectedResponse, $response);
    }

    /**
     * @dataProvider asyncRecognizeDataProvider
     */
    public function testLongRunningRecognize($audio, $expectedRequestMessage)
    {
        $recognitionConfig = $this->createRecognitionConfig();

        $expectedRecognizeResponse = new AsyncRecognizeResponse();
        $expectedResponse = new Operation();
        $expectedResponse->setResponse((new Any())->setValue($expectedRecognizeResponse->serializeToString()));
        $expectedResponse->setDone(true);

        $this->transport->startUnaryCall(Argument::allOf(
            Argument::type(Call::class),
            Argument::which('getMethod', 'google.cloud.speech.v1beta1.Speech/AsyncRecognize'),
            Argument::which('getMessage', $expectedRequestMessage)
        ),
            Argument::any()
        )
            ->shouldBeCalledTimes(1)
            ->willReturn(new FulfilledPromise($expectedResponse));

        $response = $this->client->asyncRecognize($recognitionConfig, $audio);

        $this->assertEquals($expectedRecognizeResponse, $response->getResult());
    }

    public function recognizeDataProvider()
    {
        $uri = 'gs://my-bucket/my-audio.flac';
        $data = 'abcdefgh';
        $resourceData = 'zyxwvuts';
        $resource = $this->createResource($resourceData);
        $recognitionAudio = (new RecognitionAudio())
            ->setContent('directRequestData');
        return [
            [$uri, (new SyncRecognizeRequest())
                ->setConfig($this->createRecognitionConfig())
                ->setAudio((new RecognitionAudio())
                    ->setUri($uri))],
            [$data, (new SyncRecognizeRequest())
                ->setConfig($this->createRecognitionConfig())
                ->setAudio((new RecognitionAudio())
                    ->setContent($data))],
            [$resource, (new SyncRecognizeRequest())
                ->setConfig($this->createRecognitionConfig())
                ->setAudio((new RecognitionAudio())
                    ->setContent($resourceData))],
            [$recognitionAudio, (new SyncRecognizeRequest())
                ->setConfig($this->createRecognitionConfig())
                ->setAudio($recognitionAudio)]
        ];
    }

    public function asyncRecognizeDataProvider()
    {
        $uri = 'gs://my-bucket/my-audio.flac';
        $data = 'abcdefgh';
        $resourceData = 'zyxwvuts';
        $resource = $this->createResource($resourceData);
        $recognitionAudio = (new RecognitionAudio())
            ->setContent('directRequestData');
        return [
            [$uri, (new AsyncRecognizeRequest())
                ->setConfig($this->createRecognitionConfig())
                ->setAudio((new RecognitionAudio())
                    ->setUri($uri))],
            [$data, (new AsyncRecognizeRequest())
                ->setConfig($this->createRecognitionConfig())
                ->setAudio((new RecognitionAudio())
                    ->setContent($data))],
            [$resource, (new AsyncRecognizeRequest())
                ->setConfig($this->createRecognitionConfig())
                ->setAudio((new RecognitionAudio())
                    ->setContent($resourceData))],
            [$recognitionAudio, (new AsyncRecognizeRequest())
                ->setConfig($this->createRecognitionConfig())
                ->setAudio($recognitionAudio)]
        ];
    }

    /**
     * @dataProvider recognizeAudioStreamData
     */
    public function testRecognizeAudioStream($audio, $expectedContent)
    {
        $recognitionConfig = $this->createRecognitionConfig();
        $config = new StreamingRecognitionConfig();
        $config->setConfig($recognitionConfig);

        $expectedResponseStream = [
            new StreamingRecognizeResponse(),
            new StreamingRecognizeResponse(),
        ];

        $mockBidiStreamingCall = new MockBidiStreamingCall($expectedResponseStream);

        $this->transport->startBidiStreamingCall(Argument::allOf(
            Argument::type(Call::class),
            Argument::which('getMethod', 'google.cloud.speech.v1beta1.Speech/StreamingRecognize')
        ),
            Argument::type('array')
        )
            ->shouldBeCalledTimes(1)
            ->willReturn(new BidiStream($mockBidiStreamingCall));

        $bidiStream = $this->client->recognizeAudioStream($config);
        $bidiStream->writeAll($this->client->createStreamingRequests($audio));
        $responseStream = $bidiStream->closeWriteAndReadAll();

        $this->assertSame($expectedResponseStream, iterator_to_array($responseStream));

        /** @var StreamingRecognizeRequest[] $receivedCalls */
        $receivedCalls = $mockBidiStreamingCall->popReceivedCalls();

        $expectedConfigMessage = new StreamingRecognizeRequest();
        $expectedConfigMessage->setStreamingConfig($config);

        // Expect one extra call, for the config message
        $this->assertSame(count($expectedContent) + 1, count($receivedCalls));
        $this->assertEquals($expectedConfigMessage, $receivedCalls[0]);
        for ($i = 0; $i < count($expectedContent); $i++) {
            $this->assertSame($expectedContent[$i], $receivedCalls[$i + 1]->getAudioContent());
        }
    }

    public function recognizeAudioStreamData()
    {
        $data = 'abcdefgh';
        $iterableData = ['abcd', 'efgh'];
        $resourceData = 'zyxwvuts';
        $resource = $this->createResource($resourceData);
        return [
            [$data, [$data]],
            [$iterableData, $iterableData],
            [$resource, [$resourceData]]
        ];
    }

    private function createResource($data)
    {
        $resource = fopen('php://memory','r+');
        fwrite($resource, $data);
        rewind($resource);
        return $resource;
    }
}
