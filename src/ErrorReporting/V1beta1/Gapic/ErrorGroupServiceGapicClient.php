<?php
/*
 * Copyright 2017, Google Inc. All rights reserved.
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

/*
 * GENERATED CODE WARNING
 * This file was generated from the file
 * https://github.com/google/googleapis/blob/master/google/devtools/clouderrorreporting/v1beta1/error_group_service.proto
 * and updates to that file get reflected here through a refresh process.
 *
 * EXPERIMENTAL: this client library class has not yet been declared beta. This class may change
 * more frequently than those which have been declared beta or 1.0, including changes which break
 * backwards compatibility.
 *
 * @experimental
 */

namespace Google\Cloud\ErrorReporting\V1beta1\Gapic;

use Google\Devtools\Clouderrorreporting\V1beta1\ErrorGroup;
use Google\Devtools\Clouderrorreporting\V1beta1\ErrorGroupServiceGrpcClient;
use Google\Devtools\Clouderrorreporting\V1beta1\GetGroupRequest;
use Google\Devtools\Clouderrorreporting\V1beta1\UpdateGroupRequest;
use Google\GAX\AgentHeaderDescriptor;
use Google\GAX\ApiCallable;
use Google\GAX\CallSettings;
use Google\GAX\GrpcCredentialsHelper;
use Google\GAX\PathTemplate;
use Google\GAX\ValidationException;

/**
 * Service Description: Service for retrieving and updating individual error groups.
 *
 * EXPERIMENTAL: this client library class has not yet been declared beta. This class may change
 * more frequently than those which have been declared beta or 1.0, including changes which break
 * backwards compatibility.
 *
 * This class provides the ability to make remote calls to the backing service through method
 * calls that map to API methods. Sample code to get started:
 *
 * ```
 * try {
 *     $errorGroupServiceClient = new ErrorGroupServiceClient();
 *     $formattedGroupName = $errorGroupServiceClient->groupName("[PROJECT]", "[GROUP]");
 *     $response = $errorGroupServiceClient->getGroup($formattedGroupName);
 * } finally {
 *     $errorGroupServiceClient->close();
 * }
 * ```
 *
 * Many parameters require resource names to be formatted in a particular way. To assist
 * with these names, this class includes a format method for each type of name, and additionally
 * a parseName method to extract the individual identifiers contained within formatted names
 * that are returned by the API.
 *
 * @experimental
 */
class ErrorGroupServiceGapicClient
{
    /**
     * The default address of the service.
     */
    const SERVICE_ADDRESS = 'clouderrorreporting.googleapis.com';

    /**
     * The default port of the service.
     */
    const DEFAULT_SERVICE_PORT = 443;

    /**
     * The default timeout for non-retrying methods.
     */
    const DEFAULT_TIMEOUT_MILLIS = 30000;

    /**
     * The name of the code generator, to be included in the agent header.
     */
    const CODEGEN_NAME = 'gapic';

    /**
     * The code generator version, to be included in the agent header.
     */
    const CODEGEN_VERSION = '0.0.5';

    private static $groupNameTemplate;
    private static $pathTemplateList = null;
    private static $gapicVersion = null;
    private static $gapicVersionLoaded = false;

    protected $grpcCredentialsHelper;
    protected $errorGroupServiceStub;
    private $scopes;
    private $defaultCallSettings;
    private $descriptors;

    private static function getGroupNameTemplate()
    {
        if (self::$groupNameTemplate == null) {
            self::$groupNameTemplate = new PathTemplate('projects/{project}/groups/{group}');
        }

        return self::$groupNameTemplate;
    }
    private static function getPathTemplateList()
    {
        if (self::$pathTemplateList == null) {
            self::$pathTemplateList = [
                self::getGroupNameTemplate(),
            ];
        }

        return self::$pathTemplateList;
    }

    private static function getGapicVersion()
    {
        if (!self::$gapicVersionLoaded) {
            if (file_exists(__DIR__.'/../VERSION')) {
                self::$gapicVersion = trim(file_get_contents(__DIR__.'/../VERSION'));
            } elseif (class_exists('\Google\Cloud\Version')) {
                self::$gapicVersion = \Google\Cloud\Version::VERSION;
            }
            self::$gapicVersionLoaded = true;
        }

        return self::$gapicVersion;
    }

    /**
     * Formats a string containing the fully-qualified path to represent
     * a group resource.
     *
     * @param string $project
     * @param string $group
     *
     * @return string The formatted group resource.
     * @experimental
     */
    public static function groupName($project, $group)
    {
        return self::getGroupNameTemplate()->render([
            'project' => $project,
            'group' => $group,
        ]);
    }

