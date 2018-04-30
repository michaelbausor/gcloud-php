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

namespace Google\Cloud\Speech\Tests\Snippets\V1;

use Google\ApiCore\BidiStream;
use Google\ApiCore\Call;
use Google\ApiCore\Testing\MockBidiStreamingCall;
use Google\ApiCore\Transport\TransportInterface;
use Google\Cloud\Core\Testing\Snippet\SnippetTestCase;
use Google\Cloud\Speech\V1\LongRunningRecognizeResponse;
use Google\Cloud\Speech\V1\RecognitionConfig;
use Google\Cloud\Speech\V1\RecognizeResponse;
use Google\Cloud\Speech\V1\SpeechClient;
use Google\Cloud\Speech\V1\StreamingRecognitionConfig;
use Google\Cloud\Speech\V1\StreamingRecognizeResponse;
use Google\LongRunning\Operation;
use Google\Protobuf\Any;
use GuzzleHttp\Promise\FulfilledPromise;
use Prophecy\Argument;

/**
 * @group speech
 */
class SpeechClientTest extends SnippetTestCase
{
    private $client;
    private $transport;

    public function setUp()
    {
        $this->transport = $this->prophesize(TransportInterface::class);
        $this->client = new SpeechClient([
            'transport' => $this->transport->reveal(),
        ]);
    }

    public function testRecognize()
    {
        $snippet = $this->snippetFromMethod(SpeechClient::class, 'recognize');
        $snippet->addLocal('speechClient', $this->client);

        $expectedResponse = new RecognizeResponse();

        $this->transport->startUnaryCall(Argument::allOf(
            Argument::type(Call::class),
            Argument::which('getMethod', 'google.cloud.speech.v1.Speech/Recognize')
        ),
            Argument::type('array')
        )
            ->shouldBeCalledTimes(1)
            ->willReturn(new FulfilledPromise($expectedResponse));

        $res = $snippet->invoke();
    }

    public function testLongRunningRecognize()
    {
        $snippet = $this->snippetFromMethod(SpeechClient::class, 'longRunningRecognize');
        $snippet->addLocal('speechClient', $this->client);

        $expectedRecognizeResponse = new LongRunningRecognizeResponse();
        $expectedResponse = new Operation();
        $expectedResponse->setResponse((new Any())->setValue($expectedRecognizeResponse->serializeToString()));
        $expectedResponse->setDone(true);

        $this->transport->startUnaryCall(Argument::allOf(
            Argument::type(Call::class),
            Argument::which('getMethod', 'google.cloud.speech.v1.Speech/LongRunningRecognize')
        ),
            Argument::type('array')
        )
            ->shouldBeCalledTimes(1)
            ->willReturn(new FulfilledPromise($expectedResponse));

        $res = $snippet->invoke();
    }

    public function testLongRunningRecognizeResume()
    {
        $snippet = $this->snippetFromMethod(
            SpeechClient::class,
            'longRunningRecognize',
            'resume'
        );
        $config = new RecognitionConfig();
        $audioUri = 'gs://bucket_name/file_name.flac';
        $snippet->addLocal('speechClient', $this->client);
        $snippet->addLocal('config', $config);
        $snippet->addLocal('audioUri', $audioUri);

        $expectedRecognizeResponse = new LongRunningRecognizeResponse();
        $expectedResponse = new Operation();
        $expectedResponse->setResponse((new Any())->setValue($expectedRecognizeResponse->serializeToString()));
        $expectedResponse->setDone(true);

        $this->transport->startUnaryCall(Argument::allOf(
            Argument::type(Call::class)
        ),
            Argument::type('array')
        )
            ->shouldBeCalledTimes(2)
            ->willReturn(new FulfilledPromise($expectedResponse));

        $res = $snippet->invoke();
    }

    public function testRecognizeAudioStream()
    {
        $snippet = $this->snippetFromMethod(SpeechClient::class, 'recognizeAudioStream');
        $snippet->addLocal('speechClient', $this->client);

        $snippet->replace(
            "path/to/audio.flac",
            "php://temp"
        );

        $expectedResponseStream = [
            new StreamingRecognizeResponse(),
            new StreamingRecognizeResponse(),
        ];

        $mockBidiStreamingCall = new MockBidiStreamingCall($expectedResponseStream);

        $this->transport->startBidiStreamingCall(Argument::allOf(
                Argument::type(Call::class),
                Argument::which('getMethod', 'google.cloud.speech.v1.Speech/StreamingRecognize')
            ),
                Argument::type('array')
            )
            ->shouldBeCalledTimes(1)
            ->willReturn(new BidiStream($mockBidiStreamingCall));

        $res = $snippet->invoke();
    }
}
