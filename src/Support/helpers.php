<?php

use Ignite\Crud\Models\ListItem;

if (! function_exists('b64')) {
    /**
     * Get base64 string from path.
     *
     * @param  string $path
     * @return void
     */
    function b64(string $path)
    {
        if (! file_exists($path)) {
            return;
        }

        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);

        return 'data:application/'.$type.';base64,'.base64_encode($data);
    }
}

if (! function_exists('child_is_active')) {
    /**
     * Check's if list item has child with active route.
     *
     * @param  ListItem $item
     * @param  string       $fieldId
     * @param  string       $value
     * @return mixed
     */
    function child_is_active(ListItem $item, $fieldId = 'route', $value = null)
    {
        foreach ($item->children as $child) {
            if (! $child->{$fieldId}) {
                continue;
            }
            if (! $child->{$fieldId}->isActive() && ! child_is_active($child, $fieldId, $value)) {
                continue;
            }

            return $value === null ? true : $value;
        }

        return false;
    }
}

if (! function_exists('__route')) {
    /**
     * Get translated route.
     *
     * @param  string      $string
     * @param  array       $parameters
     * @param  bool        $absolue
     * @param  string|null $locale
     * @return string|null
     */
    function __route($string, $parameters = [], $absolue = true, $locale = null)
    {
        if (! $locale) {
            $locale = app()->getLocale();
        }

        $name = $locale.'.'.$string;

        return route($name, $parameters, $absolue);
    }
}
