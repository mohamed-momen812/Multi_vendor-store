<?php

return [
    [
        "icon" => "nav-icon fas fa-tachometer-alt",
        "route" => "dashboard.dashboard",
        'title' => 'dashboard',
        'active'=> 'dashboard.dashboard',  // the name of route
    ],
    [
        "icon" => "far fa-circle nav-icon",
        "route" => "dashboard.categories.index",
        'title' => 'Categories',
        'active'=> 'dashboard.categories.*',
    ],
    [
        "icon" => "far fa-circle nav-icon",
        "route" => "dashboard.products.index",
        'title' => 'Products',
        'badge' => 'New',
        'active'=> 'dashboard.products.*',
    ],
    [
        "icon" => "far fa-circle nav-icon",
        "route" => "dashboard.categories.index",
        'title' => 'Orders',
        'badge' => 'New',
        'active'=> 'dashboard.orders.*',
    ],
];
