<?php

namespace App\Http\Services;

class PrivateFolderHandler
{
    /**
     * This method should return the folder name for the private folder.
     *
     * @return string
     */
    public function userField()
    {
        // Customize the logic to generate the private folder name
        // For example, using user ID:
        return auth()->user()->tenant_id;
    }
}
