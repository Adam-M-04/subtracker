<?php

namespace Enums;

enum Status: int
{
    case ACTIVE = 1;
    case PAUSED = 2;
    case INACTIVE = 3;
}