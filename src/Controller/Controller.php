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

        protected function throw_error_if_not_found()
        {
            if( $this->domain->getModel() === null ) 
                abort(404, "{$this->domainName} not found");
        }

        public function __call($name, $arguments)
        {
            $this->{$name}(...$arguments);

            $model = $this->domain->getModel();

            if($model === null) return;

            if(is_null($this->collection)) return $model;

            if(str_contains(get_class($model), 'Model')) 
                return new $this->collection($model);
                
            if(str_contains(get_class($model), 'Collection')) 
                return $this->collection::collection($model);
        }
    }