<?php

namespace NGFramer\NGFramerPHPBase\Schema;

use NGFramer\NGFramerPHPBase\Defaults\Exceptions\SchemaException;
use NGFramer\NGFramerPHPExceptions\Exceptions\BaseException;
use NGFramer\NGFramerPHPSQLServices\Query;

abstract class DbSchema
{
    /**
     * The variable will store its name and type (string) in it.
     * @var array
     */
    protected array $structure = [];

    /**
     * Variable storing all the fields available.
     * @var array
     */
    protected array $fields;

    /**
     * Variable storing fields that can be inserted.
     * @var array
     */
    protected array $insertableFields;

    /**
     * Variable storing fields that can be updated.
     * @var array
     */
    protected array $updatableFields;

    /**
     * Variable storing fields that can be updated at mass.
     * @var array
     */
    protected array $massUpdatableFields;

    /**
     * Variable to store the instance of a schema class.
     * @var array|null
     */
    protected static ?array $instances = null;

    /**
     * Variable to store configuration data.
     * @var array
     */
    private array $config = [];

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
     * @throws SchemaException
     */
    final public function insert(array $insertData): array
    {
        // Check for field/s.
        foreach ($insertData as $insertKey => $insertValue) {
            // Check if the field/s exist.
            if (!in_array($insertKey, $this->fields)) {
                throw new SchemaException("The following field doesn't exist in the database.schema. $insertKey.", 1006001, 'base.schema.fieldNotExistInSchema');
            }
            // Check if the field/s are insertable.
            if (!in_array($insertKey, $this->insertableFields)) {
                throw new SchemaException("The following field can't be inserted manually. $insertKey.", 1006002, 'base.schema.fieldNotInsertable');
            }
        }

        // Check for the structure type.
        if (!$this->structure['type'] == 'table') {
            throw new SchemaException("Unable to insert data in a view structure.", 1006003, 'base.schema.unableToInsertInView');
        }

        // Execute the insert query, prepare the response, and return it.
        $table = $this->structure['name'];
        try {
            $lastInsertId = Query::table($table)->insert($insertData)->execute()->lastInsertId();
        } catch (BaseException $exception) {
            throw new SchemaException($exception->getMessage(), $exception->getCode(), $exception->getLabel(), $exception);
        }
        return ['lastInsertId' => $lastInsertId];
    }


    /**
     * Inserts just one row of data to the database. Same as insert.
     * @param array $insertData . Insert data should be in this format, [field1 ⇒ value1, field2 ⇒ value2].
     * @throws SchemaException
     */
    public function insertOne(array $insertData): array
    {
        return $this->insert($insertData);
    }


    /**
     * Updates all the record from the database with specified condition.
     * @throws SchemaException
     */
    public function update(array $updateData, array $conditionData): array
    {
        // Check for field/s.
        foreach ($updateData as $updateKey => $updateValue) {
            // Check if the field/s exist.
            if (!in_array($updateKey, $this->fields)) {
                throw new SchemaException("The field $updateKey doesn't exist in the database.schema.", 1007001, 'base.schema.fieldNotExistInDbSchema.2');
            }
            // Check if the field/s are mass updatable.
            if (!in_array($updateKey, $this->massUpdatableFields)) {
                throw new SchemaException("The field $updateKey cannot be mass updated, try using updateOne().", 1007002, 'base.schema.fieldNotMassUpdatable');
            }
        }

        // Check for condition/s.
        if (empty($conditionData)) {
            throw new SchemaException("Update restricted for all records. Provide condition to select the data to update.", 1007003, 'base.schema.updateRestrictedForAll');
        }

        // Check for the structure type.
        if (!$this->structure['type'] == 'table') {
            throw new SchemaException("Unable to update data in a view structure.", 1007004, 'base.schema.unableToUpdateInView');
        }

        // Execute the update query, prepare the response, and return it.
        try {
            $table = $this->structure['name'];
            $rowCount = Query::table($table)->update($updateData)->where($conditionData)->execute()->rowCount();
        } catch (BaseException $exception) {
            throw new SchemaException($exception->getMessage(), $exception->getCode(), $exception->getLabel(), $exception);
        }

        // Return the response.
        return ['updateCount' => $rowCount];
    }


