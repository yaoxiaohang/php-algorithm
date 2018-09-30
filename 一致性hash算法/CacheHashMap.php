<?php
/**
 * 一致性hash算法
 * User: yaoxiaohang
 * Date: 2018/9/29
 * Time: 17:24
 */

class CacheHashMap
{
    private $_nodeData = [];
    private $_nodeKeys = [];
    private $_virtualNodeNum = 300;
    private static $cacheObj = null;

    public function __construct($nodes = [])
    {
        foreach ($nodes as $value) {
            $this->addNode($value, false);
        }
        sort($this->_nodeKeys);
    }

    /**
     * @return null
     */
    public static function getInstance($config = [])
    {
        if (!is_object(self::$cacheObj)) {
            self::$cacheObj = new static($config);
        }
        return self::$cacheObj;
    }

    /**
     * 增加节点
     * @param $node
     */
    public function addNode($node, $isSort = true)
    {
        for ($i = 0; $i < $this->_virtualNodeNum; $i++) {
            $keyNum = sprintf("%u", crc32($node . '_' . $i));
            $this->_nodeData[$keyNum] = $node . '_' . $i;
            $this->_nodeKeys[] = $keyNum;
        }
        $isSort && sort($this->_nodeKeys);
    }

    /**
     * @param $key
     * @return mixed
     */
    public function getNodeByKey($key)
    {
        $nodeKey = sprintf("%u", crc32($key));
        $node = $this->_findNodeByKey($nodeKey);
        list($trueNode, $num) = explode('_', $node);
        return $trueNode;
    }


    private function _findNodeByKey($key, $startIndex = 0, $endIndex = 0)
    {
        if ($endIndex == 0) {
            $endIndex = count($this->_nodeKeys) - 1;
            if ($key <= $this->_nodeKeys[0] || $key > $this->_nodeKeys[$endIndex]) {
                return $this->_nodeData[$this->_nodeKeys[0]];
            }
        }

        if ($endIndex - $startIndex == 1) {
            return $this->_nodeData[$this->_nodeKeys[$endIndex]];
        }
        $halfIndex = ceil(($endIndex - $startIndex) / 2) + $startIndex;

        if ($this->_nodeKeys[$halfIndex] == $key) {
            return $this->_nodeData[$this->_nodeKeys[$halfIndex]];
        }

        if ($this->_nodeKeys[$halfIndex] > $key) {
            return $this->_findNodeByKey($key, $startIndex, $halfIndex);
        } else {
            return $this->_findNodeByKey($key, $halfIndex, $endIndex);
        }
    }
}