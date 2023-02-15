<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Request;

class IpLocator
{
    public function getIpAddress(Request $request): string
    {
        return $request->getClientIp();
    }
}