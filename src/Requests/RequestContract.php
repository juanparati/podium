<?php

namespace Juanparati\Podium\Requests;

use Juanparati\Podium\Podium;

interface RequestContract
{
    public function __construct(Podium $podium);
}