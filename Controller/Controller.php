<?php
    namespace Multipedidos;

    use Illuminate\Http\Request;
    use Laravel\Lumen\Routing\Controller as BaseController;

    class Controller extends BaseController
    {
        public $domain, $domainID, $collection, $data;
        public $domainName = 'Domain';

        public function __construct(Request $request)
        {
            $this->request  = $request;
            $this->data     = $request->all();

            $this->domainID     = last($request->route()[2]);
            $this->restaurantID = $request->restaurantID;
            $this->cashierID    = $request->cashierID;
        }

        protected function create()
        {
            $this->domain->create($this->data);
        }

        protected function update()
        {
            $this->domain->getByID($this->domainID)->update($this->data);
        }

        protected function getByID()
        {
            $this->domain->getByID($this->domainID);
        }

        protected function all()
        {
            $this->domain->all();
        }

        public function delete()
        {
            $this->domain->deleteByID($this->domainID);
        }

        protected function getAllByRestaurantID()
        {    
            $this->domain->getAllByRestaurantID($this->restaurantID);
            
            $this->throw_error_if_not_found();
        }

        protected function getByRestaurantID()
        {   
           $this->domain->getByRestaurantID($this->restaurantID);
            
            $this->throw_error_if_not_found();
        }

        protected function getByCashierID()
        {   
            $this->domain->getByCashierID($this->cashierID);

            $this->throw_error_if_not_found();
        }

        protected function throw_error_if_not_found()
        {
            if( $this->domain->getModel() === null ) 
                throw new \MultipedidosException("{$this->domainName} not found", 404);
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
