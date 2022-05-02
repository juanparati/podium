<?php
namespace Juanparati\Podium;

class PodiumResponse
{
    public $body;
    public $status;
    public $headers;

    public function jsonBody()
    {
        return json_decode($this->body, true);
    }
}