    /**
     * Updates only one record from the database.
     * @throws SchemaException
     */
    public function updateOne(array $updateData, array $conditionData): array
    {
        // Check for field/s.
        foreach ($updateData as $updateKey => $updateValue) {
            // Check if the field/s exist.
            if (!in_array($updateKey, $this->fields)) {
                throw new SchemaException("The field $updateKey doesn't exist in the database.schema.", 1007006, 'base.schema.fieldNotExistInSchema.3');
            }
            // Check if the field/s are updatable.
            if (!in_array($updateKey, $this->updatableFields)) {
                throw new SchemaException("The field $updateKey cannot be updated.", 1007007, 'base.schema.fieldNotUpdatable');
            }
        }

        // Check for condition/s.
        if (empty($conditionData)) {
            throw new SchemaException("Can't update mass records. Provide condition to update the data set.", 1007008, 'base.schema.cantUpdateMassRecords');
        }

        // Check for the structure type.
        if (!$this->structure['type'] == 'table') {
            throw new SchemaException("Unable to update data in a view structure.", 1007009, 'base.schema.unableToUpdateInView.2');
        }

        // Execute the update query, prepare the response, and return it.
        try {
            $rowCount = Query::table($this->structure['name'])->update($updateData)->where($conditionData)->limit(1)->execute()->rowCount();

        } catch (BaseException $exception) {
            throw new SchemaException($exception->getMessage(), $exception->getCode(), $exception->getLabel(), $exception);
        }

        // Return the response.
        return ['updateCount' => $rowCount];

    }


    /**
     * Deletes all the record from the database with specified condition.
     * @throws SchemaException
     */
    public function delete(array $conditionData): array
    {
        // Check for condition/s.
        if (empty($conditionData)) {
            throw new SchemaException("Can't delete pile of data. Provide condition to delete the data set.", 1008001, 'base.schema.cantDeletePileOfData');
        }

        // Check for the structure type.
        if (!$this->structure['type'] == 'table') {
            throw new SchemaException("Unable to delete data in a view structure.", 1008002, 'base.schema.unableToDeleteInView');
        }

        // Execute the delete query, prepare the response, and return it.
        try {
            $table = $this->structure['name'];
            $rowCount = Query::table($table)->delete()->where($conditionData)->execute()->affectedRowCount();
        } catch (BaseException $exception) {
            // Assuming you want to wrap the original exception
            throw new SchemaException($exception->getMessage(), $exception->getCode(), $exception->getLabel(), $exception);
        }

        // Return the response.
        return ['deleteCount' => $rowCount];
    }


    /**
     * Deletes only one record from the database.
     * @throws SchemaException
     */
    public function deleteOne(array $conditionData): array
    {
        // Check for condition/s.
        if (empty($conditionData)) {
            throw new SchemaException("Can't delete pile of data. Provide condition to delete the data set.", 1008004, 'base.schema.cantDeletePileOfData.2');
        }

        // Check for the structure type.
        if (!$this->structure['type'] == 'table') {
            throw new SchemaException("Unable to delete data in a view structure.", 1008005, 'base.schema.unableToDeleteInView.2');
        }

        // Execute the delete query, prepare the response, and return it.
        try {
            $rowCount = Query::table($this->structure['name'])->delete()->where($conditionData)->limit(1)->execute()->rowCount();
        } catch (BaseException $exception) {
            throw new SchemaException($exception->getMessage(), $exception->getCode(), $exception->getLabel(), $exception);
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
     * @throws SchemaException
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
                throw new SchemaException("The field '$field' doesn't exists in the database.schema.", 1009001, 'base.schema.fieldNotExistInSchema.4');
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
            throw new SchemaException($exception->getMessage(), $exception->getCode(), $exception->getLabel(), $exception);
        }


        // Use the executed result to prepare the response and return it.
        return ['records' => $fetchedResult];
    }


    /**
     * Selects only one record from the database.
     * @param array $fields . Fields to be selected should be in this format, [field1, field2, field3].
     * @param array $conditionData . Condition data should be in this format, [[field1, value1, symbol1], [field2, value2, symbol2]].
     * @return array
     * @throws SchemaException
     */
    public function selectOne(array $fields, array $conditionData): array
    {
        // Check for field/s.
        foreach ($fields as $field) {
            // Check if the field/s exist.
            if (!in_array($field, $this->fields)) {
                throw new SchemaException("The field '$field' doesn't exist in the database.schema.", 1009003, 'base.schema.fieldNotExistInDbSchema.5');
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
            throw new SchemaException($exception->getMessage(), $exception->getCode(), $exception->getLabel(), $exception);
        }


        // Return the response.
        return ['record' => $response];
    }
}
