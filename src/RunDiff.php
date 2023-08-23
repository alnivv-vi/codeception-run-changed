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
    /**
     * @var array<string, string>
     */
//    public static array $events = [
//        Events::RESULT_PRINT_AFTER => 'saveFailed'
//    ];

    /** @var string filename/groupname for failed tests */
    protected string $group = 'diff';

    public function _initialize(): void
    {
        if (array_key_exists('diff-group', $this->config) && $this->config['diff-group']) {
            $this->group = $this->config['diff-group'];
        }
        $logPath = str_replace($this->getRootDir(), '', $this->getLogDir()); // get local path to logs
        $this->_reconfigure(['groups' => [$this->group => $logPath . $this->group]]);
        $this->build();
    }

    public function build()
    {
//        $commitHash = exec('git rev-parse HEAD');
//        $diff = shell_exec("git diff --name-only master $commitHash -- $filePath");
//        echo $diff;
        // Get the changed files between the current branch and the master branch
        exec('git diff --name-only master...HEAD', $changed_files);
        foreach ($changed_files as $diff) {
            exec('git diff master... ' . $diff, $output);
print_r($output) ;

// Parse each file and extract the changed functions
//foreach ($files as $file) {
//    if (!empty($file)) {
//        $code = file_get_contents($file);
//
//        $parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);
//        $traverser = new NodeTraverser;
//        $traverser->addVisitor(new NameResolver);
//
//        try {
//            $stmts = $parser->parse($code);
//            $stmts = $traverser->traverse($stmts);
//
//            // Extract the changed functions
//            foreach ($stmts as $stmt) {
//                if ($stmt instanceof \PhpParser\Node\Stmt\Function_) {
//                    echo 'Changed function: ' . $stmt->name->name . PHP_EOL;
//                }
//            }
//        } catch (Error $error) {
//            echo 'Error parsing file: ' . $file . PHP_EOL;
//        }
//    }
        }

//
//        // Iterate over each changed file
//        foreach ($changed_files as $file) {
//            // Check if the file is a PHP file
//            if (pathinfo($file, PATHINFO_EXTENSION) === 'php') {
//                // Get the diff of the changes in the file for the specified commit hash
//                exec("git diff " . $commitHash . "..HEAD -- " . $file, $diff);
//                print_r($diff);
//                echo '_______________' . "\n";
//                // Iterate over each line in the diff
//                foreach ($diff as $line) {
//                    // Check if the line contains a function declaration
//                    if (preg_match('/^\+.*function\s+(\w+)\(/', $line, $matches)) {
//                        $function_name = $matches[1];
//                        $full_path = realpath($file);
//                        echo "Function: {$function_name}\n";
//                        echo "Path: {$full_path}\n\n";
//                    }
//                }
//            }
//        }
    }


//    public function saveFailed(PrintResultEvent $event): void
//    {
//        $file = $this->getLogDir() . $this->group;
//        $output = [];
//            $output[] = $this->localizePath(Descriptor::getTestFullName($);
//        foreach ($result->errors() as $fail) {
//            $output[] = $this->localizePath(Descriptor::getTestFullName($fail->getTest()));
//        }
//
//        file_put_contents($file, implode("\n", $output));
//    }
//
//    protected function localizePath(string $path): string
//    {
//        $root = realpath($this->getRootDir()) . DIRECTORY_SEPARATOR;
//        if (substr($path, 0, strlen($root)) === $root) {
//            return substr($path, strlen($root));
//        }
//        return $path;
//    }
    }
