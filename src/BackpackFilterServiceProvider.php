<?php

namespace Winex01\BackpackFilter;

use Illuminate\Support\ServiceProvider;

class BackpackFilterServiceProvider extends ServiceProvider
{
    use AutomaticServiceProvider;

    protected $vendorName = 'winex01';
    protected $packageName = 'backpack-filter';
    protected $commands = [];
}
