<?php

namespace NGFramer\NGFramerPHPBase\model;

use NGFramer\NGFramerPHPBase\defaults\exceptions\ModelException;
use NGFramer\NGFramerPHPExceptions\exceptions\BaseException;
use NGFramer\NGFramerPHPSQLServices\Query;

abstract class DbModel extends BaseModel
{
    // Structural properties of the database.
    /**
     * @var array
     * The variable will store its name and type (string) in it.
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
     * @throws ModelException
     */
    final public function insert(array $insertData): array
    {
        // Check for field/s.
        foreach ($insertData as $insertKey => $insertValue) {
            // Check if the field/s exist.
            if (!in_array($insertKey, $this->fields)) {
                throw new ModelException("The following field doesn't exist in the database model. $insertKey.", 1006001, 'base.model.fieldNotExistInModel');
            }
            // Check if the field/s are insertable.
            if (!in_array($insertKey, $this->insertableFields)) {
                throw new ModelException("The following field can't be inserted manually. $insertKey.", 1006002, 'base.model.fieldNotInsertable');
            }
        }

        // Check for the structure type.
        if (!$this->structure['type'] == 'table') {
            throw new ModelException("Unable to insert data in a view structure.", 1006003, 'base.model.unableToInsertInView');
        }

        // Execute the insert query, prepare the response, and return it.
        $table = $this->structure['name'];
        try {
            $lastInsertId = Query::table($table)->insert($insertData)->execute()->lastInsertId();
        } catch (BaseException $exception) {
            throw new ModelException($exception->getMessage(), 1006004, 'base.model.queryExecutionError', $exception); // Generic error code for query execution issues
        }
        return ['lastInsertId' => $lastInsertId];
    }


    /**
     * Inserts just one row of data to the database. Same as insert.
     * @param array $insertData . Insert data should be in this format, [field1 ⇒ value1, field2 ⇒ value2].
     * @throws ModelException
     */
    public function insertOne(array $insertData): array
    {
        return $this->insert($insertData);
    }


    /**
     * Updates all the record from the database with specified condition.
     * @throws ModelException
     */
    public function update(array $updateData, array $conditionData): array
    {
        // Check for field/s.
        foreach ($updateData as $updateKey => $updateValue) {
            // Check if the field/s exist.
            if (!in_array($updateKey, $this->fields)) {
                throw new ModelException("The field $updateKey doesn't exist in the database model.", 1007001, 'base.model.fieldNotExistInDbModel');
            }
            // Check if the field/s are mass updatable.
            if (!in_array($updateKey, $this->massUpdatableFields)) {
                throw new ModelException("The field $updateKey cannot be mass updated, try using updateOne().", 1007002, 'base.model.fieldNotMassUpdatable');
            }
        }

        // Check for condition/s.
        if (empty($conditionData)) {
            throw new ModelException("Update restricted for all records. Provide condition to select the data to update.", 1007003, 'base.model.updateRestrictedForAll');
        }

        // Check for the structure type.
        if (!$this->structure['type'] == 'table') {
            throw new ModelException("Unable to update data in a view structure.", 1007004, 'base.model.unableToUpdateInView');
        }

        // Execute the update query, prepare the response, and return it.
        try {
            $table = $this->structure['name'];
            $rowCount = Query::table($table)->update($updateData)->where($conditionData)->execute()->rowCount();
        } catch (BaseException $exception) {
            throw new ModelException($exception->getMessage(), 1007005, 'base.model.queryExecutionError.2', $exception);
        }

        // Return the response.
        return ['updateCount' => $rowCount];
    }


    /**
     * Updates only one record from the database.
     * @throws ModelException
     */
    public function updateOne(array $updateData, array $conditionData): array
    {
        // Check for field/s.
        foreach ($updateData as $updateKey => $updateValue) {
            // Check if the field/s exist.
            if (!in_array($updateKey, $this->fields)) {
                throw new ModelException("The field $updateKey doesn't exist in the database model.", 1007006, 'model.fieldNotExistInModel.2');
            }
            // Check if the field/s are updatable.
            if (!in_array($updateKey, $this->updatableFields)) {
                throw new ModelException("The field $updateKey cannot be updated.", 1007007, 'base.model.fieldNotUpdatable');
            }
        }

        // Check for condition/s.
        if (empty($conditionData)) {
            throw new ModelException("Can't update mass records. Provide condition to update the data set.", 1007008, 'base.model.cantUpdateMassRecords');
        }

        // Check for the structure type.
        if (!$this->structure['type'] == 'table') {
            throw new ModelException("Unable to update data in a view structure.", 1007009, 'base.model.unableToUpdateInView.2');
        }

        // Execute the update query, prepare the response, and return it.
        try {
            $rowCount = Query::table($this->structure['name'])->update($updateData)->where($conditionData)->limit(1)->execute()->rowCount();

        } catch (BaseException $exception) {
            throw new ModelException($exception->getMessage(), 1007010, 'base.model.queryExecutionError.3', $exception);
        }

        // Return the response.
        return ['updateCount' => $rowCount];

    }


