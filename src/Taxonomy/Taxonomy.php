<?php
/**
 * Created by IntelliJ IDEA.
 * User: onigoetz
 * Date: 02.03.14
 * Time: 21:53
 */

namespace Rocket\UI\Taxonomy;


use Rocket\Taxonomy\Facade as T;
use Rocket\Taxonomy\Model\Hierarchy;
use Rocket\Taxonomy\Model\Term;
use Rocket\Taxonomy\Model\TermData;
use Rocket\Translation\I18NFacade as I18N;
use Rocket\Utilities\ParentChildTree;

class Taxonomy
{
    /**
     * Get a tree of all the data in the vocabulary, use with caution
     *
     * @param  array $vocabulary_id
     * @return string
     */
    public static function getTree($vocabulary_id)
    {
        $term_table = (new Term)->getTable();
        $data_table = (new TermData)->getTable();

        $terms = Term::select(
            "$term_table.id",
            "$term_table.type",
            "$term_table.vocabulary_id",
            "$data_table.language_id",
            "$data_table.title",
            "$data_table.description"
        )
            ->join($data_table, "$term_table.id", '=', 'term_id')
            ->whereIn('vocabulary_id', $vocabulary_id)
            ->get();

        $translations = array();
        foreach ($terms as $t) {
            $translations[$t->id][$t->language_id] = $t;
        }

        $hierarchy = Hierarchy::whereIn('term_id', $terms->lists('id'))->lists('parent_id','term_id');

        unset($terms);

        $table_data = array();
        foreach ($translations as $langs) {
            $f = array_slice($langs, 0, 1);
            $f = $f[0];

            $row = array(
                'id' => $f->id,
                'parent_id' => (array_key_exists($f->id, $hierarchy))? $hierarchy[$f->id] : null,
                'text' => $f->title,
                'vid' => $f->vocabulary_id
            );

            if (T::isTranslatable($vocabulary_id[0])) {
                foreach (I18N::languages() as $lang => $d) {
                    if (array_key_exists($d['id'], $langs)) {
                        $content = $langs[$d['id']]->title;
                    } else {
                        $content = '<span class="not_translated" title="' . $f->id . '">Not Translated</span>';
                    }

                    $row['data'][$lang] =
                        (($langs[1]->type == 1) ? '<strong>' : '') .
                        $content .
                        (($langs[1]->type == 1) ? '</strong>' : '');

                }
            } else {
                $row['data'][I18N::languages(1, 'iso')] =
                    (($langs[1]->type == 1) ? '<strong>' : '') .
                    $langs[1]->title .
                    (($langs[1]->type == 1) ? '</strong>' : '');
            }


            $table_data[$f->id] = $row;
        }

        unset($translations);

        $root_node = array(
            'id' => 'root',
            'parent_id' => null,
            'text' => '',
            'vid' => 'tags',
        );
        foreach (I18N::languages() as $lang => $d) {
            if ($d['id'] == 1) {
                $root_node['data'][$lang] = 'Root';
            } else {
                $root_node['data'][$lang] = '--';
            }
        }

        $tree = new ParentChildTree(
            $table_data,
            array(
                'create_root' => true,
                'default_root' => $root_node,
                'default_root_id' => 'root'
            )
        );

        return $tree;
    }

    /**
     * Get the values for a select form element
     *
     * @param  string|int $vid
     * @return array
     */
    public static function forSelectField($vid)
    {
        //get vid
        if (!is_numeric($vid)) {
            $vid = T::vocabulary($vid);
        }

        //get terms
        $tids = T::getTermsForVocabulary($vid);

        $terms = array();
        foreach ($tids as $tid) {
            $term = T::getTerm($tid);
            $terms[$term['term_id']] = ucfirst($term['text']);
        }

        //sort
        natsort($terms);

        //return
        return $terms;
    }
}
