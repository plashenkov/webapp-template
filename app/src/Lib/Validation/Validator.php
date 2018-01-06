<?php

namespace App\Lib\Validation;

use App\Lib\Validation\Exception\ParamValidationException;
use App\Lib\Validation\Exception\ValidationException;

class Validator
{
    /** @var array */
    protected $data;

    /** @var array */
    protected $rules = [];

    /**
     * Validator constructor.
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        $this->setData($data);
    }

    /**
     * Set input data which needs validation.
     * @param array $data
     * @return $this
     */
    public function setData(array $data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Get data param.
     * @param $paramName
     * @return mixed|null
     */
    public function getParam($paramName)
    {
        return isset($this->data[$paramName]) ? $this->data[$paramName] : null;
    }

    /**
     * Clear all rules.
     * @return $this
     */
    public function clearRules()
    {
        $this->rules = [];

        return $this;
    }

    /**
     * Adds validation rule.
     * @param string $selector
     * @param Rule|callable|array $ruleOrRules
     * @return $this
     */
    public function addRule($selector, $ruleOrRules)
    {
        $rules = is_array($ruleOrRules) ? $ruleOrRules : [$ruleOrRules];
        foreach ($rules as $rule) {
            if (!$rule instanceof Rule && !is_callable($rule)) {
                throw new \InvalidArgumentException(
                    'Each rule must be a callable or an instance of Rule'
                );
            }
        }

        if (!isset($this->rules[$selector])) {
            $this->rules[$selector] = [];
        }

        $this->rules[$selector][] = $ruleOrRules;

        return $this;
    }

    /**
     * Validate all.
     * @param string $errorMessage
     * @return $this
     * @throws ValidationException
     */
    public function validate($errorMessage = 'Validation error')
    {
        $validationErrors = [];

        foreach ($this->rules as $selector => $rules) {
            $value = arrayGetItem($this->data, $selector);

            foreach ($rules as $ruleOrGroup) {
                $isGroup = is_array($ruleOrGroup);
                $ruleOrGroup = $isGroup ? $ruleOrGroup : [$ruleOrGroup];
                $errors = [];

                foreach ($ruleOrGroup as $rule) {
                    try {
                        if ($rule instanceof Rule) {
                            $rule->validate($value, $this);
                        } elseif (is_callable($rule)) {
                            $rule($value, $this);
                        }
                    } catch (ParamValidationException $e) {
                        if ($isGroup) {
                            $errors[] = $e->getMessage();
                        } else {
                            $errors = $e->getMessage();
                        }
                    }
                }

                if (!empty($errors)) {
                    arraySetItem($validationErrors, $selector, $errors);
                    break;
                }
            }
        }

        if (!empty($validationErrors)) {
            throw new ValidationException($validationErrors, $errorMessage);
        }

        return $this;
    }
}
