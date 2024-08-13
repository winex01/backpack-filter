<?php

namespace Winex01\BackpackFilter\Http\Controllers\Operations;

use Closure;
use Illuminate\Support\Facades\Validator;
use Winex01\BackpackFilter\Rules\DateRangePicker;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

trait FilterOperation
{
    /**
     * Add the default settings, buttons, etc that this operation needs.
     */
    protected function setupExtendFilterDefaults()
    {
        CRUD::allowAccess('filters');

        CRUD::operation('filterOperation', function () {
            CRUD::loadDefaultOperationSettingsFromConfig();
        });

        CRUD::operation(['list', 'export'], function () {
            $this->setupFilterOperation();
            $this->crud->macro('filterLists', function() {
                return $this->fields();
            });
        });
    }

    public function filterValidations()
    {   
        // If no access to filters, then don't proceed but don't show an error.
        if (!$this->crud->hasAccess('filters')) {
            return false;
        }

        $validationErrors = [];

        foreach ($this->crud->filterLists() as $filter) {

            $filterName = $filter['name'];
            $filterValue = request()->input($filterName);
            
            if ($filter['type'] == 'date_range') {
                $validator = Validator::make([$filterName => $filterValue], [
                    $filterName => [
                        'nullable',
                        new DateRangePicker(),
                    ],
                ]);    
        
                if ($validator->fails()) {
                    $validationErrors = array_merge($validationErrors, $validator->errors()->all());
                }

            } elseif ($filter['type'] == 'select') {
                $validator = Validator::make([$filterName => $filterValue], [
                    $filterName => [
                        'nullable',
                        'in:' . implode(',', array_keys($filter['options'])), // Dynamic options here
                    ],
                ]);    
        
                if ($validator->fails()) {
                    $validationErrors = array_merge($validationErrors, $validator->errors()->all());
                }
            }
        }

        // Show all validation errors if any
        if (!empty($validationErrors)) {
            \Alert::error($validationErrors);
            return false;
        }

        return true;
    }

    public function filterQueries(Closure $callback = null)
    {
        if (!$this->crud->hasAccess('filters')) {
            return;
        }

        // make sure to run only filterValidations on list and export operation, 
        // because we put the filterQueries in setupListOperation and most of the time
        // we inherit all setupListOperaiton into our showOperation and cause error.,
        if (in_array($this->crud->getOperation(), ['list', 'export'])) {
            $this->filterValidations();
        }

        // Execute the callback if provided
        if ($callback) {
            $callback($this->crud->query);
        }
    }

    public function setupFilterOperation()
    {
        // example field
        $this->crud->field([
            'name' => 'example',
            'label' => 'Example field',
            'type' => 'select',
            'options' => [
                1 => 'Lorem',
                2 => 'Ipsum',
            ],
        ]);
    }
}