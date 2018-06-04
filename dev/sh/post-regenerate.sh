#!/bin/bash

# Revert changes in clients with partial veneers
git ch Iot/src/V1/DeviceManagerClient.php
git ch Redis/src/V1beta1/CloudRedisClient.php
git ch Spanner/src/V1/SpannerClient.php
git ch Speech/src/V1/SpeechClient.php
git ch Speech/src/V1beta1/SpeechClient.php
git ch Vision/src/V1/ImageAnnotatorClient.php

# Remove unwanted clients
rm -r Language/src/V1
rm -r Language/tests/*/V1
rm -r VideoIntelligence/src/V1beta1
rm -r VideoIntelligence/tests/*/V1beta1

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

# Update version file paths
sed -i "s/__DIR__.'\/..\/..\/..\/VERSION'/__DIR__.'\/..\/..\/..\/..\/VERSION'/" Bigtable/src/Admin/V2/Gapic/BigtableInstanceAdminGapicClient.php
sed -i "s/__DIR__.'\/..\/..\/..\/VERSION'/__DIR__.'\/..\/..\/..\/..\/VERSION'/" Bigtable/src/Admin/V2/Gapic/BigtableTableAdminGapicClient.php
sed -i "s/__DIR__.'\/..\/..\/..\/VERSION'/__DIR__.'\/..\/..\/..\/..\/..\/VERSION'/" Spanner/src/Admin/Database/V1/Gapic/DatabaseAdminGapicClient.php
sed -i "s/__DIR__.'\/..\/..\/..\/VERSION'/__DIR__.'\/..\/..\/..\/..\/..\/VERSION'/" Spanner/src/Admin/Instance/V1/Gapic/InstanceAdminGapicClient.php
