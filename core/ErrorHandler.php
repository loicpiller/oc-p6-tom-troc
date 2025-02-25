<?php
namespace MVC\Core;

use MVC\Core\Config;
use Throwable;

class ErrorHandler
{
    /**
     * Handles the error by either displaying it or logging it, depending on the environment.
     *
     * @param Throwable $exception The caught exception.
     */
    public static function handle(Throwable $exception): void
    {
        $env = Config::getInstance()->get('APP_ENV');

        if ($env === 'development') {
            self::renderErrorPage($exception);
        } else {
            self::logError($exception);
            echo "An error occurred, please try again later.";
        }
    }

    /**
     * Renders a detailed error page in development mode.
     *
     * @param Throwable $exception The caught exception.
     */
    private static function renderErrorPage(Throwable $exception): void
    {
        http_response_code(500);

        $message = htmlspecialchars($exception->getMessage(), ENT_QUOTES, 'UTF-8');
        $file = $exception->getFile();
        $line = $exception->getLine();
        $trace = nl2br(htmlspecialchars($exception->getTraceAsString(), ENT_QUOTES, 'UTF-8'));

        $codeSnippet = self::getCodeSnippet($file, $line);

        echo <<<HTML
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Application Error</title>
            <style>
                body { font-family: Arial, sans-serif; background: #2b2b2b; color: #fff; padding: 20px; }
                .container { max-width: 900px; margin: auto; background: #333; padding: 20px; border-radius: 8px; }
                h1 { color: #ff5c5c; }
                .error-message { font-size: 18px; color: #ffb86c; }
                .file-info { font-size: 16px; margin: 10px 0; }
                .code-snippet { background: #444; padding: 10px; border-radius: 5px; overflow-x: auto; }
                .trace { margin-top: 20px; padding: 10px; background: #555; border-radius: 5px; overflow-x: auto; }
            </style>
        </head>
        <body>
            <div class="container">
                <h1>⚠️ An error occurred</h1>
                <p class="error-message">{$message}</p>
                <p class="file-info">📌 File : <strong>{$file}</strong> - Line : <strong>{$line}</strong></p>
                <pre class="code-snippet">{$codeSnippet}</pre>
                <h2>Stack Trace</h2>
                <pre class="trace">{$trace}</pre>
            </div>
        </body>
        </html>
        HTML;
    }

    /**
     * Extracts a snippet of code surrounding the error line.
     *
     * @param string $file Path to the file.
     * @param int $errorLine The line where the error occurred.
     * @return string Formatted code snippet.
     */
    private static function getCodeSnippet(string $file, int $errorLine): string
    {
        if (!file_exists($file)) return "Code unavailable.";

        $lines = file($file, FILE_IGNORE_NEW_LINES);
        $start = max(0, $errorLine - 6);
        $end = min(count($lines), $errorLine + 5);
        $snippet = "";

        for ($i = $start; $i < $end; $i++) {
            $lineNumber = $i + 1;
            $highlight = $lineNumber === $errorLine ? "style='color: #ff5c5c; font-weight: bold;'" : "";
            $snippet .= "<span {$highlight}>{$lineNumber}: " . htmlspecialchars($lines[$i]) . "</span>\n";
        }

        return "<pre>{$snippet}</pre>";
    }

    /**
     * Logs the error details to a log file.
     *
     * @param Throwable $exception The caught exception.
     */
    private static function logError(Throwable $exception): void
    {
        $logMessage = "[" . date("Y-m-d H:i:s") . "] Error: " . $exception->getMessage() . "\n";
        $logMessage .= "File: " . $exception->getFile() . " on line " . $exception->getLine() . "\n";
        $logMessage .= "Stack trace: " . $exception->getTraceAsString() . "\n\n";

        file_put_contents(__DIR__ . '/../logs/error.log', $logMessage, FILE_APPEND);
    }
}
