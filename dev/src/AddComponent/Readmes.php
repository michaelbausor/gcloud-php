<?php
/**
 * Copyright 2017 Google Inc.
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

namespace Google\Cloud\Dev\AddComponent;

use Google\Cloud\Dev\AddComponent\Command\AddComponent;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Write README files for the component.
 */
class Readmes
{
    const README_TPL = 'template-README.md.txt';
    const README_GEN_CLIENT_TPL = 'template-README.md-generated-client.txt';
    const GRPC_NOTICE_TPL = 'template-README.md-%s.txt';
    const SUGGESTION_TEXT = ' (skip suggested based on path name)';

    use PathTrait;
    use QuestionTrait;

    /**
     * @var QuestionHelper
     */
    private $questionHelper;

    /**
     * @var InputInterface
     */
    private $input;

    /**
     * @var OutputInterface
     */
    private $output;

    /**
     * @var string
     */
    private $rootPath;

    /**
     * @var array
     */
    private $info;

    /**
     * @var string
     */
    private $templatesPath;

    public function __construct(
        QuestionHelper $questionHelper,
        InputInterface $input,
        OutputInterface $output,
        $rootPath,
        array $info
    ) {
        $this->questionHelper = $questionHelper;
        $this->input = $input;
        $this->output = $output;
        $this->rootPath = $rootPath;
        $this->info = $info;

        $this->templatesPath = $this->rootPath .'/dev/src/AddComponent/templates';
    }

    public function run()
    {
        $this->createReadmes($this->info['path']);
    }

    /**
     * Recursively search for paths that contain /V*, and create a minimal generated client readme.
     * @param $path
     */
    private function createReadmesInGeneratedClientRoot($path)
    {
        if ($this->pathIsGapic($path)) {
            $questionText = sprintf('%s ' . PHP_EOL . 'Create README.md?', $path);
            $q = $this->confirm($questionText);
            $create = $this->askQuestion($q);
            if ($create) {
                $file = $path .'/README.md';
                $content = file_get_contents($this->templatesPath .'/'. self::README_GEN_CLIENT_TPL);

                $content = str_replace('{notice}', file_get_contents(
                    $this->templatesPath .'/'. sprintf(self::GRPC_NOTICE_TPL, $this->info['type'])
                ), $content);
                $content = str_replace('{display}', $this->info['display'], $content);
                $content = str_replace('{version}', $this->getVersionFromPath($path), $content);

                file_put_contents($file, $content);
            }
            return;
        }

        $files = $this->scanDirectory($path);
        foreach ($files as $file) {
            $file = $path .'/'. $file;
            if (is_dir($file)) {
                $this->createReadmesInGeneratedClientRoot($file);
            }
        }
    }

    private function createReadmes($path)
    {
        $files = $this->scanDirectory($path);
        if (!in_array('README.md', $files)) {
            $questionText = sprintf('%s '. PHP_EOL .'Create README.md?', $path);
            $q = $this->confirm($questionText);
            $create = $this->askQuestion($q);

            if ($create) {
                $file = $path .'/README.md';
                $content = file_get_contents($this->templatesPath .'/'. self::README_TPL);

                $content = str_replace('{notice}', file_get_contents(
                    $this->templatesPath .'/'. sprintf(self::GRPC_NOTICE_TPL, $this->info['type'])
                ), $content);
                $content = str_replace('{display}', $this->info['display'], $content);
                $content = str_replace('{homepage}', $this->info['cloudPage'], $content);
                $content = str_replace('{docspage}', $this->info['docsPage'], $content);
                $content = str_replace('{name}', $this->info['name'], $content);
                $content = str_replace('{client}', 'readme', $content);
                $content = str_replace('{directory}', strtolower(basename($this->info['path'])), $content);

                file_put_contents($file, $content);
            }
        }

        foreach ($files as $file) {
            $file = $path .'/'. $file;
            if (is_dir($file)) {
                $this->createReadmesInGeneratedClientRoot($file);
            }
        }
    }

    protected function questionHelper()
    {
        return $this->questionHelper;
    }

    protected function input()
    {
        return $this->input;
    }

    protected function output()
    {
        return $this->output;
    }
}
