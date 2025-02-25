<?php

namespace MVC\Core;

use Exception;

/**
 * Handles view rendering with a layout system.
 */
class View
{
    private string $title;
    private string $layout;

    /**
     * Constructor.
     *
     * @param string $title Page title.
     * @param string $layout Layout file (default: "main").
     */
    public function __construct(string $title, string $layout = "main")
    {
        $this->title = $title;
        $this->layout = $layout;
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
        ob_start();
        include $layoutPath;
        echo ob_get_clean();
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
}