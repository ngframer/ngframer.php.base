<?php

namespace NGFramer\NGFramerPHPBase\model;

use Exception;
use NGFramer\NGFramerPHPSQLServices\Query;

abstract class DbModel extends BaseModel
{
    // Structural properties of the database.
    /**
     * @var array
     * The variable will store a name, and it's type (string) in it.
     */
    protected array $structure = [];
    protected array $fields;
    protected array $insertableFields;
    protected array $updatableFields;
    protected array $massUpdatableFields;


    // Property to save the instance of the class.
    protected static ?array $instances = null;


    /**
     * Function to initialize the instance of the class.
     * @return static. Returns an instance of this class.
     */
    final public static function init(): static
    {
        $calledClass = static::class;
        if (!isset(self::$instances[$calledClass])) {
            self::$instances[$calledClass] = new static();
        }
        return self::$instances[$calledClass];
    }


    /**
     * Making this class not-instance-able.
     */
    protected function __construct()
    {
    }


    /**
     * @param array $insertData . Insert data should be in this format, [field1 ⇒ value1, field2 ⇒ value2].
     * @return array . Returns an array, status mentions it if the execution was successful, the response[lastInsertId] contains the last inserted id.
     * @throws Exception
     */
    final public function insert(array $insertData): array
    {
        // Check for field/s.
        foreach ($insertData as $insertKey => $insertValue) {
            // Check if the field/s exist.
            if (!in_array($insertKey, $this->fields)) {
                throw new Exception("The following field doesn't exist in the database model. $insertKey.", 1000000);
            }
            // Check if the field/s are insertable.
            if (!in_array($insertKey, $this->insertableFields)) {
                throw new Exception("The following field can't be inserted manually. $insertKey.", 1000000);
            }
        }

        // Check for the structure type.
        if (!$this->structure['type'] == 'table') {
            throw new Exception("Unable to insert data in a view structure.");
        }

        // Execute the insert query, prepare the response, and return it.
        $table = $this->structure['name'];
        $lastInsertId = Query::table($table)->insert($insertData)->execute()->lastInsertId();
        return ['lastInsertedId' => $lastInsertId];
    }


    /**
     * Fetches all the data from the database with specified condition.
     * Function to operate into the structures.
     * @param array $fields . Fields to be selected should be in this format, [field1, field2, field3].
     * @param array $conditionData . Condition data should be in this format, [[field1, value1, symbol1], [field2, value2, symbol2]].
     * @return array .
     * Returns the selected data from the database table in the form of an array.
     * Example ['field1' ⇒ 'value1', 'field2' ⇒ 'value2'].
     * Up to max of 25 rows.
     * @throws Exception .
     */
    public function select(array $fields, array $conditionData = []): array
    {
        // Check for field/s.
        foreach ($fields as $field) {
            // Check if the field/s exist.
            if (!in_array($field, $this->fields)) {
                throw new Exception("The field '$field' doesn't exists in the database model.", 1000000);
            }
        }

        // Now the main processing.
        $fields = implode(', ', $fields);
        if (empty($conditionData)) {
            $fetchedResult = Query::table($this->structure['name'])->select($fields)->execute()->fetchAll();
        } else {
            $fetchedResult = Query::table($this->structure['name'])->select($fields)->where($conditionData)->execute()->fetchAll();
        }

        // Use the executed result to prepare the response and return it.
        return ['response' => $fetchedResult];
    }


