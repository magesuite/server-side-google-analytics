<?php
declare(strict_types=1);

namespace MageSuite\ServerSideGoogleAnalytics\Model\Event;

interface EventInterface
{
    public function getData(): array;
}
