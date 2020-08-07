<?php

if (!function_exists('isAdmin')) {

    /**
     * @return bool
     */
    function isAdmin(): bool
    {
        return auth()->user()->getRole()->isAdmin();
    }
}

if (!function_exists('canViewTrainings')) {

    /**
     * @return bool
     */
    function canViewTrainings(): bool
    {
        $userRole = auth()->user()->getRole();
        return $userRole->isAdmin() || $userRole->can_view_trainings;
    }
}

if(!function_exists('linkToRoute')) {
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
            $attributesStr .= " {$key}={$attribute}";
        }

        return sprintf("<a href='%s' %s>{$title}</a>", route($name, $parameters), $attributesStr);
    }
}