    /**
     * Updates all the record from the database with specified condition.
     * @throws Exception
     */
    public function update(array $updateData, array $conditionData): array
    {
        // Check for field/s.
        foreach ($updateData as $updateKey => $updateValue) {
            // Check if the field/s exist.
            if (!in_array($updateKey, $this->fields)) {
                throw new Exception("The field $updateKey doesn't exist in the database model.", 1000000);
            }
            // Check if the field/s are mass updatable.
            if (!in_array($updateKey, $this->massUpdatableFields)) {
                throw new Exception("The field $updateKey cannot be mass updated, try using updateOne().", 1000000);
            }
        }

        // Check for condition/s.
        if (empty($conditionData)) {
            throw new Exception("Update restricted for all records. Provide condition to select the data to update.", 1000000);
        }

        // Check for the structure type.
        if (!$this->structure['type'] == 'table') {
            throw new Exception("Unable to update data in a view structure.", 1000000);
        }

        // Execute the update query, prepare the response, and return it.
        try {
            $table = $this->structure['name'];
            $rowCount = Query::table($table)->update($updateData)->where($conditionData)->execute()->rowCount();
            return ['rowCount' => $rowCount];
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }


    /**
     * Deletes all the record from the database with specified condition.
     * @throws Exception
     * TODO: Refine the return type for the function, refined return can be seen in the execute function.
     */
    public function delete(array $conditionData): array
    {
        // Check for condition/s.
        if (empty($conditionData)) {
            throw new Exception("Can't delete pile of data. Provide condition to delete the data set.", 1000000);
        }

        // Check for the structure type.
        if (!$this->structure['type'] == 'table') {
            throw new Exception("Unable to delete data in a view structure.", 1000000);
        }

        // Execute the delete query, prepare the response, and return it.
        try {
            $table = $this->structure['name'];
            $rowCount = Query::table($table)->delete()->where($conditionData)->execute()->affectedRowCount();
            return ['rowCount' => $rowCount];
        } catch (Exception $e) {
            error_log($e->getMessage());
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }


    /**
     * Inserts just one row of data to the database. Same as insert.
     * @param array $insertData . Insert data should be in this format, [field1 ⇒ value1, field2 ⇒ value2].
     * @throws Exception
     */
    public function insertOne(array $insertData): array
    {
        return $this->insert($insertData);
    }


    /**
     * Selects only one record from the database.
     * @param array $fields . Fields to be selected should be in this format, [field1, field2, field3].
     * @param array $conditionData . Condition data should be in this format, [[field1, value1, symbol1], [field2, value2, symbol2]].
     * @throws Exception.
     */
    public function selectOne(array $fields, array $conditionData): array
    {
        // Check for field/s.
        foreach ($fields as $field) {
            // Check if the field/s exist.
            if (!in_array($field, $this->fields)) {
                throw new Exception("The field '$field' doesn't exist in the database model.");
            }
        }

        // The main processing of the select query.
        $fields = implode(', ', $fields);
        if (empty($conditionData)) {
            $response = Query::table($this->structure['name'])->select($fields)->limit(1)->execute()->fetchAll();
        } else {
            $response = Query::table($this->structure['name'])->select($fields)->where($conditionData)->limit(1)->execute()->fetchAll();
        }

        // Now returning the response.
        if (empty($response)) {
            throw new Exception("No data found in the database.", 1000000);
        } else {
            return ['data' => $response[0]];
        }
    }


    /**
     * Updates only one record from the database.
     * @throws Exception
     * TODO: Refine the return type for the function, refined return can be seen in the execute function.
     */
    public function updateOne(array $updateData, array $conditionData): int|bool|array
    {
        // Check for field/s.
        foreach ($updateData as $updateKey => $updateValue) {
            // Check if the field/s exist.
            if (!in_array($updateKey, $this->fields)) {
                throw new Exception("The field $updateKey doesn't exist in the database model.", 1000000);
            }
            // Check if the field/s are updatable.
            if (!in_array($updateKey, $this->updatableFields)) {
                throw new Exception("The field $updateKey cannot be updated.", 1000000);
            }
        }

        // Check for condition/s.
        if (empty($conditionData)) {
            throw new Exception("Can't update pile of data. Provide condition to update the data set.", 1000000);
        }

        // Check for the structure type.
        if (!$this->structure['type'] == 'table') {
            throw new Exception("Unable to update data in a view structure.", 1000000);
        }

        // Execute the update query, prepare the response, and return it.
        try {
            $rowCount = Query::table($this->structure['name'])->update($updateData)->where($conditionData)->limit(1)->execute()->rowCount();
            return ['rowCount' => $rowCount];
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }


    /**
     * Deletes only one record from the database.
     * @throws Exception
     * TODO: Refine the return type for the function, refined return can be seen in the execute function.
     */
    public function deleteOne(array $conditionData): int|bool|array
    {
        // Check for condition/s.
        if (empty($conditionData)) {
            throw new Exception("Can't delete pile of data. Provide condition to delete the data set.", 1000000);
        }

        // Check for the structure type.
        if (!$this->structure['type'] == 'table') {
            throw new Exception("Unable to delete data in a view structure.", 1000000);
        }

        // Execute the delete query, prepare the response, and return it.
        try {
            $rowCount = Query::table($this->structure['name'])->delete()->where($conditionData)->limit(1)->execute()->rowCount();
            return ['rowCount' => $rowCount];
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }
}
