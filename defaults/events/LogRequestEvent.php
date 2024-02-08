<?php

// Filename: LogRequestEvent.php
// Location: ngframerphp.base/defaults/events/LogRequestEvent.php

namespace NGFramer\NGFramerPHPBase\defaults\events;

use NGFramer\NGFramerPHPBase\event\Event;

class LogRequestEvent extends Event
{
    protected string $name = "LogRequestEvent";
}