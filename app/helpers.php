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
