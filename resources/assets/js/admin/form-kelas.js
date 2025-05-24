$(document).ready(function() {
    // console.log('Mahasiswa form script loaded');

    // Wait for form-layouts.js to initialize Select2
    setTimeout(function() {
        initializeDynamicClassSelection();
    }, 1500);
});

function initializeDynamicClassSelection() {
    // console.log('Initializing dynamic class selection...');

    // Remove any existing event handlers to prevent duplicates
    $('#prodi').off('change.dynamicClass select2:select.dynamicClass select2:clear.dynamicClass');

    // Bind events with namespace to prevent conflicts
    $('#prodi').on('change.dynamicClass select2:select.dynamicClass select2:clear.dynamicClass', function(e) {
        // console.log('Prodi changed:', $(this).val());
        handleProdiChange($(this).val());
    });

    // For edit form: trigger change if program studi is already selected
    if (typeof mahasiswaProdiId !== 'undefined' && mahasiswaProdiId) {
        // console.log('Edit form detected, triggering change for prodi:', mahasiswaProdiId);
        setTimeout(function() {
            $('#prodi').trigger('change.dynamicClass');
        }, 500);
    }

    // console.log('Dynamic class selection setup complete!');
}

function handleProdiChange(prodiId) {
    const kelasSelect = $('#kelas');

    // Clear kelas dropdown
    kelasSelect.empty();
    kelasSelect.prop('disabled', true);

    if (prodiId) {
        // console.log('Fetching classes for prodi ID:', prodiId);

        // Show loading state
        kelasSelect.append('<option value="">Loading...</option>');

        // Destroy existing Select2 instance
        if (kelasSelect.hasClass('select2-hidden-accessible')) {
            kelasSelect.select2('destroy');
        }

        // Make AJAX request
        $.ajax({
            url: `/admin/mahasiswa/kelas-by-prodi/${prodiId}`,
            type: 'GET',
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                // console.log('AJAX Success:', response);

                kelasSelect.empty();
                kelasSelect.append('<option value="">Pilih Kelas</option>');

                if (response.success && response.data && response.data.length > 0) {
                    $.each(response.data, function(index, kelas) {
                        kelasSelect.append(`<option value="${kelas.id}">${kelas.pararel}</option>`);
                    });
                    // console.log(`Loaded ${response.data.length} classes`);
                } else {
                    kelasSelect.append('<option value="">Tidak ada kelas tersedia</option>');
                    // console.log('No classes found for this program studi');
                }

                // Enable dropdown and reinitialize Select2
                kelasSelect.prop('disabled', false);
                kelasSelect.select2({
                    allowClear: true,
                    placeholder: 'Pilih Kelas'
                });
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:');
                console.error('Status:', status);
                console.error('Error:', error);
                console.error('Response Text:', xhr.responseText);

                kelasSelect.empty();
                kelasSelect.append('<option value="">Error loading classes</option>');
                kelasSelect.prop('disabled', false);

                // Reinitialize Select2 even on error
                kelasSelect.select2({
                    allowClear: true,
                    placeholder: 'Error loading classes'
                });
            }
        });
    } else {
        // No prodi selected
        kelasSelect.append('<option value="">Pilih Program Studi terlebih dahulu</option>');

        // Destroy and reinitialize Select2
        if (kelasSelect.hasClass('select2-hidden-accessible')) {
            kelasSelect.select2('destroy');
        }
        kelasSelect.select2({
            allowClear: true,
            placeholder: 'Pilih Program Studi terlebih dahulu'
        });

        // console.log('No prodi selected, kelas dropdown disabled');
    }
}
