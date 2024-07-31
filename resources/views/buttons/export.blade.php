@if($crud->hasAccess('export'))
    @php
        $exportRoute = '';

        if ($crud->exportRoute()) {
            $exportRoute = $crud->exportRoute();
        }else {
            $exportRoute = url($crud->route).'/export';
        }

    @endphp

    <button id="export-button" class="btn btn-link ml-n2" type="button">
        <span class="la la-download"></span> {{ __('Export') }}  
    </button>


    @push('crud_list_scripts')
        <script>
            document.getElementById('export-button').addEventListener('click', function() {
                // Get the current URL
                const currentUrl = new URL(window.location.href);
                
                // Get the query parameters from the current URL
                const queryParams = currentUrl.searchParams;
                
                // Create the export URL
                const exportUrl = new URL('{{ $exportRoute }}', window.location.origin);
                
                // Append all current query parameters to the export URL
                queryParams.forEach((value, key) => {
                    exportUrl.searchParams.append(key, value);
                });
                
                // Redirect to the export URL
                window.location.href = exportUrl.toString();
            });
        </script>
    @endpush


@endif