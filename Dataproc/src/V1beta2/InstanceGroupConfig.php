<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: google/cloud/dataproc/v1beta2/clusters.proto

namespace Google\Cloud\Dataproc\V1beta2;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * Optional. The config settings for Compute Engine resources in
 * an instance group, such as a master or worker group.
 *
 * Generated from protobuf message <code>google.cloud.dataproc.v1beta2.InstanceGroupConfig</code>
 */
class InstanceGroupConfig extends \Google\Protobuf\Internal\Message
{
    /**
     * Optional. The number of VM instances in the instance group.
     * For master instance groups, must be set to 1.
     *
     * Generated from protobuf field <code>int32 num_instances = 1;</code>
     */
    private $num_instances = 0;
    /**
     * Output only. The list of instance names. Cloud Dataproc derives the names
     * from `cluster_name`, `num_instances`, and the instance group.
     *
     * Generated from protobuf field <code>repeated string instance_names = 2;</code>
     */
    private $instance_names;
    /**
     * Optional. The Compute Engine image resource used for cluster
     * instances. It can be specified or may be inferred from
     * `SoftwareConfig.image_version`.
     *
     * Generated from protobuf field <code>string image_uri = 3;</code>
     */
    private $image_uri = '';
    /**
     * Optional. The Compute Engine machine type used for cluster instances.
     * A full URL, partial URI, or short name are valid. Examples:
     * * `https://www.googleapis.com/compute/v1/projects/[project_id]/zones/us-east1-a/machineTypes/n1-standard-2`
     * * `projects/[project_id]/zones/us-east1-a/machineTypes/n1-standard-2`
     * * `n1-standard-2`
     * **Auto Zone Exception**: If you are using the Cloud Dataproc
     * [Auto Zone Placement](/dataproc/docs/concepts/configuring-clusters/auto-zone#using_auto_zone_placement)
     * feature, you must use the short name of the machine type
     * resource, for example, `n1-standard-2`.
     *
     * Generated from protobuf field <code>string machine_type_uri = 4;</code>
     */
    private $machine_type_uri = '';
    /**
     * Optional. Disk option config settings.
     *
     * Generated from protobuf field <code>.google.cloud.dataproc.v1beta2.DiskConfig disk_config = 5;</code>
     */
    private $disk_config = null;
    /**
     * Optional. Specifies that this instance group contains preemptible instances.
     *
     * Generated from protobuf field <code>bool is_preemptible = 6;</code>
     */
    private $is_preemptible = false;
    /**
     * Output only. The config for Compute Engine Instance Group
     * Manager that manages this group.
     * This is only used for preemptible instance groups.
     *
     * Generated from protobuf field <code>.google.cloud.dataproc.v1beta2.ManagedGroupConfig managed_group_config = 7;</code>
     */
    private $managed_group_config = null;
    /**
     * Optional. The Compute Engine accelerator configuration for these
     * instances.
     * **Beta Feature**: This feature is still under development. It may be
     * changed before final release.
     *
     * Generated from protobuf field <code>repeated .google.cloud.dataproc.v1beta2.AcceleratorConfig accelerators = 8;</code>
     */
    private $accelerators;
    /**
     * Optional. Specifies the minimum cpu platform for the Instance Group.
     * See [Cloud Dataproc&rarr;Minimum CPU Platform]
     * (/dataproc/docs/concepts/compute/dataproc-min-cpu).
     *
     * Generated from protobuf field <code>string min_cpu_platform = 9;</code>
     */
    private $min_cpu_platform = '';

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type int $num_instances
     *           Optional. The number of VM instances in the instance group.
     *           For master instance groups, must be set to 1.
     *     @type string[]|\Google\Protobuf\Internal\RepeatedField $instance_names
     *           Output only. The list of instance names. Cloud Dataproc derives the names
     *           from `cluster_name`, `num_instances`, and the instance group.
     *     @type string $image_uri
     *           Optional. The Compute Engine image resource used for cluster
     *           instances. It can be specified or may be inferred from
     *           `SoftwareConfig.image_version`.
     *     @type string $machine_type_uri
     *           Optional. The Compute Engine machine type used for cluster instances.
     *           A full URL, partial URI, or short name are valid. Examples:
     *           * `https://www.googleapis.com/compute/v1/projects/[project_id]/zones/us-east1-a/machineTypes/n1-standard-2`
     *           * `projects/[project_id]/zones/us-east1-a/machineTypes/n1-standard-2`
     *           * `n1-standard-2`
     *           **Auto Zone Exception**: If you are using the Cloud Dataproc
     *           [Auto Zone Placement](/dataproc/docs/concepts/configuring-clusters/auto-zone#using_auto_zone_placement)
     *           feature, you must use the short name of the machine type
     *           resource, for example, `n1-standard-2`.
     *     @type \Google\Cloud\Dataproc\V1beta2\DiskConfig $disk_config
     *           Optional. Disk option config settings.
     *     @type bool $is_preemptible
     *           Optional. Specifies that this instance group contains preemptible instances.
     *     @type \Google\Cloud\Dataproc\V1beta2\ManagedGroupConfig $managed_group_config
     *           Output only. The config for Compute Engine Instance Group
     *           Manager that manages this group.
     *           This is only used for preemptible instance groups.
     *     @type \Google\Cloud\Dataproc\V1beta2\AcceleratorConfig[]|\Google\Protobuf\Internal\RepeatedField $accelerators
     *           Optional. The Compute Engine accelerator configuration for these
     *           instances.
     *           **Beta Feature**: This feature is still under development. It may be
     *           changed before final release.
     *     @type string $min_cpu_platform
     *           Optional. Specifies the minimum cpu platform for the Instance Group.
     *           See [Cloud Dataproc&rarr;Minimum CPU Platform]
     *           (/dataproc/docs/concepts/compute/dataproc-min-cpu).
     * }
     */
    public function __construct($data = NULL) {
        \GPBMetadata\Google\Cloud\Dataproc\V1Beta2\Clusters::initOnce();
        parent::__construct($data);
    }

