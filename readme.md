# BackpackFilter

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Total Downloads][ico-downloads]][link-downloads]
[![The Whole Fruit Manifesto](https://img.shields.io/badge/writing%20standard-the%20whole%20fruit-brightgreen)](https://github.com/the-whole-fruit/manifesto)

This package provides a filter functionality for [Backpack for Laravel](https://backpackforlaravel.com/) administration panel. If you don't have the budget or haven't purchased the pro version, this is a great alternative for implementing filters.

## Screenshots

![bootstrap5](https://github.com/user-attachments/assets/537cd5a5-85f1-4bb7-b790-7de7de330d70)
![bootstrap4](https://github.com/user-attachments/assets/b411481d-6ccf-47aa-828a-79e7f2e17b01)

## Theme Supported
- theme-coreuiv2 - YES
- theme-coreuiv4 - YES
- theme-tabler   - YES

## Installation

Via Composer

``` bash
composer require winex01/backpack-filter
```

## Usage

Create a file resources/vendor/backpack/crud/list.blade.php and paste the original backpack file contents. Inside, add this line:

```php
//resources/vendor/backpack/crud/list.blade.php
@include('winex01.backpack-filter::buttons.list_top_collapse')

{{-- Backpack List Filters --}}
// some code here...
```
OR you can download the file here:
[list.blade.php](https://github.com/Laravel-Backpack/CRUD/blob/main/src/resources/views/crud/list.blade.php)
```php
//line 51
@include('winex01.backpack-filter::buttons.list_top_collapse')
```

To use the filter this package provides, inside your EntityCrudController do:

```php

class EntityCrudController extends CrudController
{
    use \Winex01\BackpackFilter\Http\Controllers\Operations\FilterOperation;

    // method setup....

    public function setupFilterOperation()
    {
        $this->crud->field([
            'name' => 'status',
            'label' => __('Status'),
            'type' => 'select',
            'options' => [
                1 => 'Connected',
                2 => 'Disconnected'
            ],
            // 'class-col' => 'col-2', Optional: default length is col-2 
        ]);
    
        $this->crud->field([
            'name' => 'date_range',
            'label' => __('Date Range'),
            'type' => 'date_range',
            // 'class-col' => 'col-3', Optional: default length is col-3
        ]);
    }
```

To apply the filter field into queries, inside your setupListOperation:

```php
public function setupListOperation()
{
    // if you use this method closure, validation is automatically applied.
    $this->filterQueries(function ($query) {
        $status = request()->input('status');
        $dates = request()->input('date_range');

        if ($status) {
            $query->where('status_id', $status);
        }

        if ($dates) {
            $dates = explode('-', $dates);
            //$query->where... your clause here or scope.
        }
    });

    // some code here... add column etc...
}
```

If you want to make your own validation:
```php
public function filterValidations()
{   
    // If no access to filters, then don't proceed but don't show an error.
    if (!$this->crud->hasAccess('filters')) {
        return false;
    }

    // if you dont want to use validator and want to use request file, modify below, up to you.

    $validationErrors = [];

    // validator here.

    // Show all validation errors if any
    if (!empty($validationErrors)) {
        \Alert::error($validationErrors);
        return false;
    }

    return true;
}
```

This package also provides with export using https://laravel-excel.com/, this operation automatically add entity/export route, be sure you have EntityExport.php file in your export directory. 
example if you have UserCrudController, you must have app/Exports/UserExport.php file. Also if you have an active filters it will also apply into the export.
```php
// crud controller
class UserCrudController extends CrudController
{
    use \Winex01\BackpackFilter\Http\Controllers\Operations\ExportOperation;

    // Optional: if you dont want to use the entity/export or user/export convention you can override the export route:
    public function exportRoute()
    {
        return route('test.export');; // if you define a route here then it will use instead of the auto
    }    

    // setup method...
}

```

## Change log

Changes are documented here on Github. Please see the [Releases tab](https://github.com/winex01/backpack-filter/releases).

## Testing

``` bash
composer test
```

## Contributing

Please see [contributing.md](contributing.md) for a todolist and howtos.

## Security

If you discover any security related issues, please email winnie131212592@gmail.com instead of using the issue tracker.

## Credits

- [Winnie A. Damayo][link-author]
- [All Contributors][link-contributors]

## License

This project was released under MIT, so you can install it on top of any Backpack & Laravel project. Please see the [license file](license.md) for more information. 

However, please note that you do need Backpack installed, so you need to also abide by its [YUMMY License](https://github.com/Laravel-Backpack/CRUD/blob/master/LICENSE.md). That means in production you'll need a Backpack license code. You can get a free one for non-commercial use (or a paid one for commercial use) on [backpackforlaravel.com](https://backpackforlaravel.com).


[ico-version]: https://img.shields.io/packagist/v/winex01/backpack-filter.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/winex01/backpack-filter.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/winex01/backpack-filter
[link-downloads]: https://packagist.org/packages/winex01/backpack-filter
[link-author]: https://github.com/winex01
[link-contributors]: ../../contributors
