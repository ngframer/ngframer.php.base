<?php

namespace NGFramer\NGFramerPHPBase\response;

class _Render
{
    public function json(array $data): void
    {
        header('Content-Type: application/json');
        echo json_encode($data);
    }


    public function view(string $layoutView): _RenderView
    {
        return new _RenderView($layoutView);
    }


    public function html(string $html): void
    {
        header('Content-Type: text/html');
        echo $html;
    }
}