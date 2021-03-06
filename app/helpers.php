<?php

if (!function_exists('isAdmin')) {

    /**
     * @return bool
     */
    function isAdmin(): bool
    {
        $userRole = auth()->user()->role;
        return isset($userRole) && $userRole->isAdmin();
    }
}

if (!function_exists('linkToRoute')) {
    /**
     * @param string $name
     * @param string|null $title
     * @param array $parameters
     * @param array $attributes
     * @return string
     */
    function linkToRoute(string $name, ?string $title = null, array $parameters = [], array $attributes = []): string
    {
        $attributesStr = '';

        foreach ($attributes as $key => $attribute) {
            $attributesStr .= " {$key}='{$attribute}'";
        }

        return sprintf("<a href='%s' %s>{$title}</a>", route($name, $parameters), $attributesStr);
    }
}
