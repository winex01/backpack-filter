# BackpackFilter

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Total Downloads][ico-downloads]][link-downloads]
[![The Whole Fruit Manifesto](https://img.shields.io/badge/writing%20standard-the%20whole%20fruit-brightgreen)](https://github.com/the-whole-fruit/manifesto)

This package provides a filter functionality for [Backpack for Laravel](https://backpackforlaravel.com/) administration panel. If you don't have the budget or haven't purchased the pro version, this is a great alternative for implementing filters.

## Screenshots

![Screenshot_12](https://github.com/user-attachments/assets/b411481d-6ccf-47aa-828a-79e7f2e17b01)


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

    protected function setupFilterOperation()
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
protected function setupListOperation()
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
            //$query->where... you clause here or scope.
        }
    });

    // some code here... add column etc...
}
```

If you want to make your own validation:
```php
protected function filterValidations()
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

## Overwriting

> **// TODO: explain to your users how to overwrite the functionality this package provides;**
> we've provided an example for a custom field

If you need to change the field in any way, you can easily publish the file to your app, and modify that file any way you want. But please keep in mind that you will not be getting any updates.

**Step 1.** Copy-paste the blade file to your directory:
```bash
# create the fields directory if it's not already there
mkdir -p resources/views/vendor/backpack/crud/fields

# copy the blade file inside the folder we created above
cp -i vendor/winex01/backpack-filter/src/resources/views/fields/field_name.blade.php resources/views/vendor/backpack/crud/fields/field_name.blade.php
```

**Step 2.** Remove the vendor namespace wherever you've used the field:
```diff
$this->crud->addField([
    'name' => 'agreed',
    'type' => 'toggle',
    'label' => 'I agree to the terms and conditions',
-   'view_namespace' => 'winex01.backpack-filter::fields'
]);
```

**Step 3.** Uninstall this package. Since it only provides one file, and you're no longer using that file, it makes no sense to have the package installed:
```bash
composer remove winex01/backpack-filter
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
