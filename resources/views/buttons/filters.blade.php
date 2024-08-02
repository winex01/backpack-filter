@if($crud->hasAccess('filters'))
    <button class="btn btn-link ml-auto" type="button" data-toggle="collapse" data-target="#filterForm" aria-expanded="true" aria-controls="filterForm">
        <span class="la la-filter"></span> {{ trans('backpack::crud.filters') }}  
    </button>

    @push('list_top_collapse')
        <div id="filterForm" class="collapse" aria-labelledby="filterHeading">
            <div class="card card-body">
                <form action="{{ url($crud->route) }}" method="GET">

                    @php
                        $chunkedFilters = collect($crud->filterLists())->chunk(3);
                    @endphp

                    @foreach ($chunkedFilters as $filterChunk)

                        <div class="row">

                            @foreach ($filterChunk as $filter)
                                @include('winex01.backpack-filter::filters.'.$filter['type'])    
                            @endforeach
                            
                        </div>

                    @endforeach

                    <div class="form-group">
                        <a href="{{ url($crud->route) }}" id="remove_filters_button" class="btn btn-secondary">Clear Filters</a>
                        <button type="submit" class="btn btn-primary">Apply Filters</button>
                    </div>

                </form>
            </div>
        </div>
    @endpush

    @push('crud_list_scripts')
    <script>
        // clear filters
        jQuery(document).ready(function($) {
            $("#remove_filters_button").click(function(e) {
            // remove query string
            crud.updateUrl('{{ url($crud->route) }}');
            });
        });

        // dont collapse filter if it has get request query string
        document.addEventListener('DOMContentLoaded', function() {
            // Function to check if URL has query parameters
            function hasQueryParameters() {
                return window.location.search.length > 0;
            }

            // Function to open the collapsible filter form
            function openFilterForm() {
                var filterFormContainer = document.getElementById('filterForm');
                if (filterFormContainer) {
                    new bootstrap.Collapse(filterFormContainer, {
                        toggle: true
                    });
                }
            }

            // Check if there are query parameters and open the filter form
            if (hasQueryParameters()) {
                openFilterForm();
            }
        });

    </script>
    @endpush

@endif
{{-- end @if has access --}}