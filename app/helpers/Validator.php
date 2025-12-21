<?php
/**
 * Validator Helper Class
 * AgriSmart - Agriculture Marketplace
 * 
 * Provides input validation methods
 */

class Validator {
    
    private $errors = [];
    private $data = [];
    
    /**
     * Constructor
     * 
     * @param array $data
     */
    public function __construct($data = []) {
        $this->data = $data;
    }
    
    /**
     * Validate required field
     * 
     * @param string $field
     * @param string $message
     * @return self
     */
    public function required($field, $message = null) {
        if (!isset($this->data[$field]) || trim($this->data[$field]) === '') {
            $this->errors[$field] = $message ?? "The {$field} field is required.";
        }
        return $this;
    }
    
    /**
     * Validate email
     * 
     * @param string $field
     * @param string $message
     * @return self
     */
    public function email($field, $message = null) {
        if (isset($this->data[$field]) && !filter_var($this->data[$field], FILTER_VALIDATE_EMAIL)) {
            $this->errors[$field] = $message ?? "The {$field} must be a valid email address.";
        }
        return $this;
    }
    
    /**
     * Validate minimum length
     * 
     * @param string $field
     * @param int $length
     * @param string $message
     * @return self
     */
    public function min($field, $length, $message = null) {
        if (isset($this->data[$field]) && strlen($this->data[$field]) < $length) {
            $this->errors[$field] = $message ?? "The {$field} must be at least {$length} characters.";
        }
        return $this;
    }
    
    /**
     * Validate maximum length
     * 
     * @param string $field
     * @param int $length
     * @param string $message
     * @return self
     */
    public function max($field, $length, $message = null) {
        if (isset($this->data[$field]) && strlen($this->data[$field]) > $length) {
            $this->errors[$field] = $message ?? "The {$field} must not exceed {$length} characters.";
        }
        return $this;
    }
    
    /**
     * Validate field matches another field
     * 
     * @param string $field
     * @param string $matchField
     * @param string $message
     * @return self
     */
    public function matches($field, $matchField, $message = null) {
        if (isset($this->data[$field]) && isset($this->data[$matchField]) && 
            $this->data[$field] !== $this->data[$matchField]) {
            $this->errors[$field] = $message ?? "The {$field} must match {$matchField}.";
        }
        return $this;
    }
    
    /**
     * Validate numeric value
     * 
     * @param string $field
     * @param string $message
     * @return self
     */
    public function numeric($field, $message = null) {
        if (isset($this->data[$field]) && !is_numeric($this->data[$field])) {
            $this->errors[$field] = $message ?? "The {$field} must be a number.";
        }
        return $this;
    }
    
    /**
     * Validate URL
     * 
     * @param string $field
     * @param string $message
     * @return self
     */
    public function url($field, $message = null) {
        if (isset($this->data[$field]) && !filter_var($this->data[$field], FILTER_VALIDATE_URL)) {
            $this->errors[$field] = $message ?? "The {$field} must be a valid URL.";
        }
        return $this;
    }
    
    /**
     * Validate alphanumeric
     * 
     * @param string $field
     * @param string $message
     * @return self
     */
    public function alphanumeric($field, $message = null) {
        if (isset($this->data[$field]) && !ctype_alnum($this->data[$field])) {
            $this->errors[$field] = $message ?? "The {$field} must contain only letters and numbers.";
        }
        return $this;
    }
    
    /**
     * Validate minimum value
     * 
     * @param string $field
     * @param float $min
     * @param string $message
     * @return self
     */
    public function minValue($field, $min, $message = null) {
        if (isset($this->data[$field]) && (float)$this->data[$field] < $min) {
            $this->errors[$field] = $message ?? "The {$field} must be at least {$min}.";
        }
        return $this;
    }
    
