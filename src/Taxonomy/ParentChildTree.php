<?php namespace Rocket\UI\Taxonomy;

/**
 * Class to manage trees with "parent_id" columns
 */
class ParentChildTree
{
    /**
     * @var array The tree itself
     */
    public $tree;

    /**
     * @var array The flat tree to find elements
     */
    public $finder;

    /**
     * @var array Configuration values
     */
    public $config = [
        'id' => 'id',
        'childs' => 'childs',
        'parent' => 'parent_id',
        'default_parent' => [null, ''],
        'create_root' => false,
        'default_root' => [],
        'default_root_id' => 0,
    ];

    /**
     * Generate the tree
     *
     * @param array $tree_data The raw data to create a tree from
     * @param array $config The configuration on how to create this tree
     *
     * @throws \Exception
     */
    public function __construct($tree_data, $config = [])
    {
        //configure default vars
        $this->config = array_merge($this->config, $config);

        $this->tree = [];
        $this->finder = [];

        if ($this->config['create_root']) {
            $this->tree[$this->config['default_root_id']] = $this->config['default_root'];
            $this->finder[$this->config['default_root_id']] = &$this->tree[$this->config['default_root_id']];
        }

        $this->buildTree($tree_data);
    }

    /**
     * Build the tree from the received data
     *
     * @param array $tree_data The raw data to create a tree from
     * @throws \Exception
     */
    protected function buildTree($tree_data)
    {
        $parent_key = $this->config['parent'];
        $default_root_id = $this->config['default_root_id'];

        while (count($tree_data)) {
            $beginning_with = count($tree_data);

            foreach ($tree_data as $node_id => $node) {
                $node[$this->config['childs']] = [];
                $parent = (array_key_exists($parent_key, $node)) ? $node[$parent_key] : $default_root_id;
                if ($this->add($parent, $node[$this->config['id']], $node)) {
                    unset($tree_data[$node_id]);
                }
            }

            $this->ungracefulExit($beginning_with, $tree_data);
        }
    }

    /**
     * Exit the tree creation if the tree can't be built completely
     *
     * @param int $beginning_with The number of nodes left to place on the tree
     * @param array $tree_data The rest of the tree data to place
     * @throws \Exception
     */
    protected function ungracefulExit($beginning_with, $tree_data)
    {
        if ($beginning_with == count($tree_data)) {
            throw new \Exception('This tree has some missing parent items: ' . print_r($tree_data, true));
        }
    }

    /**
     * Add a leaf on the tree.
     *
     * @param  string $parent_id
     * @param  string $node_id
     * @param  array $node
     * @return bool
     */
    public function add($parent_id, $node_id, $node)
    {
        //is it a root ?
        if (in_array($parent_id, $this->config['default_parent'])) {
            if (!$this->config['create_root']) {
                $this->tree[$node_id] = $node;
                $this->finder[$node_id] = &$this->tree[$node_id];

                return true;
            }

            $node[$this->config['parent']] = $this->config['default_root_id'];
            $parent_id = $this->config['default_root_id'];
        }

        //is it in the finder ?
        if (array_key_exists($parent_id, $this->finder)) {
            $this->finder[$parent_id][$this->config['childs']][$node_id] = $node;
            $this->finder[$node_id] = &$this->finder[$parent_id][$this->config['childs']][$node_id];

            return true;
        }

        //could'nt find anything
        return false;
    }

    /**
     * Get the prepared tree.
     *
     * @return array
     */
    public function getTree()
    {
        return $this->tree;
    }

    /**
     * Get all node's children.
     *
     * @param  string $id The entry's ID
     * @return array
     */
    public function getChilds($id)
    {
        $result = [];
        if (array_key_exists($id, $this->finder)) {
            return $this->recursiveGetChilds($this->finder[$id][$this->config['childs']], $result);
        } else {
            return $result;
        }
    }

    /**
     * Internal recursive function to get children.
     *
     * @param  array $childs
     * @param  array $result
     * @return array
     */
    private function recursiveGetChilds($childs, $result)
    {
        foreach ($childs as $node) {
            $result[] = $node[$this->config['id']];
            $result = $this->recursiveGetChilds($node[$this->config['childs']], $result);
        }

        return $result;
    }

    /**
     * Sort the tree
     * @param mixed $key
     */
    public function sort($key)
    {
        $this->config['sort_key'] = $key;
        $this->recursiveSort($this->tree);
    }

    /**
     * Internal recursive function
     * @param  array $tree
     * @return bool
     */
    private function recursiveSort(&$tree)
    {
        //execute sort
        usort(
            $tree,
            function ($a, $b) {
                if ($a[$this->config['sort_key']] == $b[$this->config['sort_key']]) {
                    return 0;
                }

                return ($a[$this->config['sort_key']] < $b[$this->config['sort_key']]) ? -1 : 1;
            }
        );

        foreach ($tree as &$t) {
            if (array_key_exists($this->config['childs'], $t) && $t[$this->config['childs']] != '') {
                $t[$this->config['childs']] = $this->recursiveSort($t[$this->config['childs']]);
            }
        }

        return $tree;
    }
}
