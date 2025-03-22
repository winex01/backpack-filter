@php
    $dateRangeFormat = isset($field['format']) ? $field['format'] : config('backpack.ui.default_date_format');
@endphp
@include('crud::fields.inc.wrapper_start')
    <label>{!! $field['label'] !!}</label>
    <div class="input-group date">
        <input
            id="{{ $field['name'] }}"
            name="{{ $field['name'] }}"
            class="form-control"
            autocomplete="off"
            value="{{ Request::get($field['name']) ? Request::get($field['name']) : '' }}"
            type="text"
            @include('crud::fields.inc.attributes')
            >
        	<div class="input-group-append">
	            <span class="input-group-text">
                <span class="la la-calendar"></span>
            </span>
        </div>
    </div>

    {{-- HINT --}}
    @if (isset($field['hint']))
        <p class="help-block">{!! $field['hint'] !!}</p>
    @endif
@include('crud::fields.inc.wrapper_end')


@push('crud_list_styles')
  <link rel="stylesheet" type="text/css" href="{{ basset('https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css') }}" />
@endpush

@push('crud_list_scripts')
<script type="text/javascript" src="{{ basset('https://cdn.jsdelivr.net/momentjs/latest/moment.min.js') }}"></script>
<script type="text/javascript" src="{{ basset('https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js') }}"></script>

<script>
    $('input[name="{{ $field['name'] }}"]').daterangepicker({
        opens: 'right',
        autoUpdateInput: false, // Prevent automatic input update
        locale: {
            cancelLabel: 'Clear', // Customize clear button text
            format: '{{ $dateRangeFormat }}' // Adjust date format as needed
        }
    });

    // Handle clear button click event
    $('input[name="{{ $field['name'] }}"]').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('{{ $dateRangeFormat }}') + ' - ' + picker.endDate.format('{{ $dateRangeFormat }}'));
    }).on('cancel.daterangepicker', function(ev, picker) {
        $(this).val(''); // Clear the input value when canceling selection
    });
</script>

@endpush
