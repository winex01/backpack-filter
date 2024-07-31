@if (
    isset($crud->settings()['filterOperation.configuration']) ||
    isset($crud->settings()['export.configuration'])
)
    <div class="card">
        <div class="card-header" id="filterHeading">

            @if(isset($crud->settings()['filterOperation.configuration']))
                @include('winex01.backpack-filter::buttons.filters')
            @endif

            @if(isset($crud->settings()['export.configuration']))
                @include('winex01.backpack-filter::buttons.export')
            @endif


        </div>
    </div>

    @stack('list_top_collapse')

@endif