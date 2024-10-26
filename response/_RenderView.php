<?php

namespace NGFramer\NGFramerPHPBase\response;

class _RenderView
{
    /**
     * RenderView constructor.
     */
    public function __construct(string $layoutView)
    {
        return $this->layout($layoutView);
    }


    /**
     * Variable to save the layout view.
     *
     * @param string $layoutView
     */
    protected ?string $layoutView;


    /**
     * Variable to save the data parameters.
     *
     * @param ?array $paramsData;
     */
    protected ?array $paramsData;


    /**
     * Variable to save the content view.
     *
     * @param string $contentView
     */
    protected ?string $contentView;


    /**
     * Load the layout data.
     * @param string $layoutView
     * @return _RenderView
     */
    protected function layout(string $layoutView): _RenderView
    {
        $this->layoutView = $layoutView;
        return $this;
    }


    /**
     * Load the content data.
     * @param string $contentView
     * @return _RenderView
     */
    public function content(string $contentView): _RenderView
    {
        $this->contentView = $contentView;
        return $this;
    }


    /**
     * Load the data parameters.
     * @param array $paramsData
     * @return _RenderView
     */
    public function param(array $paramsData): _RenderView
    {
        $this->paramsData = $paramsData;
        return $this;
    }


    /**
     * Render the view with the layout.
     * @return void
     */
    public function build(): void
    {
        // Get all the param values and extract them.
        $paramsData = $this->paramsData;
        extract($paramsData);

        // Step 01, Load the layout data (template data).
        ob_start();
        include_once ROOT . "/views/layouts/$layoutView.php";
        $layoutBuffer = ob_get_clean();

        // Step 02, Load the content data (the sections/parts).
        ob_start();
        include_once ROOT . "/views/$contentView.php";
        $contentBuffer = ob_get_clean();

        // Replace the {{content}} with the $contentBuffer to create a view.
        echo str_replace('{{content}}', $contentBuffer, $layoutBuffer);
    }
}