    /**
     * Deletes all the record from the database with specified condition.
     * @throws ModelException
     */
    public function delete(array $conditionData): array
    {
        // Check for condition/s.
        if (empty($conditionData)) {
            throw new ModelException("Can't delete pile of data. Provide condition to delete the data set.", 1008001, 'base.model.cantDeletePileOfData');
        }

        // Check for the structure type.
        if (!$this->structure['type'] == 'table') {
            throw new ModelException("Unable to delete data in a view structure.", 1008002, 'base.model.unableToDeleteInView');
        }

        // Execute the delete query, prepare the response, and return it.
        try {
            $table = $this->structure['name'];
            $rowCount = Query::table($table)->delete()->where($conditionData)->execute()->affectedRowCount();
        } catch (BaseException $exception) {
            // Assuming you want to wrap the original exception
            throw new ModelException($exception->getMessage(), 1008003, 'base.model.queryExecutionError.4', $exception);
        }

        // Return the response.
        return ['deleteCount' => $rowCount];
    }


    /**
     * Deletes only one record from the database.
     * @throws ModelException
     */
    public function deleteOne(array $conditionData): array
    {
        // Check for condition/s.
        if (empty($conditionData)) {
            throw new ModelException("Can't delete pile of data. Provide condition to delete the data set.", 1008004, 'base.model.cantDeletePileOfData.2');
        }

        // Check for the structure type.
        if (!$this->structure['type'] == 'table') {
            throw new ModelException("Unable to delete data in a view structure.", 1008005, 'base.model.unableToDeleteInView.2');
        }

        // Execute the delete query, prepare the response, and return it.
        try {
            $rowCount = Query::table($this->structure['name'])->delete()->where($conditionData)->limit(1)->execute()->rowCount();
        } catch (BaseException $exception) {
            throw new ModelException($exception->getMessage(), 1008006, 'base.model.queryExecutionError.5', $exception);
        }

        // Return the response.
        return ['deleteCount' => $rowCount];
    }


    /**
     * Fetches all the data from the database with specified condition.
     * Function to operate into the structures.
     * @param array $fields . Fields to be selected should be in this format, [field1, field2, field3].
     * @param array $conditionData . Condition data should be in this format, [[field1, value1, symbol1], [field2, value2, symbol2]].
     * @return array .
     * @throws ModelException
     *
     * Returns the selected data from the database table in the form of an array.
     * Example ['field1' ⇒ 'value1', 'field2' ⇒ 'value2'].
     * Up to max of 25 rows.
     */
    public function select(array $fields, array $conditionData = []): array
    {
        // Check for field/s.
        foreach ($fields as $field) {
            // Check if the field/s exist.
            if (!in_array($field, $this->fields)) {
                throw new ModelException("The field '$field' doesn't exists in the database model.", 1009001, 'base.model.fieldNotExistInModel');
            }
        }

        // Now the main processing.
        $fields = implode(', ', $fields);
        try {
            if (empty($conditionData)) {
                $fetchedResult = Query::table($this->structure['name'])->select($fields)->execute()->fetchAll();
            } else {
                $fetchedResult = Query::table($this->structure['name'])->select($fields)->where($conditionData)->execute()->fetchAll();
            }
        } catch (BaseException $exception) {
            throw new ModelException($exception->getMessage(), 1009002, 'base.model.queryExecutionError.6', $exception);
        }


        // Use the executed result to prepare the response and return it.
        return ['records' => $fetchedResult];
    }


    /**
     * Selects only one record from the database.
     * @param array $fields . Fields to be selected should be in this format, [field1, field2, field3].
     * @param array $conditionData . Condition data should be in this format, [[field1, value1, symbol1], [field2, value2, symbol2]].
     * @return array
     * @throws ModelException
     */
    public function selectOne(array $fields, array $conditionData): array
    {
        // Check for field/s.
        foreach ($fields as $field) {
            // Check if the field/s exist.
            if (!in_array($field, $this->fields)) {
                throw new ModelException("The field '$field' doesn't exist in the database model.", 1009003, 'base.model.fieldNotExistInDbModel.2');
            }
        }

        // The main processing of the select query.
        $fields = implode(', ', $fields);
        try {
            if (empty($conditionData)) {
                $response = Query::table($this->structure['name'])->select($fields)->limit(1)->execute()->fetchAll()[0] ?? [];
            } else {
                $response = Query::table($this->structure['name'])->select($fields)->where($conditionData)->limit(1)->execute()->fetchAll()[0] ?? [];
            }
        } catch (BaseException $exception) {
            throw new ModelException($exception->getMessage(), 1009004, 'base.model.queryExecutionError.7', $exception);
        }


        // Return the response.
        return ['records' => $response];
    }
}