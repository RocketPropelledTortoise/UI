<?php
/**
 * Created by IntelliJ IDEA.
 * User: onigoetz
 * Date: 02.03.14
 * Time: 21:53
 */

namespace Rocket\UI\Taxonomy;


use Rocket\Taxonomy\Model\TermContent;

class Taxonomy
{

    /**
     * Set the terms to a content, removes the old ones
     *
     * @param integer $content_id
     * @param array $terms
     * @param integer $vocabulary_id
     */
    public function setTermsForContent($content_id, $terms, $vocabulary_id = null)
    {
        $this->removeTermsFromContent($content_id, $vocabulary_id);

        foreach ($terms as $term_id) {
            $content = new TermContent();
            $content->content_id = $content_id;
            $content->term_id = $term_id;
            $content->save();
        }

        //recache the terms
        $this->cacheTermsForContent($content_id);
    }

    /**
     * Removes terms specified by a vocabulary, or all
     *
     * @param integer $content_id
     * @param integer $vocabulary_id
     */
    protected function removeTermsFromContent($content_id, $vocabulary_id = null)
    {
        if ($vocabulary_id == null) {
            TermContent::where('content_id', $content_id)->delete();
            return;
        }

        $results = TermContent::with('term')
            ->where('content_id', $content_id)
            ->where('vocabulary_id', $vocabulary_id)
            ->lists('id');

        if (count($results)) {
            $terms = array();
            foreach ($results as $term) {
                $terms[] = $term->id;
            }
            TermContent::whereIn('term_id', $terms)->where('content_id', $content_id)->delete();
        }
    }

    /**
     * Get a tree of all the data in the vocabulary, use with caution
     *
     * @param  integer $vocabulary_id
     * @return string
     */
    public function getTree($vocabulary_id)
    {
        $query = TermModel::select(
            array(
                'terms.id',
                'terms.subcat',
                'words.language_id',
                'words.text',
                'terms.term_id as parent_id',
                'terms.vocabulary_id'
            )
        )
            ->join('words', 'terms.id', '=', 'words.term_id');

        if (func_num_args() > 1) {
            $vids = func_get_args();
            $query->whereIn('vocabulary_id', $vids);
        } else {
            $query->where('vocabulary_id', $vocabulary_id);
        }

        $terms = $query->get();

        $translations = array();
        foreach ($terms as $t) {
            $translations[$t->id][$t->language_id] = $t;
        }
        unset($terms);

        $table_data = array();
        foreach ($translations as $langs) {
            $f = array_slice($langs, 0, 1);
            $f = $f[0];

            $row = array(
                'id' => $f->id,
                'parent_id' => $f->parent_id,
                'text' => $f->text,
                'vid' => $f->vocabulary_id
            );

            if ($this->isTranslatable($vocabulary_id)) {
                foreach (I18N::languages() as $lang => $d) {
                    if (array_key_exists($d['id'], $langs)) {
                        $content = $langs[$d['id']]->text;
                    } else {
                        $content = '<span class="not_translated" title="' . $f->id . '">Not Translated</span>';
                    }

                    $row['data'][$lang] =
                        (($langs[1]->subcat) ? '<strong>' : '') .
                        $content .
                        (($langs[1]->subcat) ? '</strong>' : '');

                }
            } else {
                $row['data'][I18N::languages(1, 'iso')] =
                    (($langs[1]->subcat) ? '<strong>' : '') .
                    $langs[1]->text .
                    (($langs[1]->subcat) ? '</strong>' : '');
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
     * Search a specific term, if it doesn't exist
     *
     * @param  string $term
     * @param  int $vocabulary_id
     * @param  int $language_id
     * @param  array $exclude
     * @return mixed
     */
    public function searchTerm($term, $vocabulary_id, $language_id = null, $exclude = array())
    {
        $language_id = 1;
        if ($this->isTranslatable($vocabulary_id) && $language_id == null) {
            $l_iso = Session::get('language');
            $language_id = I18N::languages($l_iso)['id'];
        }

        $term = trim($term);

        if ($term == '') {
            return false;
        }

        $query = DB::table('words');

        if (count($exclude)) {
            $query->whereNotIn('terms.id', $exclude);
        }

        $row = $query->select('terms.id')
            ->join('terms', 'terms.id', '=', 'words.term_id')
            ->where('terms.vocabulary_id', $vocabulary_id)
            ->where('words.language_id', $language_id)
            ->where('words.text', $term)
            ->first();

        if (!empty($row)) {
            return $row->id;
        }

        return false;
    }

    /**
     * Returns the id of a term, if it doesn't exist, creates it.
     *
     * @param $term
     * @param  int $vocabulary_id
     * @param  int $language_id
     * @param  int $parent_id
     * @return bool|int|mixed
     */
    public function getTermId($term, $vocabulary_id, $language_id = 0, $parent_id = 0)
    {
        $term = trim($term);

        if ($term == '') {
            return false;
        }

        $language_id = 1;
        if ($this->isTranslatable($vocabulary_id) && $language_id === 0) {
            $l_iso = Session::get('language');
            $language_id = I18N::languages($l_iso)['id'];
        }

        $search = $this->searchTerm($term, $vocabulary_id, $language_id);

        if ($search !== false) {
            return $search;
        }

        //add term
        $terms = array(
            'vocabulary_id' => $vocabulary_id,
        );

        if ($parent_id !== 0) {
            $terms['term_id'] = $parent_id;
        }
        $term_id = DB::table('terms')->insertGetId($terms);

        //add translations
        $word = array(
            'language_id' => $language_id,
            'term_id' => $term_id,
            'text' => $term,
        );

        DB::table('words')->insert($word);

        //generate cache files
        $this->cacheTerm($term_id);

        //return it
        return $term_id;
    }

    /**
     * Adds one or more tags and returns an array of id's
     *
     * @param  array $taxonomies
     * @return array
     */
    public function getTermIds($taxonomies)
    {
        $tags = array();
        foreach ($taxonomies as $voc => $terms) {

            $vocabulary_id = $this->vocabulary($voc);
            if (!is_array($terms)) {
                if (strpos($terms, ',') !== false) {
                    $exploded = explode(',', $terms);
                } else {
                    $exploded = array($terms);
                }
            } else {
                $exploded = $terms;
            }

            foreach ($exploded as $term) {

                $result = $this->getTermId($term, $vocabulary_id);
                if ($result) {
                    $tags[] = $result;
                }
            }
        }

        return $tags;
    }

    /**
     * Get the values for a select form element
     *
     * @param  string|int $vid
     * @return array
     */
    public function forSelectField($vid)
    {
        //get vid
        if (!is_numeric($vid)) {
            $vid = $this->vocabulary($vid);
        }

        //get terms
        $tids = $this->getTermsForVocabulary($vid);

        $terms = array();
        foreach ($tids as $tid) {
            $term = $this->getTerm($tid);
            $terms[$term['term_id']] = ucfirst($term['text']);
        }

        //sort
        natsort($terms);

        //return
        return $terms;
    }
}
