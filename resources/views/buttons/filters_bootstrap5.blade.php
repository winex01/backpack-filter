@push('list_top_collapse')
    <div id="filterForm" class="collapse" aria-labelledby="filterHeading">
        <div class="card card-body">
            <form action="{{ url($crud->route) }}" method="GET">

                @include('winex01.backpack-filter::filters.filter_lists')

                <div class="form-group">
                    <a href="{{ url($crud->route) }}" id="remove_filters_button" class="btn btn-secondary">Clear
                        Filters</a>
                    <button type="submit"
                        class="btn {{ config('winex01.backpack-filter.filter_button_apply_style') }}">Apply
                        Filters</button>
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

        document.addEventListener('DOMContentLoaded', function() {
            // Function to check if URL has query parameters
            function hasQueryParameters() {
                return window.location.search.length > 0;
            }

            // Function to open the collapsible filter form
            function openFilterForm() {
                var filterFormContainer = document.getElementById('filterForm');
                if (filterFormContainer) {
                    var collapseInstance = bootstrap.Collapse.getOrCreateInstance(filterFormContainer, {
                        toggle: true
                    });
                    collapseInstance.show();
                }
            }

            // Check if there are query parameters and open the filter form
            if (hasQueryParameters()) {
                openFilterForm();
            }
        });
    </script>
@endpush