    /**
     * Parses a formatted name string and returns an associative array of the components in the name.
     * The following name formats are supported:
     * - projects/{project}/groups/{group}.
     *
     * @param string $formattedName The formatted name string
     *
     * @return array An associative array from name component IDs to component values.
     * @experimental
     */
    public static function parseName($formattedName)
    {
        foreach (self::getPathTemplateList() as $pathTemplate) {
            try {
                return $pathTemplate->match($formattedName);
            } catch (ValidationException $ex) {
                // Swallow the exception to continue trying other path templates
            }
        }
        throw new ValidationException("Input did not match any known format. Input: $formattedName");
    }

    /**
     * Constructor.
     *
     * @param array $options {
     *                       Optional. Options for configuring the service API wrapper.
     *
     *     @type string $serviceAddress The domain name of the API remote host.
     *                                  Default 'clouderrorreporting.googleapis.com'.
     *     @type mixed $port The port on which to connect to the remote host. Default 443.
     *     @type \Grpc\Channel $channel
     *           A `Channel` object to be used by gRPC. If not specified, a channel will be constructed.
     *     @type \Grpc\ChannelCredentials $sslCreds
     *           A `ChannelCredentials` object for use with an SSL-enabled channel.
     *           Default: a credentials object returned from
     *           \Grpc\ChannelCredentials::createSsl()
     *           NOTE: if the $channel optional argument is specified, then this argument is unused.
     *     @type bool $forceNewChannel
     *           If true, this forces gRPC to create a new channel instead of using a persistent channel.
     *           Defaults to false.
     *           NOTE: if the $channel optional argument is specified, then this option is unused.
     *     @type \Google\Auth\CredentialsLoader $credentialsLoader
     *           A CredentialsLoader object created using the Google\Auth library.
     *     @type array $scopes A string array of scopes to use when acquiring credentials.
     *                          Defaults to the scopes for the Stackdriver Error Reporting API.
     *     @type string $clientConfigPath
     *           Path to a JSON file containing client method configuration, including retry settings.
     *           Specify this setting to specify the retry behavior of all methods on the client.
     *           By default this settings points to the default client config file, which is provided
     *           in the resources folder.
     *     @type array $retryingOverride
     *           An associative array in which the keys are method names (e.g. 'createFoo'), and
     *           the values are retry settings to use for that method. The retry settings for each
     *           method can be a {@see Google\GAX\RetrySettings} object, or an associative array
     *           of retry settings parameters. See the documentation on {@see Google\GAX\RetrySettings}
     *           for example usage. Passing a value of null is equivalent to a value of
     *           ['retriesEnabled' => false].
     * }
     * @experimental
     */
    public function __construct($options = [])
    {
        $defaultOptions = [
            'serviceAddress' => self::SERVICE_ADDRESS,
            'port' => self::DEFAULT_SERVICE_PORT,
            'scopes' => [
                'https://www.googleapis.com/auth/cloud-platform',
            ],
            'retryingOverride' => null,
            'timeoutMillis' => self::DEFAULT_TIMEOUT_MILLIS,
            'libName' => null,
            'libVersion' => null,
            'clientConfigPath' => __DIR__.'/../resources/error_group_service_client_config.json',
        ];
        $options = array_merge($defaultOptions, $options);

        $gapicVersion = $options['libVersion'] ?: self::getGapicVersion();

        $headerDescriptor = new AgentHeaderDescriptor([
            'libName' => $options['libName'],
            'libVersion' => $options['libVersion'],
            'gapicVersion' => $gapicVersion,
        ]);

        $defaultDescriptors = ['headerDescriptor' => $headerDescriptor];
        $this->descriptors = [
            'getGroup' => $defaultDescriptors,
            'updateGroup' => $defaultDescriptors,
        ];

        $clientConfigJsonString = file_get_contents($options['clientConfigPath']);
        $clientConfig = json_decode($clientConfigJsonString, true);
        $this->defaultCallSettings =
                CallSettings::load(
                    'google.devtools.clouderrorreporting.v1beta1.ErrorGroupService',
                    $clientConfig,
                    $options['retryingOverride']
                );

        $this->scopes = $options['scopes'];

        $createStubOptions = [];
        if (array_key_exists('sslCreds', $options)) {
            $createStubOptions['sslCreds'] = $options['sslCreds'];
        }
        $this->grpcCredentialsHelper = new GrpcCredentialsHelper($options);

        $createErrorGroupServiceStubFunction = function ($hostname, $opts, $channel) {
            return new ErrorGroupServiceGrpcClient($hostname, $opts, $channel);
        };
        if (array_key_exists('createErrorGroupServiceStubFunction', $options)) {
            $createErrorGroupServiceStubFunction = $options['createErrorGroupServiceStubFunction'];
        }
        $this->errorGroupServiceStub = $this->grpcCredentialsHelper->createStub($createErrorGroupServiceStubFunction);
    }

