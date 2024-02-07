<?php

namespace NGFramer\NGFramerPHPBase\model;

use NGFramer\NGFramerPHPBase\utilities\UtilCommon;
use NGFramer\NGFramerPHPBase\utilities\UtilDatetime;
use NGFramer\NGFramerPHPBase\utilities\UtilGender;
use NGFramer\NGFramerPHPBase\utilities\UtilName;
use NGFramer\NGFramerPHPBase\utilities\UtilNeupId;
use NGFramer\NGFramerPHPBase\utilities\UtilPassword;
use NGFramer\NGFramerPHPBase\utilities\UtilUrl;

abstract class BaseModel
{
	// The properties for the Model Classes.
	protected array $fields;
    protected array $fieldsType;
    protected array $data;
    protected array $rules;
    protected array $errors;



	// The rules for the data to be inserted to database.
	public const RULE__REQUIRED =  'RULE__REQUIRED';
	public const RULE_ACCOUNTID__NULL = 'RULE_ACCOUNTID__NULL';
	public const RULE_ACCOUNTID__NOT_NULL = 'RULE_ACCOUNTID__NOT_NULL';
	public const RULE_ACCOUNTID__INTEGER = 'RULE_ACCOUNTID__INTEGER';
	public const RULE_ID__NULL = 'RULE_ID__NULL ';
	public const RULE_ID__NOT_NULL = 'RULE_ID__NOT_NULL ';
	public const RULE_ID__INTEGER = 'RULE_ID__INTEGER';
	public const RULE_ACCOUNTTYPE__VALID = 'RULE_ACCOUNTTYPE__VALID';
	public const RULE_NAME__VALID = 'RULE_NAME__VALID';
	public const RULE_DISPLAYIMAGE__VALID = 'RULE_DISPLAYIMAGE__VALID';
	public const RULE_DATE__VALID = 'RULE_DATE__VALID';
	public const RULE_BIRTHDATE__REQUIRED = 'RULE_BIRTHDATE__REQUIRED';
	public const RULE_BIRTHDATE__VALID = 'RULE_BIRTHDATE__VALID';
	public const RULE_AGE__VALID = 'RULE_AGE__VALID';
	public const RULE_AGE__INDIV = 'RULE_AGE__INDIV ';
	public const RULE_AGE__DEPENDENT = 'RULE_AGE__DEPENDENT';
	public const RULE_GENDER__VALID = 'RULE_GENDER__VALID';
	public const RULE_NEUPID__UNIQUE = 'RULE_NEUPID__UNIQUE';
	public const RULE_NEUPID__NOT_RESERVED = 'RULE_NEUPID__NOT_RESERVED';
	public const RULE_PASSWORD__MIN = 'RULE_PASSWORD__MIN';
	public const RULE_PASSWORD__MAX = 'RULE_PASSWORD__MAX';
	public const RULE_PASSWORD__MATCH = 'RULE_PASSWORD__MATCH';
	public const RULE_AGREEMENT__ACCEPTED = 'RULE_PASSWORD__MATCH';



	// Get the fields.
    final public function getFields(): array
	{
		return $this->fields;
	}


	// Set the data.
	final public function loadData(array ...$args): void
    {
		$argsArray = UtilCommon::makeArray(...$args);
		foreach ($argsArray as $key => $value) {
			if (in_array($key, $this->getFields())) {
				$this->data[$key] = $value;
			}
		}
	}




