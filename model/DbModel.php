<?php

namespace NGFramer\NGFramerPHPBase\model;

use Exception;
use NGFramer\NGFramerPHPBase\utilities\UtilCommon;
use NGFramer\NGFramerPHPExceptions\exceptions\SqlBuilderException;
use NGFramer\NGFramerPHPSQLServices\Query;

abstract class DbModel extends BaseModel
{
    // Structural properties of the database.
    protected array $structure = [];

    // All the fields in the database.
    protected array $fields;

    // For insert queries.
    // Fields inserted automatically by database.
    protected array $autoFilledDb;
    // Fields inserted automatically by system.
    protected array $autoFilledSys;

    // For update queries.
    // Many at once assignable fields, can use Update.
    protected array $massFillable;
    // Once at once assignable fields, must use UpdateOne.
    protected array $singleFillable;

    // For update queries.
    // Fields updated automatically by database.
    protected array $autoUpdateDb;
    // Fields updated automatically by system.
    protected array $autoUpdateSys;


    // Property to save the instance of the class.
    protected static ?self $instance = null;


    /**
     * Function to initialize the instance of the class.
     * @return static. Returns instance of this class.
     */
    final public static function init(): static
    {
        if (self::$instance == null) {
            self::$instance = new static();
        }
        return static::$instance;
    }


    /**
     * Making this class not-instance-able.
     */
    protected function __construct()
    {
    }


    /**
     * Fetches all the data from the database with specified condition.
     * Function to operate into the structures.
     * @param array $fields . Fields to be selected should be in this format, [field1, field2, field3].
     * @param array $conditionData . Condition data should be in this format, [[field1, value1, symbol1], [field2, value2, symbol2]].
     * @return array . Returns the selected data from the database table in the form of an array. Example ['field1' => 'value1', 'field2' => 'value2']. Upto max of 25 rows.
     * @throws Exception .
     * TODO: Refine the return type for the function, refined return can be seen in the execute function.
     */
    public function select(array $fields, array $conditionData = []): array
    {
        $fields = implode(', ', $fields);
        if (empty($conditionData)) {
            return Query::table($this->structure['name'])->select($fields)->execute();
        } else {
            return Query::table($this->structure['name'])->select($fields)->where($conditionData)->execute();
        }
    }


    /**
     * @param array $insertData . Insert data should be in this format, [field1 => value1, field2 => value2].
     * @return int. Returns the lastlyInsertedId from the database table.
     * @throws Exception.
     * TODO: Refine the return type for the function, refined return can be seen in the execute function.
     */
    final public function insert(array $insertData): int
    {
        if (!$this->structure['type'] == 'table') {
            throw new Exception("Unable to insert data into a view structure.");
        } else {
            return Query::table($this->structure['name'])->insert($insertData)->execute();
        }
    }


    /**
     * Updates all the record from the database with specified condition.
     * @throws SqlBuilderException
     * @throws Exception
     * TODO: Refine the return type for the function, refined return can be seen in the execute function.
     */
    public function update(array $updateData, array $conditionData): int|bool|array
    {
        if (empty($conditionData)) {
            throw new Exception("Can't update pile of data. Provide condition to update the data set.");
        } else {
            if (!$this->structure['type'] == 'table') {
                throw new Exception("Unable to update data in a view structure.");
            } else {
                return Query::table($this->structure['name'])->update($updateData)->where($conditionData)->execute();
            }
        }
    }


    /**
     * Deletes all the record from the database with specified condition.
     * @throws SqlBuilderException
     * @throws Exception
     * TODO: Refine the return type for the function, refined return can be seen in the execute function.
     */
    public function delete(array $conditionData): int|bool|array
    {
        if (empty($conditionData)) {
            throw new Exception("Can't delete pile of data. Provide condition to delete the data set.");
        } else {
            if (!$this->structure['type'] == 'table') {
                throw new Exception("Unable to delete data in a view structure.");
            } else {
                return Query::table($this->structure['name'])->delete()->where($conditionData)->execute();
            }
        }
    }


    /**
     * @throws Exception
     */
    public function insertOne(array $insertData): int
    {
        if (UtilCommon::isAssociativeArray($insertData) or count($insertData) != 1) {
            throw new Exception("Insert data invalid. Pass data for one row only.");
        }
        else {
            return $this->insert($insertData);
        }
    }


    /**
     * Selects only one record from the database.
     * @param array $fields . Fields to be selected should be in this format, [field1, field2, field3].
     * @param array $conditionData . Condition data should be in this format, [[field1, value1, symbol1], [field2, value2, symbol2]].
     * @throws SqlBuilderException.
     * @throws Exception.
     * TODO: Refine the return type for the function, refined return can be seen in the execute function.
     */
    public function selectOne(array $fields, array $conditionData): array|bool|int
    {
        $fields = implode(', ', $fields);
        if (empty($conditionData)) {
            return Query::table($this->structure['name'])->select($fields)->limit(1)->execute();
        } else {
            return Query::table($this->structure['name'])->select($fields)->where($conditionData)->limit(1)->execute()[0];
        }
    }


    /**
     * Updates only one record from the database.
     * @throws SqlBuilderException
     * @throws Exception
     * TODO: Refine the return type for the function, refined return can be seen in the execute function.
     */
    public function updateOne(array $updateData, array $conditionData): int|bool|array
    {
        if (empty($conditionData)) {
            throw new Exception("Can't update pile of data. Provide condition to update the data set.");
        } else {
            if (!$this->structure['type'] == 'table') {
                throw new Exception("Unable to update data in a view structure.");
            } else {
                return Query::table($this->structure['name'])->update($updateData)->where($conditionData)->limit(1)->execute();
            }
        }
    }


    /**
     * Deletes only one record from the database.
     * @throws SqlBuilderException
     * @throws Exception
     * TODO: Refine the return type for the function, refined return can be seen in the execute function.
     */
    public function deleteOne(array $conditionData): int|bool|array
    {
        if (empty($conditionData)) {
            throw new Exception("Can't delete pile of data. Provide condition to delete the data set.");
        } else {
            if (!$this->structure['type'] == 'table') {
                throw new Exception("Unable to delete data in a view structure.");
            } else {
                return Query::table($this->structure['name'])->delete()->where($conditionData)->limit(1)->execute();
            }
        }
    }
}