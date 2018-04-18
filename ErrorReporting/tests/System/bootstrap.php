<?php

use Google\Cloud\Core\Testing\TestHelpers;

TestHelpers::requireKeyfile('GOOGLE_CLOUD_PHP_TESTS_KEY_PATH');

$keyFilePath = getenv('GOOGLE_CLOUD_PHP_TESTS_KEY_PATH');
$keyFileData = json_decode(file_get_contents($keyFilePath), true);
$projectId = $keyFileData['project_id'];

putenv("GOOGLE_APPLICATION_CREDENTIALS=$keyFilePath");
putenv("PROJECT_ID=$projectId");
