#!/bin/bash

function setup_environment() {
  # This function expects to be executed from the root of
  # google-cloud-php
  GOOGLE_CLOUD_PHP_ROOT_DIR=$(pwd)

  ARTMAN_OUTPUT_DIR="$GOOGLE_CLOUD_PHP_ROOT_DIR/artman-output"
  ARTMAN_IMAGE="googleapis/artman:latest"
  GOOGLEAPIS_DIR="$GOOGLE_CLOUD_PHP_ROOT_DIR/googleapis"

  if [ ! -d "$GOOGLEAPIS_DIR" ]; then
    git clone git@github.com:googleapis/googleapis.git $GOOGLEAPIS_DIR
  fi

  # Check that artman is available on the path
  command -v artman >/dev/null 2>&1 || {
    echo >&2 "artman is required but not installed.  Aborting."; exit 1;
  }
}

# Merge the contents of the `proto` directory into `src`
# and `metadata` directories
#
# We assume that the protos live under the `Google\Cloud\<API>`
# root namespace - otherwise we can't distribute them as part
# of google-cloud-php
function merge_proto_into_src() {
  GENERATED_ROOT_DIR="$1"       # Absolute path to root of generated package
  ROOT_NAMESPACE_AS_PATH="$2"   # e.g. 'Google/Cloud/Language'

  # Optional, defaults to "GPBMetadata/$ROOT_NAMESPACE_AS_PATH"
  ROOT_METADATA_NAMESPACE_AS_PATH="$3" # e.g. 'Google/Cloud/Language'
  if [ -z "$3" ]; then
    ROOT_METADATA_NAMESPACE_AS_PATH="GPBMetadata/$ROOT_NAMESPACE_AS_PATH"
  fi

  echo "merge_proto_into_src $1 $2 $3"

  CLOUD_SRC_DIR=$GENERATED_ROOT_DIR/src
  CLOUD_METADATA_DIR=$GENERATED_ROOT_DIR/metadata

  mkdir -p $CLOUD_SRC_DIR
  mkdir -p $CLOUD_METADATA_DIR

  PROTO_DIR_TO_COPY=$GENERATED_ROOT_DIR/proto/src/$ROOT_NAMESPACE_AS_PATH
  PROTO_METADATA_DIR_TO_COPY=$GENERATED_ROOT_DIR/proto/src/$ROOT_METADATA_NAMESPACE_AS_PATH

  echo "cp -r $PROTO_DIR_TO_COPY/* $CLOUD_SRC_DIR/"
  cp -r $PROTO_DIR_TO_COPY/* $CLOUD_SRC_DIR/
  echo "cp -r $PROTO_METADATA_DIR_TO_COPY/* $CLOUD_METADATA_DIR/"
  cp -r $PROTO_METADATA_DIR_TO_COPY/* $CLOUD_METADATA_DIR/
}

function copy_artman_output_to_google_cloud_php() {
  GENERATED_ROOT_DIR="$1"         # Absolute path to root of generated package
  GOOGLE_CLOUD_PHP_API_DIR="$2"   # Absolute path to API dir in google-cloud-php

  mkdir -p $GOOGLE_CLOUD_PHP_API_DIR/src
  mkdir -p $GOOGLE_CLOUD_PHP_API_DIR/metadata
  # mkdir -p $GOOGLE_CLOUD_PHP_API_DIR/tests

  cp -r $GENERATED_ROOT_DIR/src/* $GOOGLE_CLOUD_PHP_API_DIR/src/
  cp -r $GENERATED_ROOT_DIR/metadata/* $GOOGLE_CLOUD_PHP_API_DIR/metadata/
  # cp -r $GENERATED_ROOT_DIR/tests/* $GOOGLE_CLOUD_PHP_API_DIR/tests/
}

# For some APIs such as Admin APIs, the generated structure is incorrect
# and we need to update it.
#
# For example, setting $DIRECTORY_STRUCTURE_TO_INSERT="Admin" will transform
# `src/V1/*` into `src/Admin/V1/*`
function restructure_generated_package() {
  GENERATED_ROOT_DIR="$1"            # Absolute path to root of generated package
  DIRECTORY_STRUCTURE_TO_INSERT="$2" # Structure to insert.

  GENERATED_SRC_DIR="$GENERATED_ROOT_DIR/src"
  TEMP_GENERATED_SRC_DIR="$GENERATED_ROOT_DIR/srcTempDir"
  RESTRUCTURED_SRC_DIR="$GENERATED_ROOT_DIR/src/$DIRECTORY_STRUCTURE_TO_INSERT"

  GENERATED_TEST_DIR="$GENERATED_ROOT_DIR/tests"

  # Move existing `src` folder, then grab its contents after
  # creating the new directory
  mv $GENERATED_SRC_DIR $TEMP_GENERATED_SRC_DIR
  mkdir -p $RESTRUCTURED_SRC_DIR
  mv $TEMP_GENERATED_SRC_DIR/* $RESTRUCTURED_SRC_DIR/
  rm -r $TEMP_GENERATED_SRC_DIR
}

function run_artman() {
  ARTMAN_YAML="$1"

  ARTMAN_ARGS="--image $ARTMAN_IMAGE --root-dir $GOOGLEAPIS_DIR --output-dir $ARTMAN_OUTPUT_DIR --config $ARTMAN_YAML generate php_gapic"
  artman $ARTMAN_ARGS
}


function regenerate_api() {
  API_ARTMAN_YAML="$1"
  API_ARTMAN_OUTPUT_DIR="$2"
  API_GCP_FOLDER_NAME="$3"
  API_METADATA_NAMESPACE_DIR="$4"
  if [ -z "$4" ]; then
    API_METADATA_NAMESPACE_DIR="GPBMetadata/Google/Cloud/$API_GCP_FOLDER_NAME"
  fi

  ARTMAN_API_OUTPUT_DIR="$ARTMAN_OUTPUT_DIR/php/$API_ARTMAN_OUTPUT_DIR"
  ROOT_NAMESPACE_AS_PATH="Google/Cloud/$API_GCP_FOLDER_NAME"
  GOOGLE_CLOUD_PHP_API_DIR="$GOOGLE_CLOUD_PHP_ROOT_DIR/$API_GCP_FOLDER_NAME"

  run_artman "$GOOGLEAPIS_DIR/$API_ARTMAN_YAML"
  merge_proto_into_src $ARTMAN_API_OUTPUT_DIR $ROOT_NAMESPACE_AS_PATH $API_METADATA_NAMESPACE_DIR
  copy_artman_output_to_google_cloud_php $ARTMAN_API_OUTPUT_DIR $GOOGLE_CLOUD_PHP_API_DIR
}

function post_regenerate() {

  # Revert changes in clients with partial veneers
  git ch Iot/src/V1/DeviceManagerClient.php
  git ch Redis/src/V1beta1/CloudRedisClient.php
  git ch Spanner/src/V1/SpannerClient.php
  git ch Speech/src/V1/SpeechClient.php
  git ch Speech/src/V1beta1/SpeechClient.php
  git ch Vision/src/V1/ImageAnnotatorClient.php

  # Revert changes in copyright files for generated clients
  FOLDERS_2016=(
    "Logging"
    "PubSub"
  )
  FOLDERS_2017=(
    "BigQueryDataTransfer"
    "Bigtable"
    "Container"
    "Dataproc"
    "ErrorReporting"
    "Firestore"
    "Language"
    "Monitoring"
    "OsLogin"
    "Spanner"
    "Speech"
    "Trace"
    "VideoIntelligence"
    "Vision"
  )
  FOLDERS_2018=(
    "Dlp"
    "Iot"
    "Monitoring/src/V3/Alert*"
    "Monitoring/src/V3/Gapic/Alert*"
    "Monitoring/src/V3/Notification*"
    "Monitoring/src/V3/Gapic/Notification*"
    "Speech/src/V1p1beta1/*"
  )

  function update_copyright {
    arr=( "$@" )
    for i in "${arr[@]:1}"
    do
      find $i -wholename */V[0-9]*/*Client.php -exec sed -i "s/Copyright 20[0-9]\{2\} Google LLC/Copyright $1 Google LLC/" {} \;
    done
  }

  update_copyright 2016 "${FOLDERS_2016[@]}"
  update_copyright 2017 "${FOLDERS_2017[@]}"
  update_copyright 2018 "${FOLDERS_2018[@]}"
}

function regenerate_container_v1() {
  regenerate_api "google/container/artman_container.yaml" "google-cloud-container-v1" "Container" "GPBMetadata/Google/Container"
}

function regenerate_tasks_v2beta2() {
  API_ARTMAN_YAML="google/bigtable/admin/artman_bigtableadmin.yaml"
  API_ARTMAN_OUTPUT_DIR="google-cloud-bigtable-admin-v2"
  API_GCP_FOLDER_NAME="Bigtable"
  API_METADATA_NAMESPACE_DIR=""

  regenerate_api "$API_ARTMAN_YAML" "$API_ARTMAN_OUTPUT_DIR" "$API_GCP_FOLDER_NAME" "$API_METADATA_NAMESPACE_DIR"
}

function regenerate_tasks_v2beta2() {
  API_ARTMAN_YAML="google/cloud/tasks/artman_cloudtasks.yaml"
  API_ARTMAN_OUTPUT_DIR="google-cloud-cloudtasks-v2beta2"
  API_GCP_FOLDER_NAME="Tasks"
  API_METADATA_NAMESPACE_DIR=""

  regenerate_api "$API_ARTMAN_YAML" "$API_ARTMAN_OUTPUT_DIR" "$API_GCP_FOLDER_NAME" "$API_METADATA_NAMESPACE_DIR"
}

function regenerate_tasks_v2beta2() {
  API_ARTMAN_YAML="google/cloud/tasks/artman_cloudtasks.yaml"
  API_ARTMAN_OUTPUT_DIR="google-cloud-cloudtasks-v2beta2"
  API_GCP_FOLDER_NAME="Tasks"
  API_METADATA_NAMESPACE_DIR=""

  regenerate_api "$API_ARTMAN_YAML" "$API_ARTMAN_OUTPUT_DIR" "$API_GCP_FOLDER_NAME" "$API_METADATA_NAMESPACE_DIR"
}

function regenerate_tasks_v2beta2() {
  API_ARTMAN_YAML="google/cloud/tasks/artman_cloudtasks.yaml"
  API_ARTMAN_OUTPUT_DIR="google-cloud-cloudtasks-v2beta2"
  API_GCP_FOLDER_NAME="Tasks"
  API_METADATA_NAMESPACE_DIR=""

  regenerate_api "$API_ARTMAN_YAML" "$API_ARTMAN_OUTPUT_DIR" "$API_GCP_FOLDER_NAME" "$API_METADATA_NAMESPACE_DIR"
}

function regenerate_tasks_v2beta2() {
  API_ARTMAN_YAML="google/cloud/tasks/artman_cloudtasks.yaml"
  API_ARTMAN_OUTPUT_DIR="google-cloud-cloudtasks-v2beta2"
  API_GCP_FOLDER_NAME="Tasks"
  API_METADATA_NAMESPACE_DIR=""

  regenerate_api "$API_ARTMAN_YAML" "$API_ARTMAN_OUTPUT_DIR" "$API_GCP_FOLDER_NAME" "$API_METADATA_NAMESPACE_DIR"
}

function regenerate_tasks_v2beta2() {
  API_ARTMAN_YAML="google/cloud/tasks/artman_cloudtasks.yaml"
  API_ARTMAN_OUTPUT_DIR="google-cloud-cloudtasks-v2beta2"
  API_GCP_FOLDER_NAME="Tasks"
  API_METADATA_NAMESPACE_DIR=""

  regenerate_api "$API_ARTMAN_YAML" "$API_ARTMAN_OUTPUT_DIR" "$API_GCP_FOLDER_NAME" "$API_METADATA_NAMESPACE_DIR"
}

function regenerate_tasks_v2beta2() {
  API_ARTMAN_YAML="google/cloud/tasks/artman_cloudtasks.yaml"
  API_ARTMAN_OUTPUT_DIR="google-cloud-cloudtasks-v2beta2"
  API_GCP_FOLDER_NAME="Tasks"
  API_METADATA_NAMESPACE_DIR=""

  regenerate_api "$API_ARTMAN_YAML" "$API_ARTMAN_OUTPUT_DIR" "$API_GCP_FOLDER_NAME" "$API_METADATA_NAMESPACE_DIR"
}

function regenerate_tasks_v2beta2() {
  API_ARTMAN_YAML="google/cloud/tasks/artman_cloudtasks.yaml"
  API_ARTMAN_OUTPUT_DIR="google-cloud-cloudtasks-v2beta2"
  API_GCP_FOLDER_NAME="Tasks"
  API_METADATA_NAMESPACE_DIR=""

  regenerate_api "$API_ARTMAN_YAML" "$API_ARTMAN_OUTPUT_DIR" "$API_GCP_FOLDER_NAME" "$API_METADATA_NAMESPACE_DIR"
}

function regenerate_tasks_v2beta2() {
  API_ARTMAN_YAML="google/cloud/tasks/artman_cloudtasks.yaml"
  API_ARTMAN_OUTPUT_DIR="google-cloud-cloudtasks-v2beta2"
  API_GCP_FOLDER_NAME="Tasks"
  API_METADATA_NAMESPACE_DIR=""

  regenerate_api "$API_ARTMAN_YAML" "$API_ARTMAN_OUTPUT_DIR" "$API_GCP_FOLDER_NAME" "$API_METADATA_NAMESPACE_DIR"
}

function regenerate_tasks_v2beta2() {
  API_ARTMAN_YAML="google/cloud/tasks/artman_cloudtasks.yaml"
  API_ARTMAN_OUTPUT_DIR="google-cloud-cloudtasks-v2beta2"
  API_GCP_FOLDER_NAME="Tasks"
  API_METADATA_NAMESPACE_DIR=""

  regenerate_api "$API_ARTMAN_YAML" "$API_ARTMAN_OUTPUT_DIR" "$API_GCP_FOLDER_NAME" "$API_METADATA_NAMESPACE_DIR"
}

function regenerate_tasks_v2beta2() {
  API_ARTMAN_YAML="google/cloud/tasks/artman_cloudtasks.yaml"
  API_ARTMAN_OUTPUT_DIR="google-cloud-cloudtasks-v2beta2"
  API_GCP_FOLDER_NAME="Tasks"
  API_METADATA_NAMESPACE_DIR=""

  regenerate_api "$API_ARTMAN_YAML" "$API_ARTMAN_OUTPUT_DIR" "$API_GCP_FOLDER_NAME" "$API_METADATA_NAMESPACE_DIR"
}

function regenerate_tasks_v2beta2() {
  API_ARTMAN_YAML="google/cloud/tasks/artman_cloudtasks.yaml"
  API_ARTMAN_OUTPUT_DIR="google-cloud-cloudtasks-v2beta2"
  API_GCP_FOLDER_NAME="Tasks"
  API_METADATA_NAMESPACE_DIR=""

  regenerate_api "$API_ARTMAN_YAML" "$API_ARTMAN_OUTPUT_DIR" "$API_GCP_FOLDER_NAME" "$API_METADATA_NAMESPACE_DIR"
}

function regenerate_tasks_v2beta2() {
  API_ARTMAN_YAML="google/cloud/tasks/artman_cloudtasks.yaml"
  API_ARTMAN_OUTPUT_DIR="google-cloud-cloudtasks-v2beta2"
  API_GCP_FOLDER_NAME="Tasks"
  API_METADATA_NAMESPACE_DIR=""

  regenerate_api "$API_ARTMAN_YAML" "$API_ARTMAN_OUTPUT_DIR" "$API_GCP_FOLDER_NAME" "$API_METADATA_NAMESPACE_DIR"
}

function regenerate_tasks_v2beta2() {
  API_ARTMAN_YAML="google/cloud/tasks/artman_cloudtasks.yaml"
  API_ARTMAN_OUTPUT_DIR="google-cloud-cloudtasks-v2beta2"
  API_GCP_FOLDER_NAME="Tasks"
  API_METADATA_NAMESPACE_DIR=""

  regenerate_api "$API_ARTMAN_YAML" "$API_ARTMAN_OUTPUT_DIR" "$API_GCP_FOLDER_NAME" "$API_METADATA_NAMESPACE_DIR"
}

function regenerate_tasks_v2beta2() {
  API_ARTMAN_YAML="google/cloud/tasks/artman_cloudtasks.yaml"
  API_ARTMAN_OUTPUT_DIR="google-cloud-cloudtasks-v2beta2"
  API_GCP_FOLDER_NAME="Tasks"
  API_METADATA_NAMESPACE_DIR=""

  regenerate_api "$API_ARTMAN_YAML" "$API_ARTMAN_OUTPUT_DIR" "$API_GCP_FOLDER_NAME" "$API_METADATA_NAMESPACE_DIR"
}

function regenerate_tasks_v2beta2() {
  API_ARTMAN_YAML="google/cloud/tasks/artman_cloudtasks.yaml"
  API_ARTMAN_OUTPUT_DIR="google-cloud-cloudtasks-v2beta2"
  API_GCP_FOLDER_NAME="Tasks"
  API_METADATA_NAMESPACE_DIR=""

  regenerate_api "$API_ARTMAN_YAML" "$API_ARTMAN_OUTPUT_DIR" "$API_GCP_FOLDER_NAME" "$API_METADATA_NAMESPACE_DIR"
}

function regenerate_tasks_v2beta2() {
  API_ARTMAN_YAML="google/cloud/tasks/artman_cloudtasks.yaml"
  API_ARTMAN_OUTPUT_DIR="google-cloud-cloudtasks-v2beta2"
  API_GCP_FOLDER_NAME="Tasks"
  API_METADATA_NAMESPACE_DIR=""

  regenerate_api "$API_ARTMAN_YAML" "$API_ARTMAN_OUTPUT_DIR" "$API_GCP_FOLDER_NAME" "$API_METADATA_NAMESPACE_DIR"
}

# Uncomment this line and set to the appropriate path
# to use a python virtualenv installation of artman
source ./env/bin/activate

set -ev
