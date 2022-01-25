<?php

namespace Multipedidos;

class FilterBuilder
{
    protected $operation, $configuration;

    protected function filter()
    {
        $filteredValues = collect([]);

        foreach($this->configuration as $key => $value) {
            if(is_array($value)) continue;
            $filteredValues[$key] = $value;
        }

        foreach(collect(['always', $this->operation])->unique() as $currentOperation) {
            if($this->configuration->has($currentOperation))
                $filteredValues = $filteredValues->merge($this->configuration[$currentOperation]);
        }

        foreach($filteredValues as $key => $value) {
            if(!is_integer($key)) continue;
            $filteredValues[$value] = $value;
            $filteredValues->forget($key);
        }

        return $filteredValues;
    }
}
