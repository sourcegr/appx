<?php
    declare(strict_types=1);

    namespace Sourcegr\Framework\Database;


    use ArrayAccess;


    class BaseCollection implements ArrayAccess
    {
        protected $data;


        public function __construct($data)
        {
            $this->data = $data;
        }

        public static function from($data) {
            return new static($data);
        }

        public function makeTree($parentId=null, $leafName='children', $idCol='id', $parentIdCol='parent_id')
        {
            $nodes = static::filterCollectionByKey($parentIdCol, $parentId);


            foreach ($nodes as $key=>$node) {
                $nodes[$key][$leafName] = static::makeTree($node[$idCol], $leafName, $idCol, $parentIdCol);
            }
            return $nodes;
        }


        public function filterCollectionByKey($key, $value)
        {
            $result = [];
            foreach ($this->data as $row) {
                if ($row[$key] == $value) {
                    $result[] = $row;
                }
            }
            return $result;
        }


        public function gather($key)
        {
            $gathered = [];

            foreach ($this->data as $item) {
                $gathered[] = ((array)$item)[$key] ?? null;
            }
            return $gathered;
        }
        public function keyByAndGather($keyToKeyBy, $keyToGet)
        {
            $gathered = [];
            foreach ($this->data as $item) {
                $gathered[$item[$keyToKeyBy]] = $item[$keyToGet] ?? null;
            }
            return $gathered;
        }



        public function keyBy($key)
        {
            $gathered = [];

            $counter = 0;
            foreach ($this->data as $item) {
                $localKey = ((array)$item)[$key] ?? $counter++;
                $gathered[$localKey] = $item;
            }
            return $gathered;
        }










        public function offsetExists($offset)
        {
            return isset($this->data[$offset]);
        }


        public function offsetGet($offset)
        {
            return isset($this->data[$offset]) ? $this->data[$offset] : null;
        }


        public function offsetSet($offset, $value)
        {
            if (is_null($offset)) {
                $this->data[] = $value;
            } else {
                $this->data[$offset] = $value;
            }
        }


        public function offsetUnset($offset)
        {
            unset($this->data[$offset]);
        }


        public function getData()
        {
            return $this->data;
        }
    }