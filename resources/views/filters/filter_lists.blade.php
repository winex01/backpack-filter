<div class="row">
    @foreach ($crud->filterLists() as $field)

        @if ($field['type'] == 'date_range')
            @include('winex01.backpack-filter::filters.'.$field['type'])    
        @else
            @include('crud::fields.'.$field['type'])    
        @endif
            
    @endforeach
</div>