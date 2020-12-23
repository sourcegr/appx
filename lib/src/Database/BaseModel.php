<?php
    declare(strict_types=1);

    namespace Sourcegr\Framework\Database;


    use Exception;
    use Sourcegr\Framework\Database\QueryBuilder\DB;
    use Sourcegr\Framework\Database\QueryBuilder\QueryBuilder;
    use Sourcegr\Framework\Database\QueryBuilder\Raw;
    use Sourcegr\Framework\Http\BoomException;
    use Sourcegr\Framework\Http\Request\RequestInterface;
    use Sourcegr\Framework\Http\Response\HTTPResponseCode;
    use stdClass;


    class BaseModel
    {
        protected static array $typeCasts = [];
        protected static string $table = 'UNINITIALIZED';
        protected static string $idField = 'id';
        protected static bool $softDeletes = false;
        protected static bool $createdBy = false;
        protected static bool $updatedBy = false;
        protected static bool $updatedAt = false;
        public int $id = 0;
        public $data;
        /**
         * @var DB $DB
         */
        protected $DB;

        public function getTableName() {
            return $this::$table;
        }

        /**
         * BaseModel constructor.
         * @param string|int $id
         * @throws \ReflectionException
         * @throws \Sourcegr\Framework\App\Container\BindingResolutionException
         */
        public function __construct($id = 0)
        {
            $this->data = new StdClass();
            $this->setId(+$id);
        }


        /**
         * @param string $fieldsToLoad
         * @return BaseCollection
         * @throws \ReflectionException
         * @throws \Sourcegr\Framework\App\Container\BindingResolutionException
         */
        public static function all($fieldsToLoad = '*')
        {
            return static::allWhere(null, $fieldsToLoad);
        }


        /**
         * @param object|null $whereClause
         * @param array|null|string $fieldsToLoad
         * @return BaseCollection
         * @throws \ReflectionException
         * @throws \Sourcegr\Framework\App\Container\BindingResolutionException
         */
        protected static function allWhere($whereClause = null, $fieldsToLoad = '*')
        {
            $qb = static::getQueryBuilder();

            if ($whereClause) {
                $qb->where($whereClause);
            }

            $res = $qb->select($fieldsToLoad);

            $all = [];
            foreach ($res as $aRes) {
                $instance = new static();
                $instance->data = $instance->convertArrayDataToModelObjectData($aRes);
                $all[] = (array)$instance->data;
            }
            return new BaseCollection($all);
        }


        /**
         * @param int|string $id id to retrieve
         * @param array|null $fieldsToLoad Fields to load. If it is not provided, loads all fields
         * @return static|null The model instance
         * @throws \ReflectionException
         * @throws \Sourcegr\Framework\App\Container\BindingResolutionException
         */
        public static function find($id, $fieldsToLoad = null)
        {
            $a = new static();
            $a->setId(+$id);
            return $a->load($fieldsToLoad);
        }


        /**
         * @param int|string $id id to retrieve
         * @param array|null $fieldsToLoad Fields to load. If it is not provided, loads all fields
         * @return static The model instance
         * @throws BoomException if the model is not found
         * @throws \ReflectionException
         * @throws \Sourcegr\Framework\App\Container\BindingResolutionException
         */
        public static function findOrFail($id, $fieldsToLoad = null)
        {
            $res = static::find(+$id, $fieldsToLoad);

            if ($res == null) {
                throw BoomException::Http(HTTPResponseCode::HTTP_NOT_FOUND);
            }
            return $res;
        }


        /**
         * @return QueryBuilder
         * @throws \ReflectionException
         * @throws \Sourcegr\Framework\App\Container\BindingResolutionException
         */
        public static function getQueryBuilder()
        {
            $db = app()->container->make(DB::class);
            $q = $db->Table(static::$table);
            if (static::$softDeletes) {
                $q->wrapIn('deleted_at IS NULL');
            }
            return $q;
        }


        /**
         * saves the model to the DB
         *
         * @param null $force Forces insertion even in update mode
         * @return static
         * @throws Exception
         * @throws \ReflectionException
         * @throws \Sourcegr\Framework\App\Container\BindingResolutionException
         */
        public function save($force = null)
        {
            $dataToUpdate = $this->convertModelObjectDataToArray();

            // prevent 0s as IDs
            if (isset($dataToUpdate['id']) && $dataToUpdate['id'] == 0) {
                unset($dataToUpdate['id']);
            }

            // if an ID is present, throw exception unless it is forced
            if ($force === null && ($dataToUpdate['id'] ?? 0) > 0) {
                throw new Exception('PLEASE USE UPDATE TO UPDATE A RECORD');
            }

            if (static::$createdBy) {
                $dataToUpdate['created_by'] = $this->getCurrentUserId();
            }

            $id = $this->QB()->insert($dataToUpdate);
            $this->setId($id);
            return $this;
        }


        /**
         * updates the DB with the  provided data, or with the models data
         *
         * @param ?array $dataToUpdate if provided, ti must be a ready to be inserted key=>value array
         * @return static
         * @throws QueryBuilder\Exceptions\UpdateErrorException
         * @throws \ReflectionException
         * @throws \Sourcegr\Framework\App\Container\BindingResolutionException
         */
        public function update($dataToUpdate = null)
        {
            if ($dataToUpdate) {
                if (!is_array($dataToUpdate)) {
                    throw new Exception('Provided data should be an associated array ready to be inserted int the DB');
                }

                $this->hydrateModelWith($dataToUpdate);
            }

            $data = $this->data;
            unset($data->id);

            $data = $this->convertModelObjectDataToArray($data);

            if (static::$updatedAt) {
                $data['updated_at'] = Raw::now();
            }

            if (static::$updatedBy) {
                $data['updated_by'] = $this->getCurrentUserId();
            }

            $this->QB()->where('id', $this->id)->update($data);
            $this->setId($this->id);

            return $this;
        }


        /**
         * quick save/update replacement
         *
         * @return static
         * @throws \ReflectionException
         * @throws \Sourcegr\Framework\App\Container\BindingResolutionException
         * @throws \Sourcegr\Framework\Database\QueryBuilder\Exceptions\UpdateErrorException
         */
        public function persist()
        {
            return (+($this->id) == 0) ?
                $this->save() :
                $this->update();
        }


        /**
         * deletes the model from the DB, respecting any soft deletes
         * @return static
         * @throws \Sourcegr\Framework\Database\QueryBuilder\Exceptions\UpdateErrorException
         * @throws \ReflectionException
         * @throws \Sourcegr\Framework\App\Container\BindingResolutionException
         */
        public function delete()
        {
            if (static::$softDeletes) {
                $this->QB()->where('id', $this->id)->update('deleted_at', Raw::now());
                $this->id = 0;
                $this->data->id = 0;

                return $this;
            }

            return $this->dangerouslyDeleteCompletely();
        }


        /**
         * hard delete model from the DB. Does not respect any soft deletes
         * @return $this
         * @throws \ReflectionException
         * @throws \Sourcegr\Framework\App\Container\BindingResolutionException
         */
        public function dangerouslyDeleteCompletely()
        {
            $this->QB()->where('id', $this->data->id)->delete();
            $this->id = 0;
            $this->data->id = 0;
            return $this;
        }


        /**
         * returns an array that contains only the $columnsToGet columns
         *
         * @param string|array $columnsToGet string with comma separated keys, or an array with the keys
         * @return array
         */
        public function filterData($columnsToGet)
        {
            if (!is_array($columnsToGet)) {
                $columnsToGet = explode(',', str_replace(' ', '', $columnsToGet));
            }

            $o = [];
            foreach ($columnsToGet as $key) {
                if ($v = $this->data->$key) {
                    $o[$key] = $v;
                }
            }
            return $o;
        }


        /**
         * hydrates the data property of the model with provided values, ensuring all castings are performed
         *
         * @param array $input
         * @return static
         * @throws Exception
         */
        public function hydrateModelWith(array $input)
        {
            if (defined('static::ALL_FIELDS')) {
                foreach (static::ALL_FIELDS as $field) {
                    $cast = static::$typeCasts[$field] ?? null;

                    if (!array_key_exists($field, $input)) {
                        continue;
                    }

                    if ($cast == null) {
                        $this->data->$field = $input[$field];
                    } else {
                        $this->data->$field = $this->castTo($cast, $input[$field]);
                    }
                }
                return $this;
            }

            foreach ($input as $field => $value) {
                if ($field == static::$idField) {
                    continue;
                }

                $cast = static::$typeCasts[$field] ?? null;
                if ($cast == null) {
                    $this->data->$field = $value;
                }else{
                    $this->data->$field = $this->castTo($field, $value);
                }
            }
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

        protected function getCurrentUserId()
        {
            $r = app()->container->make(RequestInterface::class);
            $u = $r->user ?? null;

            if ($u) {
                return $u->id;
            } else {
                return 0;
            }
        }


        /**
         * sets the ID for the model
         * @param $id
         * @return $this
         * @internal
         *
         */
        protected function setId($id)
        {
            $this->id = $this->data->id = +$id;
            return $this;
        }


        /**
         * Loads data from the DB into a model
         *
         * @param string|array|null $fields to select
         * @return static | null
         * @throws \ReflectionException
         * @throws \Sourcegr\Framework\App\Container\BindingResolutionException
         */
        protected function load($fields = '*')
        {
            $id = +$this->id;
            $q = $this->QB()->where('id', $id);

            $res = $q->first($fields);

            if ($res == null) {
                return null;
            }

            $this->data = $this->convertArrayDataToModelObjectData($res);
            $this->setId($id);

            return $this;
        }


        /**
         * @return array
         * @deprecated
         */
        protected function getDataForDB()
        {
            $data = (array)$this->data;

            foreach ($data as $field => $value) {
                $cast = static::$typeCasts[$field] ?? null;
                if ($cast === null) {
                    continue;
                }

                $data[$field] = $this->castForDB($cast, $value);
            }

            return $data;
        }


        /**
         * converts the given object (or the objects data) to array for DB insertion
         *
         * @param object | null $data
         * @return array
         */
        protected function convertModelObjectDataToArray($data = null)
        {
            if ($data === null) {
                $data = $this->data;
            }
            $data = (array)$data;

            foreach ($data as $field => $value) {
                $cast = static::$typeCasts[$field] ?? null;
                if ($cast === null) {
                    continue;
                }
                $data[$field] = $this->castForDB($cast, $value);
            }

            return $data;
        }


        /**
         * converts the given array to object. The result should populate the model data
         * @param null $data
         * @return object
         */
        protected function convertArrayDataToModelObjectData($data)
        {
            if (is_object($data)) {
                $data = (array)$data;
            }
            if (!is_array($data)) {
                throw new Exception("convertArrayDataToModelObjectData accepts an Array or an Object. " . get_class($this));
            }
            $returning = new StdClass();

            foreach ($data as $field => $value) {
                $cast = static::$typeCasts[$field] ?? null;

                $returning->$field = $cast !== null ?
                    $this->castTo($cast, $value) :
                    $value;
            }

            return $returning;
        }


        protected function castTo(string $castType, $value)
        {
            if ($castType === 'json') {
                return json_decode($value);
            }
            if ($castType === 'number') {
                $value = "0$value";
                return (int)$value;
            }

            if ($castType === 'string') {
                return (string)$value;
            }

            if ($castType === 'boolean') {
                if ($value == 1 || $value === true || $value === "true" || $value === 't') {
                    return true;
                }
                if ($value == 0 || $value === false || $value === "false" || $value === 'f') {
                    return false;
                }
                throw new Exception('Unknown Boolean value: ' . $castType . ' in ' . get_class($this) . '::castTo');
            }

            throw new Exception('Unknown cast: ' . $castType . ' in ' . get_class($this) . '::castTo');
        }


        protected function castForDB(string $castType, $value)
        {
            if ($castType === 'json') {
                return json_encode($value, JSON_UNESCAPED_UNICODE);
            }

            if ($castType === 'number') {
                if ($value == '') {
                    return null;
                }

                $value = "0$value";
                return (int)$value;
            }

            if ($castType === 'string') {
                return (string)$value;
            }

            if ($castType === 'boolean') {
                if ($value == 1 || $value === true || $value === "true" || $value === 't') {
                    return SQL_TRUE;
                }
                if ($value == 0 || $value === false || $value === "false" || $value === 'f') {
                    return SQL_FALSE;
                }
                throw new Exception('Unknown Boolean value: ' . $castType . ' in ' . get_class($this) . '::castForDB');
            }

            throw new Exception('Unknown cast: ' . $castType . ' in ' . get_class($this) . '::castForDB');
        }


        /**
         * @param array $data
         * @return static
         */
        protected function mergeArrayDataToModel(array $data)
        {
            //convert to object first
            $data = $this->convertArrayDataToModelObjectData($data);

            foreach ($data as $key => $value) {
                $this->data->$key = $value;
            }

            return $this;
        }


        /**
         * @return QueryBuilder
         * @throws \Sourcegr\Framework\App\Container\BindingResolutionException
         * @throws \ReflectionException
         */
        public function QB()
        {
            if (!$this->DB) {
                $this->DB = static::getQueryBuilder();
            }

            return $this->DB;
        }
    }