@if (isset($crud->settings()['filterOperation.configuration']) || isset($crud->settings()['export.configuration']))

    @if (config('winex01.backpack-filter.filter_button_in_card'))
        <div class="card">
            <div class="card-header" id="filterHeading">
    @endif

    @php
        $filterBtnClass = config('winex01.backpack-filter.filter_button_style');
    @endphp

    @if (isset($crud->settings()['filterOperation.configuration']) && $crud->hasAccess('filters'))
        @if (config('backpack.ui.view_namespace') == 'backpack.theme-coreuiv2::')
            {{-- bootsrap 4 --}}
            <button class="btn {{ $filterBtnClass }} ml-auto" type="button" data-toggle="collapse"
                data-target="#filterForm" aria-expanded="true" aria-controls="filterForm">
                <span class="la la-filter"></span> {{ trans('backpack::crud.filters') }}
            </button>

            @include('winex01.backpack-filter::buttons.filters')
        @else
            {{-- bootstrap 5 --}}
            <button class="btn {{ $filterBtnClass }} ml-auto" type="button" data-bs-toggle="collapse"
                data-bs-target="#filterForm" aria-expanded="true" aria-controls="filterForm">
                <span class="la la-filter"></span> {{ trans('backpack::crud.filters') }}
            </button>
            @include('winex01.backpack-filter::buttons.filters_bootstrap5')
        @endif
    @endif

    @if (isset($crud->settings()['export.configuration']))
        @include('winex01.backpack-filter::buttons.export')
    @endif

    @if (config('winex01.backpack-filter.filter_button_in_card'))
        </div>
        </div>
    @endif


    @stack('list_top_collapse')

@endif