    /**
     * Validate maximum value
     * 
     * @param string $field
     * @param float $max
     * @param string $message
     * @return self
     */
    public function maxValue($field, $max, $message = null) {
        if (isset($this->data[$field]) && (float)$this->data[$field] > $max) {
            $this->errors[$field] = $message ?? "The {$field} must not exceed {$max}.";
        }
        return $this;
    }
    
    /**
     * Validate in array
     * 
     * @param string $field
     * @param array $values
     * @param string $message
     * @return self
     */
    public function in($field, $values, $message = null) {
        if (isset($this->data[$field]) && !in_array($this->data[$field], $values)) {
            $this->errors[$field] = $message ?? "The {$field} is invalid.";
        }
        return $this;
    }
    
    /**
     * Validate unique value in database
     * 
     * @param string $field
     * @param string $table
     * @param string $column
     * @param int $excludeId
     * @param string $message
     * @return self
     */
    public function unique($field, $table, $column, $excludeId = null, $message = null) {
        if (!isset($this->data[$field])) {
            return $this;
        }
        
        try {
            $db = Database::getInstance()->getConnection();
            $sql = "SELECT COUNT(*) FROM {$table} WHERE {$column} = ?";
            $params = [$this->data[$field]];
            
            if ($excludeId !== null) {
                $sql .= " AND id != ?";
                $params[] = $excludeId;
            }
            
            $stmt = $db->prepare($sql);
            $stmt->execute($params);
            $count = $stmt->fetchColumn();
            
            if ($count > 0) {
                $this->errors[$field] = $message ?? "The {$field} has already been taken.";
            }
        } catch (PDOException $e) {
            // Log error
            error_log("Validation error: " . $e->getMessage());
        }
        
        return $this;
    }
    
    /**
     * Validate phone number
     * 
     * @param string $field
     * @param string $message
     * @return self
     */
    public function phone($field, $message = null) {
        if (isset($this->data[$field])) {
            $phone = preg_replace('/[^0-9+]/', '', $this->data[$field]);
            if (strlen($phone) < 10 || strlen($phone) > 15) {
                $this->errors[$field] = $message ?? "The {$field} must be a valid phone number.";
            }
        }
        return $this;
    }
    
    /**
     * Custom validation rule
     * 
     * @param string $field
     * @param callable $callback
     * @param string $message
     * @return self
     */
    public function custom($field, $callback, $message) {
        if (isset($this->data[$field]) && !$callback($this->data[$field])) {
            $this->errors[$field] = $message;
        }
        return $this;
    }
    
    /**
     * Check if validation passed
     * 
     * @return bool
     */
    public function passes() {
        return empty($this->errors);
    }
    
    /**
     * Check if validation failed
     * 
     * @return bool
     */
    public function fails() {
        return !$this->passes();
    }
    
    /**
     * Get validation errors
     * 
     * @return array
     */
    public function getErrors() {
        return $this->errors;
    }
    
    /**
     * Get first error
     * 
     * @return string|null
     */
    public function getFirstError() {
        return !empty($this->errors) ? reset($this->errors) : null;
    }
    
    /**
     * Get error for specific field
     * 
     * @param string $field
     * @return string|null
     */
    public function getError($field) {
        return $this->errors[$field] ?? null;
    }
    
    /**
     * Static validation method
     * 
     * @param array $data
     * @param array $rules
     * @return Validator
     */
    public static function make($data, $rules) {
        $validator = new self($data);
        
        foreach ($rules as $field => $ruleSet) {
            $fieldRules = explode('|', $ruleSet);
            
            foreach ($fieldRules as $rule) {
                if (strpos($rule, ':') !== false) {
                    list($ruleName, $ruleValue) = explode(':', $rule, 2);
                    $ruleParams = explode(',', $ruleValue);
                } else {
                    $ruleName = $rule;
                    $ruleParams = [];
                }
                
                if (method_exists($validator, $ruleName)) {
                    call_user_func_array([$validator, $ruleName], array_merge([$field], $ruleParams));
                }
            }
        }
        
        return $validator;
    }
}
