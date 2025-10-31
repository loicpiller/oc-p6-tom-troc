<?php

namespace MVC\Core;

use Exception;
use ScssPhp\ScssPhp\Compiler;
use ScssPhp\ScssPhp\Exception\SassException;
use ScssPhp\ScssPhp\OutputStyle;

/**
 * Handles view rendering with a layout system.
 */
class View
{
    private string $title;
    private string $layout;
    private array $styles = [];

    /**
     * Constructor.
     *
     * @param string $title Page title.
     * @param string $layout Layout file (default: "main").
     * @param array $styles Initial styles to include (default: ["global"]).
     */
    public function __construct(string $title, string $layout = "main", array $styles = ["reset", "global"])
    {
        $this->title = $title;
        $this->layout = $layout;
        foreach ($styles as $style) {
            $this->addStyle($style);
        }
    }

    /**
     * Adds a CSS file compiled from SCSS.
     *
     * If the APP_ENV is set to "development" or the CSS file does not exist, it will be compiled from the corresponding SCSS file.
     *
     * @param string $scssFile SCSS file name (without extension) inside /assets/scss/.
     * @throws Exception If the SCSS file is missing.
     * @throws SassException If the SCSS compilation fails.
     */
    public function addStyle(string $scssFile): void
    {
        $cssPath = __DIR__ . "/../public/css/$scssFile.css";

        $env = Config::getInstance()->get('APP_ENV');
        if ($env === 'development' || !file_exists($cssPath)) {
            $scssPath = __DIR__ . "/../assets/scss/$scssFile.scss";

            if (!file_exists($scssPath)) {
                throw new Exception("SCSS file not found: " . $scssPath);
            }

            $compiler = new Compiler();
            $compiler->setOutputStyle(OutputStyle::COMPRESSED);
            $compiledCss = $compiler->compileFile("$scssPath")->getCss();
            file_put_contents($cssPath, $compiledCss);
        }

        $this->styles[] = $scssFile;
    }

    /**
     * Renders a full page using a layout.
     *
     * @param string $templatePath Path of the view inside /views/.
     * @param array $data Variables to pass to the view.
     * @throws Exception If the view or layout file is missing.
     */
    public function render(string $templatePath, array $data = []): void
    {
        // Generate the content of the requested view
        $content = $this->renderPartial($templatePath, $data);

        // Load the selected layout
        $layoutPath = $this->buildViewPath("layouts/" . $this->layout);
        if (!file_exists($layoutPath)) {
            throw new Exception("Layout file not found: " . $layoutPath);
        }

        $title = $this->title;
        $styles = $this->styles;
        ob_start();
        include $layoutPath;
        echo ob_get_clean();

        $env = Config::getInstance()->get('APP_ENV');
        if ($env === 'development') $this->renderDebug();
    }

    /**
     * Renders a view without a layout (useful for AJAX requests).
     *
     * @param string $relativePath Path inside /views/.
     * @param array $data Variables to pass to the view.
     * @return string Rendered view content.
     * @throws Exception If the file does not exist.
     */
    public function renderPartial(string $relativePath, array $data = []): string
    {
        $viewPath = $this->buildViewPath($relativePath);
        if (!file_exists($viewPath)) {
            throw new Exception("View file not found: " . $viewPath);
        }

        extract($data);
        ob_start();
        include $viewPath;
        return ob_get_clean();
    }

    /**
     * Builds the full path to a view file.
     *
     * @param string $relativePath Path inside /views/.
     * @return string Full absolute path.
     */
    private function buildViewPath(string $relativePath): string
    {
        return __DIR__ . "/../app/views/" . $relativePath . ".php";
    }

    private function renderDebug(): void
    {
        $queries = \MVC\Core\QueryBuilder::getQueries();
        $session = $_SESSION ?? [];


        echo '<strong>=== DEBUG ===</strong>';
        echo '<strong>Session:</strong><pre>' . print_r($session, true) . '</pre>';
        echo '<strong>SQL Queries:</strong><br>';

        foreach ($queries as $q) {
            echo htmlspecialchars($q['sql']) . '<br>';
            if (!empty($q['params'])) {
                echo 'Params: ' . htmlspecialchars(json_encode($q['params'])) . '<br>';
            }
            echo '<hr>';
        }
    }
}