    /**
     * Optional. The number of VM instances in the instance group.
     * For master instance groups, must be set to 1.
     *
     * Generated from protobuf field <code>int32 num_instances = 1;</code>
     * @return int
     */
    public function getNumInstances()
    {
        return $this->num_instances;
    }

    /**
     * Optional. The number of VM instances in the instance group.
     * For master instance groups, must be set to 1.
     *
     * Generated from protobuf field <code>int32 num_instances = 1;</code>
     * @param int $var
     * @return $this
     */
    public function setNumInstances($var)
    {
        GPBUtil::checkInt32($var);
        $this->num_instances = $var;

        return $this;
    }

    /**
     * Output only. The list of instance names. Cloud Dataproc derives the names
     * from `cluster_name`, `num_instances`, and the instance group.
     *
     * Generated from protobuf field <code>repeated string instance_names = 2;</code>
     * @return \Google\Protobuf\Internal\RepeatedField
     */
    public function getInstanceNames()
    {
        return $this->instance_names;
    }

    /**
     * Output only. The list of instance names. Cloud Dataproc derives the names
     * from `cluster_name`, `num_instances`, and the instance group.
     *
     * Generated from protobuf field <code>repeated string instance_names = 2;</code>
     * @param string[]|\Google\Protobuf\Internal\RepeatedField $var
     * @return $this
     */
    public function setInstanceNames($var)
    {
        $arr = GPBUtil::checkRepeatedField($var, \Google\Protobuf\Internal\GPBType::STRING);
        $this->instance_names = $arr;

        return $this;
    }

    /**
     * Optional. The Compute Engine image resource used for cluster
     * instances. It can be specified or may be inferred from
     * `SoftwareConfig.image_version`.
     *
     * Generated from protobuf field <code>string image_uri = 3;</code>
     * @return string
     */
    public function getImageUri()
    {
        return $this->image_uri;
    }

    /**
     * Optional. The Compute Engine image resource used for cluster
     * instances. It can be specified or may be inferred from
     * `SoftwareConfig.image_version`.
     *
     * Generated from protobuf field <code>string image_uri = 3;</code>
     * @param string $var
     * @return $this
     */
    public function setImageUri($var)
    {
        GPBUtil::checkString($var, True);
        $this->image_uri = $var;

        return $this;
    }

    /**
     * Optional. The Compute Engine machine type used for cluster instances.
     * A full URL, partial URI, or short name are valid. Examples:
     * * `https://www.googleapis.com/compute/v1/projects/[project_id]/zones/us-east1-a/machineTypes/n1-standard-2`
     * * `projects/[project_id]/zones/us-east1-a/machineTypes/n1-standard-2`
     * * `n1-standard-2`
     * **Auto Zone Exception**: If you are using the Cloud Dataproc
     * [Auto Zone Placement](/dataproc/docs/concepts/configuring-clusters/auto-zone#using_auto_zone_placement)
     * feature, you must use the short name of the machine type
     * resource, for example, `n1-standard-2`.
     *
     * Generated from protobuf field <code>string machine_type_uri = 4;</code>
     * @return string
     */
    public function getMachineTypeUri()
    {
        return $this->machine_type_uri;
    }

    /**
     * Optional. The Compute Engine machine type used for cluster instances.
     * A full URL, partial URI, or short name are valid. Examples:
     * * `https://www.googleapis.com/compute/v1/projects/[project_id]/zones/us-east1-a/machineTypes/n1-standard-2`
     * * `projects/[project_id]/zones/us-east1-a/machineTypes/n1-standard-2`
     * * `n1-standard-2`
     * **Auto Zone Exception**: If you are using the Cloud Dataproc
     * [Auto Zone Placement](/dataproc/docs/concepts/configuring-clusters/auto-zone#using_auto_zone_placement)
     * feature, you must use the short name of the machine type
     * resource, for example, `n1-standard-2`.
     *
     * Generated from protobuf field <code>string machine_type_uri = 4;</code>
     * @param string $var
     * @return $this
     */
    public function setMachineTypeUri($var)
    {
        GPBUtil::checkString($var, True);
        $this->machine_type_uri = $var;

        return $this;
    }

