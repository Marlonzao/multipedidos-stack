<?php
    namespace Multipedidos;

    use Illuminate\Http\Request;
    use Laravel\Lumen\Routing\Controller as BaseController;

    class Controller extends BaseController
    {
        protected $domain, $domainID, $collection, $data;
        protected $domainName = 'Domain';

        public function __construct(Request $request)
        {
            $this->request  = $request;
            $this->data     = $request->all();

            $this->domainID = last($request->route()[2]);

            $this->domain = new $this->domain();
        }

        protected function create()
        {
            $this->domain->create($this->data);
        }

        protected function update()
        {
            $this->domain->find($this->domainID)->update($this->data);
        }

        protected function find()
        {
            $this->domain->find($this->domainID);
        }

        protected function all()
        {
            $this->domain->all();
        }

        public function delete()
        {
            $this->domain->delete($this->domainID);
        }

        protected function throw_error_if_not_found()
        {
            if( $this->domain->getModel() === null ) 
                abort(404, "{$this->domainName} not found")    
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
