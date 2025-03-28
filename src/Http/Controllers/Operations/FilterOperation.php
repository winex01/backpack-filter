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

        \Backpack\CRUD\app\Library\Widget::add()->type('view')->view('winex01.backpack-filter::script');

        CRUD::operation(['list', 'export'], function () {
            if (config('winex01.backpack-filter.auto_add_button')) {
                CRUD::button('filters')->view('winex01.backpack-filter::buttons.list_top_collapse');
            }

            $this->setupFilterOperation();
            $this->crud->macro('filterLists', function () {
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

            $validator = null;
            if ($filter['type'] == 'date_range') {
                $validator = Validator::make([$filterName => $filterValue], [
                    $filterName => [
                        'nullable',
                        new DateRangePicker(),
                    ],
                ]);
            } elseif (isset($filter['relation_type']) && isset($filter['model'])) {
                // if using relationship
                $validator = Validator::make([$filterName => $filterValue], [
                    $filterName => 'nullable|exists:' . $filter['model'] . ',id',
                ]);
            } elseif ($filter['type'] == 'checkbox') {
                $validator = Validator::make([$filterName => $filterValue], [
                    $filterName => 'nullable|boolean',
                ]);
            } else {
                // free fields from backpack
                switch ($filter['type']) {
                    case 'number':
                        $validator = Validator::make([$filterName => $filterValue], [
                            $filterName => 'nullable|numeric',
                        ]);
                        break;

                    case 'select_from_array':
                        $validator = Validator::make([$filterName => $filterValue], [
                            $filterName => [
                                'nullable',
                                'in:' . implode(',', array_keys($filter['options'])), // Dynamic options here
                            ],
                        ]);
                        break;
                    
                    case 'hidden':
                        // dont validate hidden.
                        break;

                    default:
                        $validator = Validator::make([$filterName => $filterValue], [
                            $filterName => 'nullable|' . $filter['type'],
                        ]);
                        break;
                }
            }

            // append
            if ($validator) {
                if ($validator->fails()) {
                    $validationErrors = array_merge($validationErrors, $validator->errors()->all());
                }
            }
        }

        // Show all validation errors if any
        if (!empty($validationErrors)) {
            \Alert::error($validationErrors)->flash();
            // return redirect()->back();
            return false;
        }

        return true;
        // return redirect()->back()->withInput(request()->input());
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
            if ($this->filterValidations()) {
                if ($callback) {
                    $callback($this->crud->query);
                }
            }

            return redirect()->back()->withInput(request()->input());
        }
    }

    public function setupFilterOperation()
    {
        // example field
        $this->crud->field([
            'name' => 'example',
            'label' => 'Example field',
            'type' => 'select_from_array',
            'options' => [
                1 => 'Lorem',
                2 => 'Ipsum',
            ],
        ]);
    }
}
