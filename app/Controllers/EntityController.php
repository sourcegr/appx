<?php

    declare(strict_types=1);


    namespace App\Controllers;


    use Sourcegr\Framework\Database\QueryBuilder\DB;
    use Sourcegr\Framework\Database\QueryBuilder\QueryBuilder;

    class EntityController
    {
        protected $_db;
        protected $table = 'temp';


        public static function hasMany(
            string $relationName,
            array $master,
            $slave,
            string $masterKey,
            string $slaveKey,
            $fields = '*'
        ) {
            // bail if this is an ampty result
            if (!count($master)) {
                return [];
            }

            // if its a QueryBuilder, then we prob/ly have a where or something
            $slaveDB = $slave instanceof QueryBuilder ? $slave : $slave->DB();


            // this is not an array, its probably a simple object
            if (!($master[0] ?? null)) {
                $master[$relationName] = $slaveDB->where($slaveKey, $master[$masterKey])->select($fields);
                return $master;
            }


            // only one item, so no array_column/joins here
            if (count($master) === 1) {
                $ent = $master[0];
                $ent[$relationName] = $slaveDB->where($slaveKey, $ent[$masterKey])->select($fields);
                return [$ent];
            }


            // ok this is an array, do the full

            $slaveResults = $slaveDB->whereIn($slaveKey, array_column($master, $masterKey))->select($fields);

            $keyed = [];

            foreach ($slaveResults as $ent) {
                $keyed[$ent[$slaveKey]][] = $ent;
            }

            $all = [];
            foreach ($master as $ent) {
                $ent[$relationName] = $keyed[$ent[$masterKey]] ?? [];
                $all[] = $ent;
            }

            return $all;
        }


        public function __construct(DB $db, $table)
        {
            $this->_db = $db;
            $this->table = $table;
        }


        public function DB()
        {
            return $this->_db->Table($this->table);
        }


        public function getNextZindex($col = null, $val = null)
        {
            $q = $this->DB();
            if ($col) {
                $q->where($col, $val);
            }
            return $q->max('zindex') + 10;
        }


        public function getValue($res)
        {
            return array_values($res[0])[0];
        }


        public function getValues($res)
        {
            return array_values($res[0]);
        }


        public function getObject($res)
        {
            return (object)$res[0];
        }


        public function find($id)
        {
            return $this->DB()->where('id', $id)->first();
        }

        public function exists($col, $val = null)
        {
            if ($val === null) {
                $val = $col;
                $col = 'id';
            }
            return $this->DB()->where($col, $val)->count();
        }

        public static function keyBy($collection, $keyField)
        {
            $res = [];
            foreach ($collection as $item) {
                $res[$item[$keyField]] = $item;
            }
            return $res;
        }

        public static function keyFieldBy($collection, $keyField, $column)
        {
            $res = [];
            foreach ($collection as $item) {
                $res[$item[$keyField]] = $item[$column];
            }
            return $res;
        }

        public static function arrayFrom($collection, string $column)
        {
            return array_column($collection, $column);
        }

        public static function getEntityByCol($entity, $col, $val, $fields = '*')
        {
            return $entity->where($col, $val)->select($fields);
        }

        public static function getEntityByColCount($entity, $col, $val)
        {
            return $entity->where($col, $val)->count();
        }

        public static function hasKey($collection, $col, $val)
        {
            return in_array($val, static::arrayFrom($collection, $col));
        }
    }