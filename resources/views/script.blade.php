{{-- Laravel backpack bp-init function field doest work in list operation so we manually trigger and event to make sure that that checkbox worked --}}
@push('after_scripts')
    @if ($crud->getOperation() == 'list')
        <script>
            $(document).ready(function() {
                // Listen for changes to any checkbox with the class "form-check-input"
                $('input.form-check-input[type="checkbox"]').on('change', function() {
                    // Find the closest hidden input field that is a sibling of the checkbox
                    var hiddenInput = $(this).closest('div.form-check').find('input[type="hidden"]');

                    // Update the value of the hidden input based on the checkbox state
                    if ($(this).is(':checked')) {
                        hiddenInput.val('1'); // Assign 1 if checked
                    } else {
                        hiddenInput.val('0'); // Assign 0 if unchecked
                    }
                });
            });
        </script>
    @endif
@endpush
