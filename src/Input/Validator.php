<?php

namespace Multipedidos;

abstract class Validator extends FilterBuilder
{
    public $rules, $currentRules, $values;
    public $availableDataTypes = ['string', 'integer', 'double'];

    public function __construct(array $values)
    {
        $this->configuration = collect($this->rules);
        $this->values        = $values;
    }

    public function validate(string $operation)
    {
        $this->operation    = $operation;
        $this->currentRules = $this->filter();

        foreach($this->currentRules as $alias => $rules) {
            $currentValue = $this->values[$alias] ?? null;
            $rules        = explode('|', $rules);

            $isRequired = in_array('required', $rules);
            if($isRequired && !$currentValue)
                throw new \Error("$alias is required", 400);

            if(!$currentValue) continue;

            $dataType = collect(array_intersect($rules, $this->availableDataTypes))->first();
            if($dataType && gettype($currentValue) != $dataType)
                throw new \Error("Invalid data type, $alias must be $dataType", 400);
        }
    }
}
