<?php

namespace NGFramer\NGFramerPHPBase\Response;

use App\Config\ApplicationConfig;
use Exception;

class _RenderPage
{
    /**
     * Layout buffer variable.
     *
     * @var string|null $layoutBuffer
     */
    private ?string $layoutBuffer;


    /**
     * Section buffer variable.
     *
     * @var array $sectionBuffer
     */
    private array $sectionBuffer = [];


    /**
     * Function to start the rendering process.
     *
     * @param string $layout . Name of the layout to use.
     * @param array $param . Meta-variables for the layout.
     * @return void
     */
    public function layout(string $layout, array $param = []): void
    {
        // Get the root directory.
        $root = ApplicationConfig::get('root');

        // Get all the content data and extract them.
        extract($param);

        // Step 01, Load the layout and save it.
        ob_start();
        require_once $root . "/Views/Layouts/$layout.php";
        $this->layoutBuffer = ob_get_clean();
    }


    /**
     * Function to render a section.
     *
     * @param string $section
     * @param array $param
     * @return void
     * @throws Exception
     */
    public function section(string $section, array $param = []): void
    {
        // Get all the content data and extract them.
        extract($param);

        // Get the root directory.
        $root = ApplicationConfig::get('root');

        // Step 02, Load the content data (the page => sections/parts).
        ob_start();
        include_once $root . "/Views/Sections/$section.php";
        $this->sectionBuffer[] = ob_get_clean();
    }


    /**
     * Function to end the rendering process.
     *
     * @return void
     */
    public function build(): void
    {
        // First, get the layout and the content.
        $layout = $this->layoutBuffer;
        $content = implode('', $this->sectionBuffer);
        // Now render the content.
        echo str_replace('{{content}}', $content, $layout);
    }
}