<?php
/*
 * Copyright 2018 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     https://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

/*
 * GENERATED CODE WARNING
 * This file was automatically generated - do not edit!
 */

namespace Google\Cloud\Tests\Unit\ErrorReporting\V1beta1;

use Google\ApiCore\Call;
use Google\ApiCore\Transport\TransportInterface;
use Google\Cloud\ErrorReporting\V1beta1\ErrorGroupServiceClient;
use Google\ApiCore\ApiException;
use Google\ApiCore\Testing\MockTransport;
use Google\Cloud\ErrorReporting\V1beta1\ErrorGroup;
use Google\Cloud\ErrorReporting\V1beta1\GetGroupRequest;
use Google\Cloud\ErrorReporting\V1beta1\UpdateGroupRequest;
use Grpc;
use GuzzleHttp\Promise\FulfilledPromise;
use GuzzleHttp\Promise\RejectedPromise;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use stdClass;

/**
 * @group error_reporting
 * @group grpc
 */
class ErrorGroupServiceClientTest extends TestCase
{
    public function testGetGroup()
    {
        $transport = $this->prophesize(TransportInterface::class);

        $client = new ErrorGroupServiceClient(['transport' => $transport->reveal()]);

        $formattedGroupName = $client->groupName('[PROJECT]', '[GROUP]');
        $expectedRequest = new GetGroupRequest();
        $expectedRequest->setGroupName($formattedGroupName);

        $expectedCall = new Call(
            'google.devtools.clouderrorreporting.v1beta1.ErrorGroupService/GetGroup',
            ErrorGroup::class,
            $expectedRequest
        );

        // Mock response
        $name = 'name3373707';
        $groupId = 'groupId506361563';
        $expectedResponse = new ErrorGroup();
        $expectedResponse->setName($name);
        $expectedResponse->setGroupId($groupId);

        $transport->startUnaryCall(
            $expectedCall,
            Argument::allOf(
                Argument::withEntry('timeoutMillis', Argument::type('int')),
                Argument::withEntry('headers', Argument::withKey('x-goog-api-client'))
            )
        )->willReturn(new FulfilledPromise($expectedResponse));

        $response = $client->getGroup($formattedGroupName);

        $this->assertEquals($expectedResponse, $response);
    }

    /**
     * @expectedException \Google\ApiCore\ApiException
     * @expectedExceptionMessageRegExp /{\s+"message":\s+"internal error",\s+"code": 15,\s+"status": "DATA_LOSS",\s+"details": \[\]\s+}/
     */
    public function testGetGroupException()
    {
        $transport = $this->prophesize(TransportInterface::class);

        $client = new ErrorGroupServiceClient(['transport' => $transport->reveal()]);

        $formattedGroupName = $client->groupName('[PROJECT]', '[GROUP]');
        $expectedRequest = new GetGroupRequest();
        $expectedRequest->setGroupName($formattedGroupName);

        $expectedCall = new Call(
            'google.devtools.clouderrorreporting.v1beta1.ErrorGroupService/GetGroup',
            ErrorGroup::class,
            $expectedRequest
        );

        $status = new stdClass();
        $status->code = Grpc\STATUS_DATA_LOSS;
        $status->details = 'internal error';

        $transport->startUnaryCall(
            $expectedCall,
            Argument::allOf(
                Argument::withEntry('timeoutMillis', Argument::type('int')),
                Argument::withEntry('headers', Argument::withKey('x-goog-api-client'))
            )
        )->willReturn(new RejectedPromise(ApiException::createFromStdClass($status)));

        $client->getGroup($formattedGroupName);
    }

    public function testUpdateGroup()
    {
        $transport = $this->prophesize(TransportInterface::class);

        $client = new ErrorGroupServiceClient(['transport' => $transport->reveal()]);

        $group = new ErrorGroup();
        $expectedRequest = new UpdateGroupRequest();
        $expectedRequest->setGroup($group);

        $expectedCall = new Call(
            'google.devtools.clouderrorreporting.v1beta1.ErrorGroupService/UpdateGroup',
            ErrorGroup::class,
            $expectedRequest
        );

        // Mock response
        $name = 'name3373707';
        $groupId = 'groupId506361563';
        $expectedResponse = new ErrorGroup();
        $expectedResponse->setName($name);
        $expectedResponse->setGroupId($groupId);

        $transport->startUnaryCall(
            $expectedCall,
            Argument::allOf(
                Argument::withEntry('timeoutMillis', Argument::type('int')),
                Argument::withEntry('headers', Argument::withKey('x-goog-api-client'))
            )
        )->willReturn(new FulfilledPromise($expectedResponse));

        $response = $client->updateGroup($group);

        $this->assertEquals($expectedResponse, $response);
    }

    /**
     * @expectedException \Google\ApiCore\ApiException
     * @expectedExceptionMessageRegExp /{\s+"message":\s+"internal error",\s+"code": 15,\s+"status": "DATA_LOSS",\s+"details": \[\]\s+}/
     */
    public function testUpdateGroupException()
    {
        $transport = $this->prophesize(TransportInterface::class);

        $client = new ErrorGroupServiceClient(['transport' => $transport->reveal()]);

        $group = new ErrorGroup();
        $expectedRequest = new UpdateGroupRequest();
        $expectedRequest->setGroup($group);

        $expectedCall = new Call(
            'google.devtools.clouderrorreporting.v1beta1.ErrorGroupService/UpdateGroup',
            ErrorGroup::class,
            $expectedRequest
        );

        $status = new stdClass();
        $status->code = Grpc\STATUS_DATA_LOSS;
        $status->details = 'internal error';

        $transport->startUnaryCall(
            $expectedCall,
            Argument::allOf(
                Argument::withEntry('timeoutMillis', Argument::type('int')),
                Argument::withEntry('headers', Argument::withKey('x-goog-api-client'))
            )
        )->willReturn(new RejectedPromise(ApiException::createFromStdClass($status)));

        $client->updateGroup($group);
    }
}
