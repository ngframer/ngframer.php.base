<?php

namespace NGFramer\NGFramerPHPBase\Response;

use App\Config\ApplicationConfig;

class _Render
{
    /**
     * Instanciate for Render.
     * Use same object all the time.
     *
     * @var _RenderPage $render
     */
    private _RenderPage $render;


    /**
     * Function to render a JSON response.
     *
     * @param array $data
     * @return void
     */
    public function json(array $data): void
    {
        header('Content-Type: application/json');
        echo json_encode($data);
    }


    /**
     * Function to render a view.
     *
     * @param string $layout
     * @param string $page
     * @param array $content
     * @return void
     */
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


    /**
     * Function to render a HTML response.
     *
     * @param string $html
     * @return void
     */
    public function html(string $html): void
    {
        header('Content-Type: text/html');
        echo $html;
    }


    /**
     * Function to render a page.
     *
     * @return _RenderPage
     */
    public function page(): _RenderPage
    {
        if (empty($this->render)) {
            $this->render = new _RenderPage();
        }
        return $this->render;
    }
}