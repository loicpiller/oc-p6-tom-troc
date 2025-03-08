<?php

use ScssPhp\ScssPhp\Compiler;
use ScssPhp\ScssPhp\OutputStyle;

require_once __DIR__ . '/../vendor/autoload.php';

/**
 * Compiles all SCSS files
 */
function compileAllScss(): void
{
    // Directory containing SCSS files to compile
    $scssDir = __DIR__ . '/../assets/scss/';

    // Directory where compiled CSS files will be saved
    $compiledDir = __DIR__ . '/../public/css/';

    // If the compiled files directory does not exist, create it
    if (!is_dir($compiledDir)) {
        mkdir($compiledDir, 0777, true);
    }

    // Get all SCSS files in the directory
    $scssFiles = glob($scssDir . '*.scss');

    if (empty($scssFiles)) {
        echo "No SCSS files found in the $scssDir directory\n";
        return;
    }

    $scssCompiler = new Compiler();
    $scssCompiler->setOutputStyle(OutputStyle::COMPRESSED);

    foreach ($scssFiles as $scssFile) {
        $scssFileName = pathinfo($scssFile, PATHINFO_FILENAME);
        $cssFile = $compiledDir . $scssFileName . '.css';

        $compiledCss = $scssCompiler->compileFile($scssFile)->getCss();
        file_put_contents($cssFile, $compiledCss);

        echo "Compiled $scssFile to $cssFile\n";
    }
}

compileAllScss();