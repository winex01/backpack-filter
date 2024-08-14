<?php

namespace Winex01\BackpackFilter\Http\Controllers\Operations;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Route;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Winex01\BackpackFilter\Http\Controllers\Operations\FilterOperation;

trait ExportOperation
{
    use FilterOperation;
    /**
     * Define which routes are needed for this operation.
     *
     * @param string $segment    Name of the current entity (singular). Used as first URL segment.
     * @param string $routeName  Prefix of the route name.
     * @param string $controller Name of the current CrudController.
     */
    protected function setupExportRoutes($segment, $routeName, $controller)
    {
        Route::get($segment.'/export', [
            'as'        => $routeName.'.export',
            'uses'      => $controller.'@export',
            'operation' => 'export',
        ]);
    }

    /**
     * Add the default settings, buttons, etc that this operation needs.
     */
    protected function setupExportDefaults()
    {
        CRUD::allowAccess('export');

        CRUD::operation('export', function () {
            CRUD::loadDefaultOperationSettingsFromConfig();
        });

        $exportRoute = $this->exportRoute();        
        $this->crud->macro('exportRoute', function() use ($exportRoute) {
            return $exportRoute;
        });

    }

    // this is use for route in blade file. if you define a route here then it will use instead of the auto
    public function exportRoute()
    {
        return;
    }

    /**
     * Show the view for performing the operation.
     *
     */
    public function export()
    {
        CRUD::hasAccessOrFail('export');

        // validate first
        if ($this->crud->hasAccess('filters')) {
            $this->filterValidations();
        }

        return $this->exportClass();
    }

    // override this in controller to change the export class
    public function exportClass()
    {
        $class = ucwords($this->crud->entity_name) . 'Export';

        // Build the class name with the namespace
        $classExport = 'App\\Exports\\' . str_replace(' ', '', $class);

        // Instantiate the class using the variable
        $classExportInstance = new $classExport();

        return $classExportInstance->download($this->strHumanReadable($class) . '-' . now() . '.xlsx');
    }

    private function strHumanReadable($string) 
    {
		// Convert camel case to snake case with underscores
		$snake = Str::snake($string);

		// Replace underscores with spaces
		$spaced = str_replace('_', ' ', $snake);

		// Convert to title case
		$humanReadable = Str::title($spaced);

		return $humanReadable;
	}

}