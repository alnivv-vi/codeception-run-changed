<?php

declare(strict_types=1);

namespace Codeception\Extension;

use Codeception\Event\PrintResultEvent;
use Codeception\Events;
use Codeception\Extension;
use Codeception\Test\Descriptor;

use function array_key_exists;
use function file_put_contents;
use function implode;
use function is_file;
use function realpath;
use function str_replace;
use function strlen;
use function substr;
use function unlink;
use PhpParser\Error;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitor\NameResolver;
use PhpParser\ParserFactory;

/**
 * Saves failed tests into tests/_output/failed in order to rerun failed tests.
 *
 * To rerun failed tests just run the `failed` group:
 *
 * ```
 * php codecept run -g failed
 * ```
 *
 * To change failed group name add:
 * ```
 * --override "extensions: config: Codeception\Extension\RunFailed: fail-group: another_group1"
 * ```
 * Remember: if you run tests and they generated custom-named fail group, to run this group, you should add override too
 *
 * Starting from Codeception 2.1 **this extension is enabled by default**.
 *
 * ``` yaml
 * extensions:
 *     enabled: [Codeception\Extension\RunFailed]
 * ```
 *
 * On each execution failed tests are logged and saved into `tests/_output/failed` file.
 */
class RunDiff extends Extension
{
    protected string $group = 'diff';

    public function _initialize(): void
    {
        if (array_key_exists('diff-group', $this->config) && $this->config['diff-group']) {
            $this->group = $this->config['diff-group'];
        }
        $logPath = str_replace($this->getRootDir(), '', $this->getLogDir()); // get local path to logs
        $this->_reconfigure(['groups' => [$this->group => $logPath . $this->group]]);
        $this->getDiffTests();
    }

    private function getDiffTests()
    {
        $modifiedFiles = shell_exec("git diff --name-only master...HEAD");
        $modifiedFiles = array_filter(explode("\n", $modifiedFiles), function ($file) {
            return str_ends_with($file, "Cest.php");
        });
        foreach ($modifiedFiles as $file) {
            $groupFile = $this->getLogDir() . $this->group;
            if (is_file($groupFile)) {
                unlink($groupFile);
            }
            file_put_contents($groupFile, implode("\n", $file));
        }
    }
}