	// Function to sanitize the data of the type String, & Array. Checks for the array by looking if the array's key and value are of String type.
	final public function validate(): void
    {
		foreach ($this->getRules() as $fieldName => $fieldCriteria) {
			// Value for that field.
			$fieldValue = $this->getData($fieldName);
            $ruleName = "";
            $ruleData = null;

			// Loop through the criteria for the current field.
			foreach ($fieldCriteria as $criterion) {
				// Extract the rule name and criteria data.
				if (is_array($criterion)) {
					$ruleName = $criterion[0];
					$ruleData = $criterion[1];
				} elseif (is_string($criterion)) {
					$ruleName = $criterion;
				}

				switch ($ruleName) {
                    case 'RULE_ACCOUNTID__NOT_NULL':
                    case 'RULE_ID__NOT_NULL':
                    case 'RULE__REQUIRED':
						if (empty($fieldValue)) {
							$this->setErrors($fieldName, $ruleName);
						}
						break;
                    case 'RULE_ID__NULL':
                    case 'RULE_ACCOUNTID__NULL':
						if (!empty($fieldValue)) {
							$this->setErrors($fieldName, $ruleName);
						}
						break;
                    case 'RULE_ID__INTEGER':
                    case 'RULE_ACCOUNTID__INTEGER':
						if (!is_int($fieldValue)) {
							$this->setErrors($fieldName, $ruleName);
						}
						break;
                    case 'RULE_ACCOUNTTYPE__VALID':
						if (in_array($fieldValue, ['indiv', 'brand', 'dependent'])) {
							$this->setErrors($fieldName, $ruleName);
						}
						break;
					case 'RULE_NAME__VALID':
						if (!UtilName::isValidName($fieldValue)) {
							$this->setErrors($fieldName, $ruleName);
						}
						break;
					case 'RULE_DISPLAYIMAGE__VALID':
						if (!UtilUrl::isValidURL($fieldValue)) {
							$this->setErrors($fieldName, $ruleName);
						}
						break;
					case 'RULE_DATE__VALID':
						if (!UtilDatetime::isValidDate($fieldValue)) {
							$this->setErrors($fieldName, $ruleName);
						}
						break;
					case 'RULE_BIRTHDATE__VALID':
						if (!UtilDatetime::isValidBirthdate($fieldValue)) {
							$this->setErrors($fieldName, $ruleName);
						}
						break;
					case 'RULE_AGE__VALID':
						if (!UtilDatetime::isValidAge($fieldValue)) {
							$this->setErrors($fieldName, $ruleName);
						}
						break;
					case 'RULE_AGE__INDIV':
						if (!UtilDatetime::isValidIndivAge($fieldValue)) {
							$this->setErrors($fieldName, $ruleName);
						}
						break;
					case 'RULE_AGE__DEPENDENT':
						if (!UtilDatetime::isValidDependentAge($fieldValue)) {
							$this->setErrors($fieldName, $ruleName);
						}
						break;
					case 'RULE_GENDER__VALID':
						if (!UtilGender::isValidGender($fieldValue)) {
							$this->setErrors($fieldName, $ruleName);
						}
						break;
					case 'RULE_NEUPID__VALID':
						if (!UtilNeupId::isValidNeupIdFormat($fieldValue)) {
							$this->setErrors($fieldName, $ruleName);
						}
						break;
					case 'RULE_NEUPID__NOT_RESERVED':
						if (!UtilNeupId::isReservedNeupId($fieldValue)) {
							$this->setErrors($fieldName, $ruleName);
						}
						break;
					case 'RULE_PASSWORD__MIN_PASSWORD_STRENGTH':
						if (UtilPassword::calculatePasswordStrength($this->data['password'], $this->data['firstName'], $this->data['middleName'], $this->data['lastName'], $this->data['phone'], $this->data['countryName'], $this->data['birthDate'])) {
							$this->setErrors($fieldName, $ruleName);
						}
						break;
					case 'RULE_PASSWORD__MATCH':
						if ($this->data['password'] !== $this->data['retypedPassword']) {
							$this->setErrors($fieldName, $ruleName);
						}
						break;
				}
			}
		}
	}


	// Get the rules.
	final public function getRules(): array
	{
		return $this->rules;
	}



    // Get the data.
	private function getData($fieldName)
	{
        return $this->data[$fieldName] ?? null;
	}


	// Set the error.
	final protected function setErrors(string $fieldName, string $ruleName): void
    {
        $this->errors[$fieldName][] = $ruleName;
	}


    // Check for errors.
    final public function hasErrors($fieldName): bool
    {
        return $this->errors[$fieldName] ?? false;
    }


	// Get the error.
	final public function getErrors($fieldName = null): array
	{
        if ($fieldName !== null){
            return $this->errors[$fieldName];
        }
        return $this->errors;
	}


    // Get the first error.
    final public function getFirstError($fieldName): string
    {
        return $this->getErrors($fieldName)[0];
    }
}
