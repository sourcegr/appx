<?php
    declare(strict_types=1);

    namespace Sourcegr\Framework\Database;


    use Sourcegr\Framework\Database\QueryBuilder\DB;
    use Sourcegr\Framework\Database\QueryBuilder\QueryBuilder;
    use stdClass;


    class BaseEntity
    {
        protected static $jsonColumns = [];
        protected static $table = 'UNINITIALIZED';
        protected static $idField = 'id';
        protected static $softDeletes = false;

        protected $data;
        /**
         * @var DB
         */
        protected $DB;


        public function __construct()
        {
            $this->data = new StdClass();
        }


        public static function all($fieldsToLoad='*')
        {
            $db = app()->container->make(DB::class);
            $q = $db->Table(static::$table);

            if (static::$softDeletes) {
                $q->whereNull('deleted_at');
            }

            $res = $q->select($fieldsToLoad);

            $all = [];
            foreach ($res as $aRes) {
                $instance = new static();
                $instance->data = $aRes;
                $instance->prepareFromDB();
                $all[] = (array)$instance->data;
            }
            return new BaseCollection($all);
        }


        public static function find($id, $fieldsToLoad = null)
        {
            $a = new static();
            $a->setId($id);
            $a->load($fieldsToLoad);
            return $a;
        }



        public function save() {
            $dataToUpdate = $this->getDataForDB();
            $id = $this->QB()->insert($dataToUpdate);
            $this->setId($id);
            return $this;
        }


        public function update($dataToUpdate = null) {
            if (!$dataToUpdate) {
                $dataToUpdate = $this->getDataForDB();
                unset($dataToUpdate['id']);
            }

            $this->QB()->where('id', $this->data->id)->update($dataToUpdate);
            return $this;
        }

        /*
         *
         *
         *
         *
         *
         *
         *
         *
         *
         *
         *
         *
         *
         *
         *
         *
         *
         * protected functions
         *
         */



        protected function load($fields = '*')
        {
            $id = +$this->data->id;
            $this->data = $this->QB()->where('id', $id)->select($fields);
            $this->setId($id);
            $this->prepareFromDB();

            return $this;
        }

        protected function setId($id) {
            $this->id = $this->data->id = +$id;
            return $this;
        }

        protected function getDataForDB()
        {
            $data = (array)$this->data;

            foreach (static::$jsonColumns as $col) {
                if (array_key_exists($col, $data)) $this->data[$col] = json_encode($this->data[$col], JSON_UNESCAPED_UNICODE);
            }

            return $data;
        }


        protected function prepareFromDB()
        {
            if (is_array($this->data)) {
                $this->data = (object)$this->data;
            }

            foreach (static::$jsonColumns as $col) {
                if (property_exists($this->data, $col)) $this->data->$col = json_decode($this->data->$col);
            }
            return $this;
        }




        /**
         * @return mixed
         * @return QueryBuilder
         * @throws \Sourcegr\Framework\App\Container\BindingResolutionException
         * @throws \ReflectionException
         */
        protected function QB()
        {
            if (!$this->DB) {
                $this->DB = app()->container->make(DB::class);
            }

            return $this->DB->Table(static::$table);
        }
    }