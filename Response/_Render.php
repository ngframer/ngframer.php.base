<?php

namespace NGFramer\NGFramerPHPBase\Response;

use App\Config\ApplicationConfig;

class _Render
{
    /**
     * Function to render HTML response.
     *
     * @return _RenderHtml
     */
    public function html(): _RenderHtml
    {
        return new _RenderHtml();
    }


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
}
