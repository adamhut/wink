<?php

namespace adamhut\Wink;

class Wink
{
    /**
     * Get the default JavaScript variables for Wink.
     *
     * @return array
     */
    public static function scriptVariables()
    {
        return [
            'unsplash_key' => config('services.unsplash.key'),
            'path' => config('wink.path'),
            'author' => auth('wink')->check() ? auth('wink')->user()->only('name', 'avatar', 'id') : null,
        ];
    }

    /**
     * Abort the Http Request if not Admi
     *
     * @return array
     */
    public static function abortIfNotAdmin()
    {
        if (!in_array(auth()->user()->email, config('wink.admins'))) {
            abort(403);
        }
    }
}
