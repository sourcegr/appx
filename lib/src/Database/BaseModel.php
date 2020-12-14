<?php
    declare(strict_types=1);

    namespace Sourcegr\Framework\Database;


    use App\Models\ScheduledTaskEntity;
    use Exception;
    use Sourcegr\Framework\Database\QueryBuilder\DB;
    use Sourcegr\Framework\Database\QueryBuilder\QueryBuilder;
    use Sourcegr\Framework\Database\QueryBuilder\Raw;
    use Sourcegr\Framework\Http\Boom;
    use Sourcegr\Framework\Http\BoomException;
    use Sourcegr\Framework\Http\Request\RequestInterface;
    use Sourcegr\Framework\Http\Response\HTTPResponseCode;
    use stdClass;


    class BaseModel
    {
        protected static array $jsonColumns = [];
        protected static string $table = 'UNINITIALIZED';
        protected static string $idField = 'id';
        protected static bool $softDeletes = false;
        protected static bool $createdBy = false;
        protected static bool $updatedBy = false;
        protected static bool $updatedAt = false;
        public int $id = 0;
        public $data;
        protected $user = null;

        /**
         * @var DB $DB
         */
        protected $DB;


        /**
         * BaseModel constructor.
         * @param string|int $id
         * @throws \ReflectionException
         * @throws \Sourcegr\Framework\App\Container\BindingResolutionException
         */
        public function __construct($id = 0)
        {
            $request = app()->container->make(RequestInterface::class);
            $this->user = $request->user;
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

            if (static::$softDeletes) {
                $qb->whereNull('deleted_at');
            }
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
         * @return static|null The model instance
         * @throws BoomException if the model is not found
         * @throws \ReflectionException
         * @throws \Sourcegr\Framework\App\Container\BindingResolutionException
         */
        public static function findOrFail($id, $fieldsToLoad = null)
        {
            $res = static::find(+$id, $fieldsToLoad);

            if ($res == null) {
                throw new BoomException(new Boom(HTTPResponseCode::HTTP_NOT_FOUND, "Item $id not found in table " . static::$table));
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
            return $db->Table(static::$table);
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
                $dataToUpdate['created_by'] = $this->user['id'];
            }

            $id = $this->QB()->insert($dataToUpdate);
            $this->setId($id);
            return $this;
        }


        /**
         * updates the DB with the given or provided data
         * @param object|null $dataToUpdate data to update. If null, the model's data are used
         * @return static
         * @throws \ReflectionException
         * @throws \Sourcegr\Framework\App\Container\BindingResolutionException
         * @throws \Sourcegr\Framework\Database\QueryBuilder\Exceptions\UpdateErrorException
         */
        public function update($dataToUpdate = null)
        {
            $data = $dataToUpdate === null ? $this->data : $dataToUpdate;

            unset($data->id);
            // prepare data for DB
            $data = $this->convertModelObjectDataToArray($data);

            if (static::$updatedAt) {
                $data['updated_at'] = Raw::now();
            }

            if (static::$updatedBy) {
                $data['updated_by'] = $this->user['id'];
            }

            $this->QB()->where('id', $this->id)->update($data);
            $this->setId($this->id);

            return $this->mergeArrayDataToModel($data);
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

        /**
         * sets the ID for the model
         * @internal
         *
         * @param $id
         * @return $this
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
            $res = $this->QB()->where('id', $id)->first($fields);

//            if (get_class($this) == ScheduledTaskEntity::class) {
//                dd($res);
//            }
            if ($res == null) {
                return null;
            }

            $this->data = $this->convertArrayDataToModelObjectData($res);
            $this->setId($id);

            return $this;
        }


        /**
         * @deprecated
         * @return array
         */
        protected function getDataForDB()
        {
            $data = (array)$this->data;

            foreach (static::$jsonColumns as $col) {
                if (array_key_exists($col, $data)) {
                    $data[$col] = json_encode($data[$col], JSON_UNESCAPED_UNICODE);
                }
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

            foreach (static::$jsonColumns as $col) {
                if (array_key_exists($col, $data)) {
                    $data[$col] = json_encode($data[$col], JSON_UNESCAPED_UNICODE);
                }
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
            if (is_array($data)) {
                $data = (object)$data;
            }

//            if (get_class($this) == ScheduledTaskEntity::class) {
//                dd($data);
//            }
            foreach (static::$jsonColumns as $col) {
                if (property_exists($data, $col)) {
                    $data->$col = json_decode($data->$col);
                }
            }
            return $data;
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