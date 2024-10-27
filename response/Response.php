<?php

namespace NGFramer\NGFramerPHPBase\response;

use Exception;
use NGFramer\NGFramerPHPBase\defaults\exceptions\FileException;

class Response
{
    /**
     * Set the status code of the response.
     * @param int $statusCode
     * @return void
     */
	public function statusCode(int $statusCode): void
	{
		http_response_code($statusCode);
	}


    /**
     * Function to render some kind of output.
     */
    public function render(): _Render
    {
        return new _Render();
    }


    /**
     * Redirect to the given hard-coded URL.
     * @param string $url
     * @return void
     */
    public function redirect(string $url): void
    {
        header('Location: ' . $url);
    }


    /**
     * Function to stream a download.
     *
     * @param string $filePath
     * @param string|null $downloadName
     * @return void
     *
     * @throws Exception
     */
    public function download(string $filePath, ?string $downloadName = null): void
    {
        // Check for the file's existence.
        if (!file_exists($filePath)) {
            $this->statusCode(404);
            throw new FileException('File not found.', 1001002, 'base.file.downloadFileNotFound');
        }

        // Define the download name.
        $downloadName = $downloadName ?? basename($filePath);

        // Set headers to initiate file download
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $downloadName . '"');
        header('Content-Length: ' . filesize($filePath));
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Expires: 0');

        // Clear the output buffer and read the file for download
        ob_clean();
        flush();
        readfile($filePath);
        exit;
    }
}