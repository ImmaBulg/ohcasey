<?php

namespace App\Models;

trait Tree
{
    public function buildTree(array &$elements, $parentId = 0)
    {
        $branch = array();

        foreach ($elements as $element) {
            if ($element['parent'] == $parentId) {
                $children = $this->buildTree($elements, $element['id']);
                if ($children) {
                    $element['children'] = $children;
                }
                array_push($branch, $element);
                unset($element);
            }
        }

        return $branch;
    }
}
