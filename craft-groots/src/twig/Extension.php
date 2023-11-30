<?php

namespace globalia\groots\twig;

use Craft;
use globalia\groots\GlobaliaGroots;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;
use Twig\TwigTest;

class Extension extends AbstractExtension
{
    protected $tokens;

    public function __construct()
    {
        // Get Design Tokens from JSON file
        $filename = GlobaliaGroots::getInstance()->getSettings()->getGrootsPath(). '/setup/tokens.json';
        // If file exists
        if (file_exists($filename)) {
            // Get contents of file
            $this->tokens = json_decode(file_get_contents($filename), true);
        }
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('getTokens', [$this, 'getTokens'])
        ];
    }

    public function getFilters()
    {
        return [
            new TwigFilter('sortArrayByProp', [$this, 'sortArrayByProp']),
            new TwigFilter('setIdleForAnchor', [$this, 'setIdleForAnchor'])
        ];
    }

    /**
     * Get Design Tokens and make them accessible within Twig templates.
     *
     * Usage:
     * {% set tokens = getTokens() %}
     */
    public function getTokens()
    {
        return $this->tokens;
    }



    /**
     * Twig filter to sort an array of objects by a property.
     *
     * Usage:
     *  {{ array|sortArrayByProp("property") }}            // Sort in descending order (default)
     *  {{ array|sortArrayByProp("property", "asc") }}     // Sort in ascending order
     *
     * Params:
     *  - `property`: Property of the object for sorting. Can be String, Numeric, or CSS pixel values (like "100px").
     *  - `order`: 'asc' for ascending, 'desc' for descending (default).
     *
     * Notes:
     *  - CSS pixel values (e.g., "100px") are converted to float.
     *  - Sorts numerically if applicable, otherwise lexicographically.
     *  - Assumes properties are consistent in type across objects.
     *  - Only works for top-level properties.
     */
    public function sortArrayByProp($sources, $property, $order = 'desc')
    {
        usort($sources, function ($a, $b) use ($property, $order) {
            $a = (array)$a;
            $b = (array)$b;
            $aValue = $a[$property];
            $bValue = $b[$property];

            if (is_string($aValue) && strpos($aValue, 'px') !== false) {
                $aValue = floatval(str_replace('px', '', $aValue));
            }

            if (is_string($bValue) && strpos($bValue, 'px') !== false) {
                $bValue = floatval(str_replace('px', '', $bValue));
            }

            $compareResult = 0;
            if ($aValue < $bValue) {
                $compareResult = -1;
            } elseif ($aValue > $bValue) {
                $compareResult = 1;
            }

            return $order === 'desc' ? -$compareResult : $compareResult;
        });

        return $sources;
    }

    /**
     * Twig filter to set the `idle` property of actions based on the type of the component.
     *
     * Usage:
     *  {{ actionsArray|setIdleForAnchor(typeArray) }}
     *
     * Params:
     *  - `actions`: Array of action objects. Each action object should have a 'component' property.
     *  - `type`: Array of component types where the first index is used to determine the type.
     *
     * Notes:
     *  - Checks if the first element of the `type` array is 'anchor'.
     *  - If it is, then the 'idle' property of the 'component' in each action object is set to true.
     *  - Returns the modified actions array.
     */
    public function setIdleForAnchor($actions, $type)
    {
        return array_map(function ($action) use ($type) {
            if ($type[0] === 'anchor') {
                $action['component']['idle'] = true;
            }
            return $action;
        }, $actions);
    }
}
