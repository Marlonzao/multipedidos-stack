<?php
    namespace Multipedidos;

    use Illuminate\Http\Request;
    use Laravel\Lumen\Routing\Controller as BaseController;

    class Controller extends BaseController
    {
        protected $domain, $collection;
        protected $domainName = 'Domain';

        protected function create()
        {
            $this->domain->create($this->data());
        }

        protected function update()
        {
            $this->domain->find($this->domainID())->update($this->data());
        }

        protected function find()
        {
            $this->domain->find($this->domainID());
        }

        protected function all()
        {
            $this->domain->all();
        }

        public function delete()
        {
            $this->domain->delete($this->domainID());
        }

        private function data()
        {
            return request()->all();
        }

        private function domainID()
        {
            return end(request()->route()[2]);
        }

        public function __call($name, $arguments)
        {
            $this->{$name}(...$arguments);

            $result = $this->domain->toArray();

            if(empty($result)) return;

            if(is_null($this->collection)) return $result;
                
            if(isset($result[0])) 
                return $this->collection::collection($result);
            
            return new $this->collection($result);
        }
    }