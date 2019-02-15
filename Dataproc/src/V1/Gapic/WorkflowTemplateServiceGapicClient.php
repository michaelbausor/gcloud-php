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
 * This file was generated from the file
 * https://github.com/google/googleapis/blob/master/google/cloud/dataproc/v1/workflow_templates.proto
 * and updates to that file get reflected here through a refresh process.
 *
 * @experimental
 */

namespace Google\Cloud\Dataproc\V1\Gapic;

use Google\ApiCore\ApiException;
use Google\ApiCore\CredentialsWrapper;
use Google\ApiCore\GapicClientTrait;
use Google\ApiCore\LongRunning\OperationsClient;
use Google\ApiCore\OperationResponse;
use Google\ApiCore\PathTemplate;
use Google\ApiCore\RetrySettings;
use Google\ApiCore\Transport\TransportInterface;
use Google\ApiCore\ValidationException;
use Google\Auth\FetchAuthTokenInterface;
use Google\Cloud\Dataproc\V1\CreateWorkflowTemplateRequest;
use Google\Cloud\Dataproc\V1\DeleteWorkflowTemplateRequest;
use Google\Cloud\Dataproc\V1\GetWorkflowTemplateRequest;
use Google\Cloud\Dataproc\V1\InstantiateInlineWorkflowTemplateRequest;
use Google\Cloud\Dataproc\V1\InstantiateWorkflowTemplateRequest;
use Google\Cloud\Dataproc\V1\ListWorkflowTemplatesRequest;
use Google\Cloud\Dataproc\V1\ListWorkflowTemplatesResponse;
use Google\Cloud\Dataproc\V1\UpdateWorkflowTemplateRequest;
use Google\Cloud\Dataproc\V1\WorkflowMetadata;
use Google\Cloud\Dataproc\V1\WorkflowTemplate;
use Google\LongRunning\Operation;
use Google\Protobuf\GPBEmpty;

