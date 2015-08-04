<?php

namespace Sef\Controller;

use DI\Container;

interface ControllerInterface
{
    public function setDic(Container $dic);
}
