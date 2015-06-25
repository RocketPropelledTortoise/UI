<?php

/**
 * Generate a Taxonomy to a Tree Table
 */

namespace Rocket\UI\Taxonomy;

use Rocket\Taxonomy\Support\Laravel5\Facade as T;
use Rocket\Translation\Support\Laravel5\Facade as I18N;

/**
 * Class TreeTable
 * @package Taxonomy
 */
class TreeTable
{
    /**
     * Render a tree table
     *
     * @param array $tree
     * @param integer $vid
     */
    public static function render($tree, $vid, $short = false)
    {
        $rows = self::node($tree, $vid, 0, $short);

        $heads = [];
        if (!$short) {
            $heads = array();
            if (T::isTranslatable($vid)) {
                foreach (I18N::languages() as $lang) {
                    $heads[] = t($lang['name'], array(), 'languages');
                }
            } else {
                $heads[] = t('Mot', [], 'rocket/taxonomy');
            }
            $heads[] = t('ID', [], 'rocket/taxonomy');
            $heads[] = t('Action', [], 'rocket/taxonomy');
        }

        //TODO :: externalize table
        echo \Table::quick($heads, $rows, array('id' => 'sortable'));
    }

    /**
     * Renders the rows of the tree table
     *
     * @param array $tree
     * @param int $vid
     * @param int $parent
     * @return array
     */
    public static function node($tree, $vid, $parent = 0, $short = false)
    {
        $rows = array();

        foreach ($tree as $node) {

            $row = ($short)? [self::getTitle($node)] : self::getRow($node, $vid);

            if ($parent != null) {
                $rows[] = array('data' => $row, 'id' => 'n-'.$node['id'], 'class'  => 'child-of-n-'.$parent);
            } else {
                $rows[] = array('data' => $row, 'id' => 'n-'.$node['id']);
            }

            if (!empty($node['childs'])) {
                $r = self::node($node['childs'], $vid, $node['id'], $short);
                $rows = array_merge($rows, $r);
            }
        }

        return $rows;
    }

    public static function getTitle($node)
    {
        return '<span class="icon-taxonomy-'.T::vocabulary($node['vid']).'">&nbsp;</span>' . $node['data'][I18N::languages(1, 'iso')];
    }

    public static function getRow($node, $vid)
    {
        $row = [];

        if (\Taxonomy::isTranslatable($vid)) {
            foreach (I18N::languages() as $lang => $d) {
                if ($d['id'] == 1) {
                    $row[] = '<span class="icon-taxonomy-'.T::vocabulary($node['vid']).'">&nbsp;</span>' . $node['data'][$lang];
                } else {
                    $row[] = $node['data'][$lang];
                }
            }
        } else {
            $row[] = self::getTitle($node);
        }

        $row[] = $node['id'];

        if (empty($node['childs']) or $node['id'] != 0) {
            $row[] = anchor('admin/taxonomy/term_refresh/' . $node['id'], icon('refresh')) .
                anchor_modal('admin/taxonomy/term_edit/' . $node['id'], icon('pencil')) .
                anchor_modal('admin/taxonomy/term_delete/' . $node['id'], icon('bin'));
        } else {
            $row[] = '&nbsp;';
        }

        return $row;
    }
}