    /**
     * Get the specified group.
     *
     * Sample code:
     * ```
     * try {
     *     $errorGroupServiceClient = new ErrorGroupServiceClient();
     *     $formattedGroupName = $errorGroupServiceClient->groupName("[PROJECT]", "[GROUP]");
     *     $response = $errorGroupServiceClient->getGroup($formattedGroupName);
     * } finally {
     *     $errorGroupServiceClient->close();
     * }
     * ```
     *
     * @param string $groupName [Required] The group resource name. Written as
     *                          <code>projects/<var>projectID</var>/groups/<var>group_name</var></code>.
     *                          Call
     *                          <a href="/error-reporting/reference/rest/v1beta1/projects.groupStats/list">
     *                          <code>groupStats.list</code></a> to return a list of groups belonging to
     *                          this project.
     *
     * Example: <code>projects/my-project-123/groups/my-group</code>
     * @param array $optionalArgs {
     *                            Optional.
     *
     *     @type \Google\GAX\RetrySettings|array $retrySettings
     *          Retry settings to use for this call. Can be a
     *          {@see Google\GAX\RetrySettings} object, or an associative array
     *          of retry settings parameters. See the documentation on
     *          {@see Google\GAX\RetrySettings} for example usage.
     * }
     *
     * @return \Google\Devtools\Clouderrorreporting\V1beta1\ErrorGroup
     *
     * @throws \Google\GAX\ApiException if the remote call fails
     * @experimental
     */
    public function getGroup($groupName, $optionalArgs = [])
    {
        $request = new GetGroupRequest();
        $request->setGroupName($groupName);

        $mergedSettings = $this->defaultCallSettings['getGroup']->merge(
            new CallSettings($optionalArgs)
        );
        $callable = ApiCallable::createApiCall(
            $this->errorGroupServiceStub,
            'GetGroup',
            $mergedSettings,
            $this->descriptors['getGroup']
        );

        return $callable(
            $request,
            [],
            ['call_credentials_callback' => $this->createCredentialsCallback()]);
    }

    /**
     * Replace the data for the specified group.
     * Fails if the group does not exist.
     *
     * Sample code:
     * ```
     * try {
     *     $errorGroupServiceClient = new ErrorGroupServiceClient();
     *     $group = new ErrorGroup();
     *     $response = $errorGroupServiceClient->updateGroup($group);
     * } finally {
     *     $errorGroupServiceClient->close();
     * }
     * ```
     *
     * @param ErrorGroup $group        [Required] The group which replaces the resource on the server.
     * @param array      $optionalArgs {
     *                                 Optional.
     *
     *     @type \Google\GAX\RetrySettings|array $retrySettings
     *          Retry settings to use for this call. Can be a
     *          {@see Google\GAX\RetrySettings} object, or an associative array
     *          of retry settings parameters. See the documentation on
     *          {@see Google\GAX\RetrySettings} for example usage.
     * }
     *
     * @return \Google\Devtools\Clouderrorreporting\V1beta1\ErrorGroup
     *
     * @throws \Google\GAX\ApiException if the remote call fails
     * @experimental
     */
    public function updateGroup($group, $optionalArgs = [])
    {
        $request = new UpdateGroupRequest();
        $request->setGroup($group);

        $mergedSettings = $this->defaultCallSettings['updateGroup']->merge(
            new CallSettings($optionalArgs)
        );
        $callable = ApiCallable::createApiCall(
            $this->errorGroupServiceStub,
            'UpdateGroup',
            $mergedSettings,
            $this->descriptors['updateGroup']
        );

        return $callable(
            $request,
            [],
            ['call_credentials_callback' => $this->createCredentialsCallback()]);
    }

    /**
     * Initiates an orderly shutdown in which preexisting calls continue but new
     * calls are immediately cancelled.
     *
     * @experimental
     */
    public function close()
    {
        $this->errorGroupServiceStub->close();
    }

    private function createCredentialsCallback()
    {
        return $this->grpcCredentialsHelper->createCallCredentialsCallback();
    }
}
