<?php 

namespace Multipedidos;

use Illuminate\Support\Arr;

abstract class ParserV2 extends FilterBuilder
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
            try{
                $currentValue = $this->extract($parseFrom);
            }catch(\MultipedidosException $mpe) {
                continue;
            }

            $this->parsed->put($parseTo, $currentValue);
        }

        $this->undot();
        $this->buildResponseClass();
        $this->validate();

        return $this->responseClass;
    }

    protected function extract($key)
    {
        $value = $this->collectionToBeParsed->pluck($key)->first();

        if(is_null($value))
            throw new \MultipedidosException('Value cannot be null');

        return $value;
    }

    private function undot() 
    { 
        $all = $this->parsed->all();
        $result = undot($all);
        $this->parsed = collect($result);
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
