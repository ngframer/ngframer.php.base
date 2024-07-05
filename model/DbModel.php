<?php

namespace NGFramer\NGFramerPHPBase\model;

use Exception;
use NGFramer\NGFramerPHPExceptions\exceptions\SqlBuilderException;
use NGFramer\NGFramerPHPSQLServices\Query;

abstract class DbModel extends BaseModel
{
    // Structural properties of the database.
    protected array $structure = [];
    protected array $fields;
    protected array $insertableFields;
    protected array $updatableFields;
    protected array $massUpdatableFields;




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
     *
     * @param array $insertData . Insert data should be in this format, [field1 => value1, field2 => value2].
     * @return int. Returns the lastlyInsertedId from the database table.
     * @throws Exception.
     */
    final public function insert(array $insertData): int
    {
        // Check if the fields are insertable.
        foreach ($insertData as $insertKey => $insertValue) {
            if (!in_array($insertKey, $this->insertableFields)) {
                throw new Exception("The following field can't be inserted manually. $insertKey.");
            }
        }

        if (!$this->structure['type'] == 'table') {
            throw new Exception("Unable to insert data into a view structure.");
        } else {
            return Query::table($this->structure['name'])->insert($insertData)->execute();
        }
    }


    /**
     * Fetches all the data from the database with specified condition.
     * Function to operate into the structures.
     * @param array $fields . Fields to be selected should be in this format, [field1, field2, field3].
     * @param array $conditionData . Condition data should be in this format, [[field1, value1, symbol1], [field2, value2, symbol2]].
     * @return array . Returns the selected data from the database table in the form of an array. Example ['field1' => 'value1', 'field2' => 'value2']. Upto max of 25 rows.
     * @throws Exception .
     */
    public function select(array $fields, array $conditionData = []): array
    {
        // Check if all fields are valid.
        foreach ($fields as $field) {
            if (!in_array($field, $this->fields)) {
                throw new Exception("The field '$field' doesn't exist in the database model.");
            }
        }

        // Now the main processing.
        $fields = implode(', ', $fields);
        if (empty($conditionData)) {
            return Query::table($this->structure['name'])->select($fields)->execute();
        } else {
            return Query::table($this->structure['name'])->select($fields)->where($conditionData)->execute();
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
        // Check for updateData mass fillable.
        foreach ($updateData as $updateKey => $updateValue) {
            if (!in_array($updateKey, $this->massUpdatableFields)) {
                throw new Exception("The following field cannot be mass updated, try using updateOne(). $updateKey.");
            }
        }

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
     * Inserts just one row of data to the database. Same as insert.
     * @param array $insertData . Insert data should be in this format, [field1 => value1, field2 => value2].
     * @throws Exception
     */
    public function insertOne(array $insertData): int
    {
        return $this->insert($insertData);
    }


    /**
     * Selects only one record from the database.
     * @param array $fields . Fields to be selected should be in this format, [field1, field2, field3].
     * @param array $conditionData . Condition data should be in this format, [[field1, value1, symbol1], [field2, value2, symbol2]].
     * @throws SqlBuilderException.
     * @throws Exception.
     */
    public function selectOne(array $fields, array $conditionData): array
    {
        // Check if each field is valid.
        foreach ($fields as $field) {
            if (!in_array($field, $this->fields)) {
                throw new Exception("The field '$field' doesn't exist in the database model.");
            }
        }

        // The main processing of the select query.
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
        // Check for the updateData.
        foreach ($updateData as $updateKey => $updateValue) {
            if (!in_array($updateKey, $this->updatableFields)) {
                throw new Exception("The following field cannot be updated. $updateKey.");
            }
        }

        // The main process of update.
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