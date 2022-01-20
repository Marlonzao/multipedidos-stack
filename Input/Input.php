<?php 

namespace Multipedidos;

abstract class Input extends FilterBuilder
{
    public $origin, $parser, $parsed, $currentParse, $collectionToBeParsed, $responseClass;

    public function __construct($valuesToBeParsed)
    {
        $this->configuration = collect($this->parser);

        $this->origin = $valuesToBeParsed;
        $this->transformOriginToCollection();
        $this->parsed = collect([]);
    }

    private function transformOriginToCollection()
    {
        $this->collectionToBeParsed = collect([$this->transformOriginToArray()]);
    }

    private function transformOriginToArray()
    {
        if(is_array($this->origin))
            return $this->origin;

        if(property_exists($this->origin, 'model') && !is_null($this->origin->model))
            return $this->origin->model->toArray();

        if(method_exists($this->origin, 'toArray'))
            return $this->origin->toArray();

        return (array) $this->origin;
    }

    public function parse($operation = 'always')
    {
        $this->operation    = $operation;
        $this->currentParse = $this->filter();

        foreach($this->currentParse as $parseFrom => $parseTo) {
            $currentValue = $this->collectionToBeParsed->pluck($parseFrom)->first();
            if(is_null($currentValue)) continue;

            $this->parsed = $this->parsed->merge([$parseTo => $currentValue]); 
        }

        $this->buildResponseClass();
        $this->validate();

        return $this->responseClass;
    }

    private function buildResponseClass()
    {
        $this->parsedAsArray = $this->parsed->toArray();

        $className = str_replace("Input", "", get_class($this));

        if(class_exists($className))
            return $this->responseClass = new $className($this->parsedAsArray);

        $newClass = new class($this->parsedAsArray) extends \Illuminate\Support\Collection {};
        class_alias(get_class($newClass), $className);
        $this->responseClass = $newClass;
    }

    private function validate()
    {
        $validatorName = str_replace("Input", "Validator", static::class);

        if(class_exists($validatorName))
            (new $validatorName($this->parsedAsArray))->validate($this->operation);
    }
}
