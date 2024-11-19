<?php

namespace NGFramer\NGFramerPHPBase\Response;

use App\Config\ApplicationConfig;

class _Render
{
    public function json(array $data): void
    {
        header('Content-Type: application/json');
        echo json_encode($data);
    }


    public function view(string $layout, string $page, array $content): void
    {
        // Get all the content data and extract them.
        extract($content);

        // Get the root directory.
        $root = ApplicationConfig::get('root');

        // Step 01, Load the layout page (template page).
        ob_start();
        include_once $root . "/Views/Layouts/$layout.php";
        $layoutBuffer = ob_get_clean();

        // Step 02, Load the content data (the page => sections/parts).
        ob_start();
        include_once $root . "/Views/Pages/$page.php";
        $contentBuffer = ob_get_clean();

        // Replace the {{content}} with the $contentBuffer to create a view.
        echo str_replace('{{content}}', $contentBuffer, $layoutBuffer);
    }


    public function html(string $html): void
    {
        header('Content-Type: text/html');
        echo $html;
    }
}