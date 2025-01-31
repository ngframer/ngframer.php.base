<?php

namespace NGFramer\NGFramerPHPBase\Response;

use App\Config\ApplicationConfig;

class _RenderHtml
{
    /**
     * Raw layout page buffer data.
     *
     * @var string
     */
    private string $layoutBuffer;


    /**
     * Raw layout page buffer data.
     * Will also contain the RAW HTML.
     * Will also contain the page data.
     *
     * @var array
     */
    private array $contentBuffer;


    /**
     * HTML generated data.
     *
     * @var string
     */
    private string $renderedOutput;


    /**
     * Function to render HTML response.
     *
     * @param string $html
     * @return static
     */
    public function raw(string $html): static
    {
        $this->contentBuffer[] = $html;
        return $this;
    }


    /**
     * Function to render a layout page.
     *
     * @param string $layout . The layout file name/location that will be rendered relative to the root.
     * @param array $param . The array data to be passed to the layout.
     * @return static
     */
    public function layout(string $layout, array $param = []): static
    {
        // Get all the param data and extract them.
        extract($param);

        // Get the root directory.
        $root = ApplicationConfig::get('root');

        // Load the content data.
        ob_start();
        if (file_exists($root . $layout)) include_once $root . $layout;
        else include_once $root . "/Views/Layouts/$layout.php";

        $this->layoutBuffer = ob_get_clean();
        return $this;
    }


    /**
     * Function to render a content in the layout.
     * If the layout has not been passed, only the content will be rendered.
     *
     * @param string $content . The content file name/location of the content relative to the root.
     * @param array $param . The array data to be passed to the content.
     * @return static
     */
    public function content(string $content, array $param = []): static
    {
        // Get all the param data and extract them.
        extract($param);

        // Get the root directory.
        $root = ApplicationConfig::get('root');

        // Load the content data.
        ob_start();
        include_once $root . "/Views/Sections/$content.php";
        $this->contentBuffer[] = ob_get_clean();
        return $this;
    }


    /**
     * Function to render the HTML generated.
     *
     * @return void
     */
    public function build(): void
    {
        // Check if the layout has been passed.
        if (!empty($this->layoutBuffer)) {
            // Replace the content in the layout.
            $this->renderedOutput = str_replace('{{content}}', implode('', $this->contentBuffer), $this->layoutBuffer);
        } else {
            // Build the content without any layout.
            $this->renderedOutput = implode('', $this->contentBuffer);
        }

        // Output the raw HTML.
        echo implode('', $this->renderedOutput);
    }
}
