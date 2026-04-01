<?php

use App\SidebarItem;

if (!function_exists('dashboard_sidebar_items')) {
    function dashboard_sidebar_items()
    {
        return SidebarItem::all();
    }
}

if (!function_exists('dashboard_sidebar')) {
    function dashboard_sidebar()
    {
        return SidebarItem::sidebar();
    }
}

if (!function_exists('dashboard_search_url')) {
    function dashboard_search_url()
    {
        return route('dashboard.search');
    }
}