/**
 * Service Description: The API interface for managing Workflow Templates in the
 * Cloud Dataproc API.
 *
 * This class provides the ability to make remote calls to the backing service through method
 * calls that map to API methods. Sample code to get started:
 *
 * ```
 * $workflowTemplateServiceClient = new WorkflowTemplateServiceClient();
 * try {
 *     $formattedParent = $workflowTemplateServiceClient->regionName('[PROJECT]', '[REGION]');
 *     $template = new WorkflowTemplate();
 *     $response = $workflowTemplateServiceClient->createWorkflowTemplate($formattedParent, $template);
 * } finally {
 *     $workflowTemplateServiceClient->close();
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
class WorkflowTemplateServiceGapicClient
{
    use GapicClientTrait;

    /**
     * The name of the service.
     */
    const SERVICE_NAME = 'google.cloud.dataproc.v1.WorkflowTemplateService';

    /**
     * The default address of the service.
     */
    const SERVICE_ADDRESS = 'dataproc.googleapis.com';

    /**
     * The default port of the service.
     */
    const DEFAULT_SERVICE_PORT = 443;

    /**
     * The name of the code generator, to be included in the agent header.
     */
    const CODEGEN_NAME = 'gapic';

    /**
     * The default scopes required by the service.
     */
    public static $serviceScopes = [
        'https://www.googleapis.com/auth/cloud-platform',
    ];
    private static $regionNameTemplate;
    private static $workflowTemplateNameTemplate;
    private static $pathTemplateMap;

    private $operationsClient;

    private static function getClientDefaults()
    {
        return [
            'serviceName' => self::SERVICE_NAME,
            'serviceAddress' => self::SERVICE_ADDRESS.':'.self::DEFAULT_SERVICE_PORT,
            'clientConfig' => __DIR__.'/../resources/workflow_template_service_client_config.json',
            'descriptorsConfigPath' => __DIR__.'/../resources/workflow_template_service_descriptor_config.php',
            'gcpApiConfigPath' => __DIR__.'/../resources/workflow_template_service_grpc_config.json',
            'credentialsConfig' => [
                'scopes' => self::$serviceScopes,
            ],
            'transportConfig' => [
                'rest' => [
                    'restClientConfigPath' => __DIR__.'/../resources/workflow_template_service_rest_client_config.php',
                ],
            ],
        ];
    }

    private static function getRegionNameTemplate()
    {
        if (null == self::$regionNameTemplate) {
            self::$regionNameTemplate = new PathTemplate('projects/{project}/regions/{region}');
        }

        return self::$regionNameTemplate;
    }

    private static function getWorkflowTemplateNameTemplate()
    {
        if (null == self::$workflowTemplateNameTemplate) {
            self::$workflowTemplateNameTemplate = new PathTemplate('projects/{project}/regions/{region}/workflowTemplates/{workflow_template}');
        }

        return self::$workflowTemplateNameTemplate;
    }

    private static function getPathTemplateMap()
    {
        if (null == self::$pathTemplateMap) {
            self::$pathTemplateMap = [
                'region' => self::getRegionNameTemplate(),
                'workflowTemplate' => self::getWorkflowTemplateNameTemplate(),
            ];
        }

        return self::$pathTemplateMap;
    }

    /**
     * Formats a string containing the fully-qualified path to represent
     * a region resource.
     *
     * @param string $project
     * @param string $region
     *
     * @return string The formatted region resource.
     * @experimental
     */
    public static function regionName($project, $region)
    {
        return self::getRegionNameTemplate()->render([
            'project' => $project,
            'region' => $region,
        ]);
    }

    /**
     * Formats a string containing the fully-qualified path to represent
     * a workflow_template resource.
     *
     * @param string $project
     * @param string $region
     * @param string $workflowTemplate
     *
     * @return string The formatted workflow_template resource.
     * @experimental
     */
    public static function workflowTemplateName($project, $region, $workflowTemplate)
    {
        return self::getWorkflowTemplateNameTemplate()->render([
            'project' => $project,
            'region' => $region,
            'workflow_template' => $workflowTemplate,
        ]);
    }

    /**
     * Parses a formatted name string and returns an associative array of the components in the name.
     * The following name formats are supported:
     * Template: Pattern
     * - region: projects/{project}/regions/{region}
     * - workflowTemplate: projects/{project}/regions/{region}/workflowTemplates/{workflow_template}.
     *
     * The optional $template argument can be supplied to specify a particular pattern, and must
     * match one of the templates listed above. If no $template argument is provided, or if the
     * $template argument does not match one of the templates listed, then parseName will check
     * each of the supported templates, and return the first match.
     *
     * @param string $formattedName The formatted name string
     * @param string $template      Optional name of template to match
     *
     * @return array An associative array from name component IDs to component values.
     *
     * @throws ValidationException If $formattedName could not be matched.
     * @experimental
     */
    public static function parseName($formattedName, $template = null)
    {
        $templateMap = self::getPathTemplateMap();

        if ($template) {
            if (!isset($templateMap[$template])) {
                throw new ValidationException("Template name $template does not exist");
            }

            return $templateMap[$template]->match($formattedName);
        }

        foreach ($templateMap as $templateName => $pathTemplate) {
            try {
                return $pathTemplate->match($formattedName);
            } catch (ValidationException $ex) {
                // Swallow the exception to continue trying other path templates
            }
        }
        throw new ValidationException("Input did not match any known format. Input: $formattedName");
    }

    /**
     * Return an OperationsClient object with the same endpoint as $this.
     *
     * @return OperationsClient
     * @experimental
     */
    public function getOperationsClient()
    {
        return $this->operationsClient;
    }

    /**
     * Resume an existing long running operation that was previously started
     * by a long running API method. If $methodName is not provided, or does
     * not match a long running API method, then the operation can still be
     * resumed, but the OperationResponse object will not deserialize the
     * final response.
     *
     * @param string $operationName The name of the long running operation
     * @param string $methodName    The name of the method used to start the operation
     *
     * @return OperationResponse
     * @experimental
     */
    public function resumeOperation($operationName, $methodName = null)
    {
        $options = isset($this->descriptors[$methodName]['longRunning'])
            ? $this->descriptors[$methodName]['longRunning']
            : [];
        $operation = new OperationResponse($operationName, $this->getOperationsClient(), $options);
        $operation->reload();

        return $operation;
    }

    /**
     * Constructor.
     *
     * @param array $options {
     *                       Optional. Options for configuring the service API wrapper.
     *
     *     @type string $serviceAddress
     *           The address of the API remote host. May optionally include the port, formatted
     *           as "<uri>:<port>". Default 'dataproc.googleapis.com:443'.
     *     @type string|array|FetchAuthTokenInterface|CredentialsWrapper $credentials
     *           The credentials to be used by the client to authorize API calls. This option
     *           accepts either a path to a credentials file, or a decoded credentials file as a
     *           PHP array.
     *           *Advanced usage*: In addition, this option can also accept a pre-constructed
     *           {@see \Google\Auth\FetchAuthTokenInterface} object or
     *           {@see \Google\ApiCore\CredentialsWrapper} object. Note that when one of these
     *           objects are provided, any settings in $credentialsConfig will be ignored.
     *     @type array $credentialsConfig
     *           Options used to configure credentials, including auth token caching, for the client.
     *           For a full list of supporting configuration options, see
     *           {@see \Google\ApiCore\CredentialsWrapper::build()}.
     *     @type bool $disableRetries
     *           Determines whether or not retries defined by the client configuration should be
     *           disabled. Defaults to `false`.
     *     @type string|array $clientConfig
     *           Client method configuration, including retry settings. This option can be either a
     *           path to a JSON file, or a PHP array containing the decoded JSON data.
     *           By default this settings points to the default client config file, which is provided
     *           in the resources folder.
     *     @type string|TransportInterface $transport
     *           The transport used for executing network requests. May be either the string `rest`
     *           or `grpc`. Defaults to `grpc` if gRPC support is detected on the system.
     *           *Advanced usage*: Additionally, it is possible to pass in an already instantiated
     *           {@see \Google\ApiCore\Transport\TransportInterface} object. Note that when this
     *           object is provided, any settings in $transportConfig, and any $serviceAddress
     *           setting, will be ignored.
     *     @type array $transportConfig
     *           Configuration options that will be used to construct the transport. Options for
     *           each supported transport type should be passed in a key for that transport. For
     *           example:
     *           $transportConfig = [
     *               'grpc' => [...],
     *               'rest' => [...]
     *           ];
     *           See the {@see \Google\ApiCore\Transport\GrpcTransport::build()} and
     *           {@see \Google\ApiCore\Transport\RestTransport::build()} methods for the
     *           supported options.
     * }
     *
     * @throws ValidationException
     * @experimental
     */
    public function __construct(array $options = [])
    {
        $clientOptions = $this->buildClientOptions($options);
        $this->setClientOptions($clientOptions);
        $this->operationsClient = $this->createOperationsClient($clientOptions);
    }

    /**
     * Creates new workflow template.
     *
     * Sample code:
     * ```
     * $workflowTemplateServiceClient = new WorkflowTemplateServiceClient();
     * try {
     *     $formattedParent = $workflowTemplateServiceClient->regionName('[PROJECT]', '[REGION]');
     *     $template = new WorkflowTemplate();
     *     $response = $workflowTemplateServiceClient->createWorkflowTemplate($formattedParent, $template);
     * } finally {
     *     $workflowTemplateServiceClient->close();
     * }
     * ```
     *
     * @param string           $parent       Required. The "resource name" of the region, as described
     *                                       in https://cloud.google.com/apis/design/resource_names of the form
     *                                       `projects/{project_id}/regions/{region}`
     * @param WorkflowTemplate $template     Required. The Dataproc workflow template to create.
     * @param array            $optionalArgs {
     *                                       Optional.
     *
     *     @type RetrySettings|array $retrySettings
     *          Retry settings to use for this call. Can be a
     *          {@see Google\ApiCore\RetrySettings} object, or an associative array
     *          of retry settings parameters. See the documentation on
     *          {@see Google\ApiCore\RetrySettings} for example usage.
     * }
     *
     * @return \Google\Cloud\Dataproc\V1\WorkflowTemplate
     *
     * @throws ApiException if the remote call fails
     * @experimental
     */
    public function createWorkflowTemplate($parent, $template, array $optionalArgs = [])
    {
        $request = new CreateWorkflowTemplateRequest();
        $request->setParent($parent);
        $request->setTemplate($template);

        return $this->startCall(
            'CreateWorkflowTemplate',
            WorkflowTemplate::class,
            $optionalArgs,
            $request
        )->wait();
    }

    /**
     * Retrieves the latest workflow template.
     *
     * Can retrieve previously instantiated template by specifying optional
     * version parameter.
     *
     * Sample code:
     * ```
     * $workflowTemplateServiceClient = new WorkflowTemplateServiceClient();
     * try {
     *     $formattedName = $workflowTemplateServiceClient->workflowTemplateName('[PROJECT]', '[REGION]', '[WORKFLOW_TEMPLATE]');
     *     $response = $workflowTemplateServiceClient->getWorkflowTemplate($formattedName);
     * } finally {
     *     $workflowTemplateServiceClient->close();
     * }
     * ```
     *
     * @param string $name         Required. The "resource name" of the workflow template, as described
     *                             in https://cloud.google.com/apis/design/resource_names of the form
     *                             `projects/{project_id}/regions/{region}/workflowTemplates/{template_id}`
     * @param array  $optionalArgs {
     *                             Optional.
     *
     *     @type int $version
     *          Optional. The version of workflow template to retrieve. Only previously
     *          instatiated versions can be retrieved.
     *
     *          If unspecified, retrieves the current version.
     *     @type RetrySettings|array $retrySettings
     *          Retry settings to use for this call. Can be a
     *          {@see Google\ApiCore\RetrySettings} object, or an associative array
     *          of retry settings parameters. See the documentation on
     *          {@see Google\ApiCore\RetrySettings} for example usage.
     * }
     *
     * @return \Google\Cloud\Dataproc\V1\WorkflowTemplate
     *
     * @throws ApiException if the remote call fails
     * @experimental
     */
    public function getWorkflowTemplate($name, array $optionalArgs = [])
    {
        $request = new GetWorkflowTemplateRequest();
        $request->setName($name);
        if (isset($optionalArgs['version'])) {
            $request->setVersion($optionalArgs['version']);
        }

        return $this->startCall(
            'GetWorkflowTemplate',
            WorkflowTemplate::class,
            $optionalArgs,
            $request
        )->wait();
    }

    /**
     * Instantiates a template and begins execution.
     *
     * The returned Operation can be used to track execution of
     * workflow by polling
     * [operations.get][google.longrunning.Operations.GetOperation].
     * The Operation will complete when entire workflow is finished.
     *
     * The running workflow can be aborted via
     * [operations.cancel][google.longrunning.Operations.CancelOperation].
     * This will cause any inflight jobs to be cancelled and workflow-owned
     * clusters to be deleted.
     *
     * The [Operation.metadata][google.longrunning.Operation.metadata] will be
     * [WorkflowMetadata][google.cloud.dataproc.v1.WorkflowMetadata].
     *
     * On successful completion,
     * [Operation.response][google.longrunning.Operation.response] will be
     * [Empty][google.protobuf.Empty].
     *
     * Sample code:
     * ```
     * $workflowTemplateServiceClient = new WorkflowTemplateServiceClient();
     * try {
     *     $formattedName = $workflowTemplateServiceClient->workflowTemplateName('[PROJECT]', '[REGION]', '[WORKFLOW_TEMPLATE]');
     *     $operationResponse = $workflowTemplateServiceClient->instantiateWorkflowTemplate($formattedName);
     *     $operationResponse->pollUntilComplete();
     *     if ($operationResponse->operationSucceeded()) {
     *         // operation succeeded and returns no value
     *     } else {
     *         $error = $operationResponse->getError();
     *         // handleError($error)
     *     }
     *
     *
     *     // Alternatively:
     *
     *     // start the operation, keep the operation name, and resume later
     *     $operationResponse = $workflowTemplateServiceClient->instantiateWorkflowTemplate($formattedName);
     *     $operationName = $operationResponse->getName();
     *     // ... do other work
     *     $newOperationResponse = $workflowTemplateServiceClient->resumeOperation($operationName, 'instantiateWorkflowTemplate');
     *     while (!$newOperationResponse->isDone()) {
     *         // ... do other work
     *         $newOperationResponse->reload();
     *     }
     *     if ($newOperationResponse->operationSucceeded()) {
     *       // operation succeeded and returns no value
     *     } else {
     *       $error = $newOperationResponse->getError();
     *       // handleError($error)
     *     }
     * } finally {
     *     $workflowTemplateServiceClient->close();
     * }
     * ```
     *
     * @param string $name         Required. The "resource name" of the workflow template, as described
     *                             in https://cloud.google.com/apis/design/resource_names of the form
     *                             `projects/{project_id}/regions/{region}/workflowTemplates/{template_id}`
     * @param array  $optionalArgs {
     *                             Optional.
     *
     *     @type int $version
     *          Optional. The version of workflow template to instantiate. If specified,
     *          the workflow will be instantiated only if the current version of
     *          the workflow template has the supplied version.
     *
     *          This option cannot be used to instantiate a previous version of
     *          workflow template.
     *     @type string $requestId
     *          Optional. A tag that prevents multiple concurrent workflow
     *          instances with the same tag from running. This mitigates risk of
     *          concurrent instances started due to retries.
     *
     *          It is recommended to always set this value to a
     *          [UUID](https://en.wikipedia.org/wiki/Universally_unique_identifier).
     *
     *          The tag must contain only letters (a-z, A-Z), numbers (0-9),
     *          underscores (_), and hyphens (-). The maximum length is 40 characters.
     *     @type array $parameters
     *          Optional. Map from parameter names to values that should be used for those
     *          parameters. Values may not exceed 100 characters.
     *     @type RetrySettings|array $retrySettings
     *          Retry settings to use for this call. Can be a
     *          {@see Google\ApiCore\RetrySettings} object, or an associative array
     *          of retry settings parameters. See the documentation on
     *          {@see Google\ApiCore\RetrySettings} for example usage.
     * }
     *
     * @return \Google\ApiCore\OperationResponse
     *
     * @throws ApiException if the remote call fails
     * @experimental
     */
    public function instantiateWorkflowTemplate($name, array $optionalArgs = [])
    {
        $request = new InstantiateWorkflowTemplateRequest();
        $request->setName($name);
        if (isset($optionalArgs['version'])) {
            $request->setVersion($optionalArgs['version']);
        }
        if (isset($optionalArgs['requestId'])) {
            $request->setRequestId($optionalArgs['requestId']);
        }
        if (isset($optionalArgs['parameters'])) {
            $request->setParameters($optionalArgs['parameters']);
        }

        return $this->startOperationsCall(
            'InstantiateWorkflowTemplate',
            $optionalArgs,
            $request,
            $this->getOperationsClient()
        )->wait();
    }

    /**
     * Instantiates a template and begins execution.
     *
     * This method is equivalent to executing the sequence
     * [CreateWorkflowTemplate][google.cloud.dataproc.v1.WorkflowTemplateService.CreateWorkflowTemplate], [InstantiateWorkflowTemplate][google.cloud.dataproc.v1.WorkflowTemplateService.InstantiateWorkflowTemplate],
     * [DeleteWorkflowTemplate][google.cloud.dataproc.v1.WorkflowTemplateService.DeleteWorkflowTemplate].
     *
     * The returned Operation can be used to track execution of
     * workflow by polling
     * [operations.get][google.longrunning.Operations.GetOperation].
     * The Operation will complete when entire workflow is finished.
     *
     * The running workflow can be aborted via
     * [operations.cancel][google.longrunning.Operations.CancelOperation].
     * This will cause any inflight jobs to be cancelled and workflow-owned
     * clusters to be deleted.
     *
     * The [Operation.metadata][google.longrunning.Operation.metadata] will be
     * [WorkflowMetadata][google.cloud.dataproc.v1.WorkflowMetadata].
     *
     * On successful completion,
     * [Operation.response][google.longrunning.Operation.response] will be
     * [Empty][google.protobuf.Empty].
     *
     * Sample code:
     * ```
     * $workflowTemplateServiceClient = new WorkflowTemplateServiceClient();
     * try {
     *     $formattedParent = $workflowTemplateServiceClient->regionName('[PROJECT]', '[REGION]');
     *     $template = new WorkflowTemplate();
     *     $operationResponse = $workflowTemplateServiceClient->instantiateInlineWorkflowTemplate($formattedParent, $template);
     *     $operationResponse->pollUntilComplete();
     *     if ($operationResponse->operationSucceeded()) {
     *         // operation succeeded and returns no value
     *     } else {
     *         $error = $operationResponse->getError();
     *         // handleError($error)
     *     }
     *
     *
     *     // Alternatively:
     *
     *     // start the operation, keep the operation name, and resume later
     *     $operationResponse = $workflowTemplateServiceClient->instantiateInlineWorkflowTemplate($formattedParent, $template);
     *     $operationName = $operationResponse->getName();
     *     // ... do other work
     *     $newOperationResponse = $workflowTemplateServiceClient->resumeOperation($operationName, 'instantiateInlineWorkflowTemplate');
     *     while (!$newOperationResponse->isDone()) {
     *         // ... do other work
     *         $newOperationResponse->reload();
     *     }
     *     if ($newOperationResponse->operationSucceeded()) {
     *       // operation succeeded and returns no value
     *     } else {
     *       $error = $newOperationResponse->getError();
     *       // handleError($error)
     *     }
     * } finally {
     *     $workflowTemplateServiceClient->close();
     * }
     * ```
     *
     * @param string           $parent       Required. The "resource name" of the workflow template region, as described
     *                                       in https://cloud.google.com/apis/design/resource_names of the form
     *                                       `projects/{project_id}/regions/{region}`
     * @param WorkflowTemplate $template     Required. The workflow template to instantiate.
     * @param array            $optionalArgs {
     *                                       Optional.
     *
     *     @type string $requestId
     *          Optional. A tag that prevents multiple concurrent workflow
     *          instances with the same tag from running. This mitigates risk of
     *          concurrent instances started due to retries.
     *
     *          It is recommended to always set this value to a
     *          [UUID](https://en.wikipedia.org/wiki/Universally_unique_identifier).
     *
     *          The tag must contain only letters (a-z, A-Z), numbers (0-9),
     *          underscores (_), and hyphens (-). The maximum length is 40 characters.
     *     @type RetrySettings|array $retrySettings
     *          Retry settings to use for this call. Can be a
     *          {@see Google\ApiCore\RetrySettings} object, or an associative array
     *          of retry settings parameters. See the documentation on
     *          {@see Google\ApiCore\RetrySettings} for example usage.
     * }
     *
     * @return \Google\ApiCore\OperationResponse
     *
     * @throws ApiException if the remote call fails
     * @experimental
     */
    public function instantiateInlineWorkflowTemplate($parent, $template, array $optionalArgs = [])
    {
        $request = new InstantiateInlineWorkflowTemplateRequest();
        $request->setParent($parent);
        $request->setTemplate($template);
        if (isset($optionalArgs['requestId'])) {
            $request->setRequestId($optionalArgs['requestId']);
        }

        return $this->startOperationsCall(
            'InstantiateInlineWorkflowTemplate',
            $optionalArgs,
            $request,
            $this->getOperationsClient()
        )->wait();
    }

    /**
     * Updates (replaces) workflow template. The updated template
     * must contain version that matches the current server version.
     *
     * Sample code:
     * ```
     * $workflowTemplateServiceClient = new WorkflowTemplateServiceClient();
     * try {
     *     $template = new WorkflowTemplate();
     *     $response = $workflowTemplateServiceClient->updateWorkflowTemplate($template);
     * } finally {
     *     $workflowTemplateServiceClient->close();
     * }
     * ```
     *
     * @param WorkflowTemplate $template Required. The updated workflow template.
     *
     * The `template.version` field must match the current version.
     * @param array $optionalArgs {
     *                            Optional.
     *
     *     @type RetrySettings|array $retrySettings
     *          Retry settings to use for this call. Can be a
     *          {@see Google\ApiCore\RetrySettings} object, or an associative array
     *          of retry settings parameters. See the documentation on
     *          {@see Google\ApiCore\RetrySettings} for example usage.
     * }
     *
     * @return \Google\Cloud\Dataproc\V1\WorkflowTemplate
     *
     * @throws ApiException if the remote call fails
     * @experimental
     */
    public function updateWorkflowTemplate($template, array $optionalArgs = [])
    {
        $request = new UpdateWorkflowTemplateRequest();
        $request->setTemplate($template);

        return $this->startCall(
            'UpdateWorkflowTemplate',
            WorkflowTemplate::class,
            $optionalArgs,
            $request
        )->wait();
    }

    /**
     * Lists workflows that match the specified filter in the request.
     *
     * Sample code:
     * ```
     * $workflowTemplateServiceClient = new WorkflowTemplateServiceClient();
     * try {
     *     $formattedParent = $workflowTemplateServiceClient->regionName('[PROJECT]', '[REGION]');
     *     // Iterate over pages of elements
     *     $pagedResponse = $workflowTemplateServiceClient->listWorkflowTemplates($formattedParent);
     *     foreach ($pagedResponse->iteratePages() as $page) {
     *         foreach ($page as $element) {
     *             // doSomethingWith($element);
     *         }
     *     }
     *
     *
     *     // Alternatively:
     *
     *     // Iterate through all elements
     *     $pagedResponse = $workflowTemplateServiceClient->listWorkflowTemplates($formattedParent);
     *     foreach ($pagedResponse->iterateAllElements() as $element) {
     *         // doSomethingWith($element);
     *     }
     * } finally {
     *     $workflowTemplateServiceClient->close();
     * }
     * ```
     *
     * @param string $parent       Required. The "resource name" of the region, as described
     *                             in https://cloud.google.com/apis/design/resource_names of the form
     *                             `projects/{project_id}/regions/{region}`
     * @param array  $optionalArgs {
     *                             Optional.
     *
     *     @type int $pageSize
     *          The maximum number of resources contained in the underlying API
     *          response. The API may return fewer values in a page, even if
     *          there are additional values to be retrieved.
     *     @type string $pageToken
     *          A page token is used to specify a page of values to be returned.
     *          If no page token is specified (the default), the first page
     *          of values will be returned. Any page token used here must have
     *          been generated by a previous call to the API.
     *     @type RetrySettings|array $retrySettings
     *          Retry settings to use for this call. Can be a
     *          {@see Google\ApiCore\RetrySettings} object, or an associative array
     *          of retry settings parameters. See the documentation on
     *          {@see Google\ApiCore\RetrySettings} for example usage.
     * }
     *
     * @return \Google\ApiCore\PagedListResponse
     *
     * @throws ApiException if the remote call fails
     * @experimental
     */
    public function listWorkflowTemplates($parent, array $optionalArgs = [])
    {
        $request = new ListWorkflowTemplatesRequest();
        $request->setParent($parent);
        if (isset($optionalArgs['pageSize'])) {
            $request->setPageSize($optionalArgs['pageSize']);
        }
        if (isset($optionalArgs['pageToken'])) {
            $request->setPageToken($optionalArgs['pageToken']);
        }

        return $this->getPagedListResponse(
            'ListWorkflowTemplates',
            $optionalArgs,
            ListWorkflowTemplatesResponse::class,
            $request
        );
    }

    /**
     * Deletes a workflow template. It does not cancel in-progress workflows.
     *
     * Sample code:
     * ```
     * $workflowTemplateServiceClient = new WorkflowTemplateServiceClient();
     * try {
     *     $formattedName = $workflowTemplateServiceClient->workflowTemplateName('[PROJECT]', '[REGION]', '[WORKFLOW_TEMPLATE]');
     *     $workflowTemplateServiceClient->deleteWorkflowTemplate($formattedName);
     * } finally {
     *     $workflowTemplateServiceClient->close();
     * }
     * ```
     *
     * @param string $name         Required. The "resource name" of the workflow template, as described
     *                             in https://cloud.google.com/apis/design/resource_names of the form
     *                             `projects/{project_id}/regions/{region}/workflowTemplates/{template_id}`
     * @param array  $optionalArgs {
     *                             Optional.
     *
     *     @type int $version
     *          Optional. The version of workflow template to delete. If specified,
     *          will only delete the template if the current server version matches
     *          specified version.
     *     @type RetrySettings|array $retrySettings
     *          Retry settings to use for this call. Can be a
     *          {@see Google\ApiCore\RetrySettings} object, or an associative array
     *          of retry settings parameters. See the documentation on
     *          {@see Google\ApiCore\RetrySettings} for example usage.
     * }
     *
     * @throws ApiException if the remote call fails
     * @experimental
     */
    public function deleteWorkflowTemplate($name, array $optionalArgs = [])
    {
        $request = new DeleteWorkflowTemplateRequest();
        $request->setName($name);
        if (isset($optionalArgs['version'])) {
            $request->setVersion($optionalArgs['version']);
        }

        return $this->startCall(
            'DeleteWorkflowTemplate',
            GPBEmpty::class,
            $optionalArgs,
            $request
        )->wait();
    }
}