    /**
     * Optional. Disk option config settings.
     *
     * Generated from protobuf field <code>.google.cloud.dataproc.v1beta2.DiskConfig disk_config = 5;</code>
     * @return \Google\Cloud\Dataproc\V1beta2\DiskConfig
     */
    public function getDiskConfig()
    {
        return $this->disk_config;
    }

    /**
     * Optional. Disk option config settings.
     *
     * Generated from protobuf field <code>.google.cloud.dataproc.v1beta2.DiskConfig disk_config = 5;</code>
     * @param \Google\Cloud\Dataproc\V1beta2\DiskConfig $var
     * @return $this
     */
    public function setDiskConfig($var)
    {
        GPBUtil::checkMessage($var, \Google\Cloud\Dataproc\V1beta2\DiskConfig::class);
        $this->disk_config = $var;

        return $this;
    }

    /**
     * Optional. Specifies that this instance group contains preemptible instances.
     *
     * Generated from protobuf field <code>bool is_preemptible = 6;</code>
     * @return bool
     */
    public function getIsPreemptible()
    {
        return $this->is_preemptible;
    }

    /**
     * Optional. Specifies that this instance group contains preemptible instances.
     *
     * Generated from protobuf field <code>bool is_preemptible = 6;</code>
     * @param bool $var
     * @return $this
     */
    public function setIsPreemptible($var)
    {
        GPBUtil::checkBool($var);
        $this->is_preemptible = $var;

        return $this;
    }

    /**
     * Output only. The config for Compute Engine Instance Group
     * Manager that manages this group.
     * This is only used for preemptible instance groups.
     *
     * Generated from protobuf field <code>.google.cloud.dataproc.v1beta2.ManagedGroupConfig managed_group_config = 7;</code>
     * @return \Google\Cloud\Dataproc\V1beta2\ManagedGroupConfig
     */
    public function getManagedGroupConfig()
    {
        return $this->managed_group_config;
    }

    /**
     * Output only. The config for Compute Engine Instance Group
     * Manager that manages this group.
     * This is only used for preemptible instance groups.
     *
     * Generated from protobuf field <code>.google.cloud.dataproc.v1beta2.ManagedGroupConfig managed_group_config = 7;</code>
     * @param \Google\Cloud\Dataproc\V1beta2\ManagedGroupConfig $var
     * @return $this
     */
    public function setManagedGroupConfig($var)
    {
        GPBUtil::checkMessage($var, \Google\Cloud\Dataproc\V1beta2\ManagedGroupConfig::class);
        $this->managed_group_config = $var;

        return $this;
    }

    /**
     * Optional. The Compute Engine accelerator configuration for these
     * instances.
     * **Beta Feature**: This feature is still under development. It may be
     * changed before final release.
     *
     * Generated from protobuf field <code>repeated .google.cloud.dataproc.v1beta2.AcceleratorConfig accelerators = 8;</code>
     * @return \Google\Protobuf\Internal\RepeatedField
     */
    public function getAccelerators()
    {
        return $this->accelerators;
    }

    /**
     * Optional. The Compute Engine accelerator configuration for these
     * instances.
     * **Beta Feature**: This feature is still under development. It may be
     * changed before final release.
     *
     * Generated from protobuf field <code>repeated .google.cloud.dataproc.v1beta2.AcceleratorConfig accelerators = 8;</code>
     * @param \Google\Cloud\Dataproc\V1beta2\AcceleratorConfig[]|\Google\Protobuf\Internal\RepeatedField $var
     * @return $this
     */
    public function setAccelerators($var)
    {
        $arr = GPBUtil::checkRepeatedField($var, \Google\Protobuf\Internal\GPBType::MESSAGE, \Google\Cloud\Dataproc\V1beta2\AcceleratorConfig::class);
        $this->accelerators = $arr;

        return $this;
    }

    /**
     * Optional. Specifies the minimum cpu platform for the Instance Group.
     * See [Cloud Dataproc&rarr;Minimum CPU Platform]
     * (/dataproc/docs/concepts/compute/dataproc-min-cpu).
     *
     * Generated from protobuf field <code>string min_cpu_platform = 9;</code>
     * @return string
     */
    public function getMinCpuPlatform()
    {
        return $this->min_cpu_platform;
    }

    /**
     * Optional. Specifies the minimum cpu platform for the Instance Group.
     * See [Cloud Dataproc&rarr;Minimum CPU Platform]
     * (/dataproc/docs/concepts/compute/dataproc-min-cpu).
     *
     * Generated from protobuf field <code>string min_cpu_platform = 9;</code>
     * @param string $var
     * @return $this
     */
    public function setMinCpuPlatform($var)
    {
        GPBUtil::checkString($var, True);
        $this->min_cpu_platform = $var;

        return $this;
    }

}

