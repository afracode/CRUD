<?php

namespace Afranext\Crud\Overrides;


use Illuminate\Routing\ResourceRegistrar as OriginalRegistrar;

class ResourceRegistrar extends OriginalRegistrar

{


    protected $resourceDefaults = ['index', 'create', 'store', 'show', 'edit', 'update', 'destroy', 'datatable', 'deleteMedia'];


    protected function addResourceDatatable($name, $base, $controller, $options)
    {
        $uri = $this->getResourceUri($name) . '/datatable';
        $action = $this->getResourceAction($name, $controller, 'datatable', $options);
        return $this->router->post($uri, $action);
    }


    protected function addResourceDeleteMedia($name, $base, $controller, $options)
    {

        $name = $this->getShallowName($name, $options);

        $uri = $this->getResourceUri($name) . '/{' . $base . '}' . '/media';

        $action = $this->getResourceAction($name, $controller, 'deleteMedia', $options);

        return $this->router->delete($uri, $action);
    }
}
