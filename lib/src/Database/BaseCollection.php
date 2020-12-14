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


        public function gather($key)
        {
            $gathered = [];

            foreach ($this->data as $item) {
                $gathered[] = ((array)$item)[$key] ?? null;
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