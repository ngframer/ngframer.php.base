<?php

namespace NGFramer\NGFramerPHPBase\model;

use Exception;
use NGFramer\NGFramerPHPExceptions\exceptions\SqlBuilderException;
use NGFramer\NGFramerPHPSQLServices\Query;

class DbModel extends BaseModel
{
    // Structural properties of the database.
    protected array $structure = [];

    // The $fields contains all the properties that exists in database.
    protected array $fields;
    // Only the fields that are not allowed to be passed to database.
    protected array $fillableFields;
    // Only the fields that are automatically filled by the database.
    protected array $autofillFields;
    // Only the fields that are hidden from the general view, and are not supposed to be updated manually, but systematically is allowed like the last_updated column.
    protected array $guardedFields;


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