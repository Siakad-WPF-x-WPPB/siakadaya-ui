$(document).ready(function() {
    console.log('Jadwal form script loaded');

    // Wait for form-layouts.js to initialize Select2
    setTimeout(function() {
        initializeDynamicSelection();
    }, 1500);
});

function initializeDynamicSelection() {
    console.log('Initializing dynamic selection...');

    // Remove any existing event handlers to prevent duplicates
    $('#prodi').off('change.dynamicSelection select2:select.dynamicSelection select2:clear.dynamicSelection');

    // Bind events with namespace to prevent conflicts
    $('#prodi').on('change.dynamicSelection select2:select.dynamicSelection select2:clear.dynamicSelection', function(e) {
        console.log('Prodi changed:', $(this).val());
        handleProdiChange($(this).val());
    });

    // For edit form: trigger change if program studi is already selected
    if (typeof jadwalProdiId !== 'undefined' && jadwalProdiId) {
        console.log('Edit form detected, triggering change for prodi:', jadwalProdiId);
        setTimeout(function() {
            $('#prodi').trigger('change.dynamicSelection');
        }, 500);
    }

    console.log('Dynamic selection setup complete!');
}

function handleProdiChange(prodiId) {
    const kelasSelect = $('#kelas');
    const matakuliahSelect = $('#matakuliah');

    if (prodiId) {
        // Load both kelas and mata kuliah
        loadKelasByProdi(prodiId);
        loadMatakuliahByProdi(prodiId);
    } else {
        // Reset both dropdowns
        resetDropdown(kelasSelect, 'Pilih Program Studi terlebih dahulu');
        resetDropdown(matakuliahSelect, 'Pilih Program Studi terlebih dahulu');
    }
}

function loadKelasByProdi(prodiId) {
    const kelasSelect = $('#kelas');

    // Clear kelas dropdown
    kelasSelect.empty();
    kelasSelect.prop('disabled', true);

    console.log('Fetching classes for prodi ID:', prodiId);

    // Show loading state
    kelasSelect.append('<option value="">Loading...</option>');

    // Destroy existing Select2 instance
    if (kelasSelect.hasClass('select2-hidden-accessible')) {
        kelasSelect.select2('destroy');
    }

    // Make AJAX request
    $.ajax({
        url: `/admin/jadwal-kuliah/kelas-by-prodi/${prodiId}`,
        type: 'GET',
        dataType: 'json',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            console.log('Kelas AJAX Success:', response);

            kelasSelect.empty();
            kelasSelect.append('<option value="">Pilih Kelas</option>');

            if (response.success && response.data && response.data.length > 0) {
                $.each(response.data, function(index, kelas) {
                    kelasSelect.append(`<option value="${kelas.id}">${kelas.display_name}</option>`);
                });
                console.log(`Loaded ${response.data.length} classes`);
            } else {
                kelasSelect.append('<option value="">Tidak ada kelas tersedia</option>');
                console.log('No classes found for this program studi');
            }

            // Enable dropdown and reinitialize Select2
            kelasSelect.prop('disabled', false);
            kelasSelect.select2({
                allowClear: true,
                placeholder: 'Pilih Kelas'
            });

            // Select current kelas if in edit mode
            if (typeof jadwalKelasId !== 'undefined' && jadwalKelasId) {
                kelasSelect.val(jadwalKelasId).trigger('change');
            }
        },
        error: function(xhr, status, error) {
            console.error('Kelas AJAX Error:', xhr.responseText);

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
}

function loadMatakuliahByProdi(prodiId) {
    const matakuliahSelect = $('#matakuliah');

    // Clear mata kuliah dropdown
    matakuliahSelect.empty();
    matakuliahSelect.prop('disabled', true);

    console.log('Fetching mata kuliah for prodi ID:', prodiId);

    // Show loading state
    matakuliahSelect.append('<option value="">Loading...</option>');

    // Destroy existing Select2 instance
    if (matakuliahSelect.hasClass('select2-hidden-accessible')) {
        matakuliahSelect.select2('destroy');
    }

    // Make AJAX request
    $.ajax({
        url: `/admin/jadwal-kuliah/matakuliah-by-prodi/${prodiId}`,
        type: 'GET',
        dataType: 'json',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            console.log('Matakuliah AJAX Success:', response);

            matakuliahSelect.empty();
            matakuliahSelect.append('<option value="">Pilih Mata Kuliah</option>');

            if (response.success && response.data && response.data.length > 0) {
                $.each(response.data, function(index, mk) {
                    matakuliahSelect.append(`<option value="${mk.id}">${mk.display_name}</option>`);
                });
                console.log(`Loaded ${response.data.length} mata kuliah`);
            } else {
                matakuliahSelect.append('<option value="">Tidak ada mata kuliah tersedia</option>');
                console.log('No mata kuliah found for this program studi');
            }

            // Enable dropdown and reinitialize Select2
            matakuliahSelect.prop('disabled', false);
            matakuliahSelect.select2({
                allowClear: true,
                placeholder: 'Pilih Mata Kuliah'
            });

            // Select current mata kuliah if in edit mode
            if (typeof jadwalMatakuliahId !== 'undefined' && jadwalMatakuliahId) {
                matakuliahSelect.val(jadwalMatakuliahId).trigger('change');
            }
        },
        error: function(xhr, status, error) {
            console.error('Matakuliah AJAX Error:', xhr.responseText);

            matakuliahSelect.empty();
            matakuliahSelect.append('<option value="">Error loading mata kuliah</option>');
            matakuliahSelect.prop('disabled', false);

            // Reinitialize Select2 even on error
            matakuliahSelect.select2({
                allowClear: true,
                placeholder: 'Error loading mata kuliah'
            });
        }
    });
}

function resetDropdown(selectElement, placeholder) {
    // Clear dropdown
    selectElement.empty();
    selectElement.prop('disabled', true);

    // Add placeholder
    selectElement.append(`<option value="">${placeholder}</option>`);

    // Destroy and reinitialize Select2
    if (selectElement.hasClass('select2-hidden-accessible')) {
        selectElement.select2('destroy');
    }
    selectElement.select2({
        allowClear: true,
        placeholder: placeholder
    });

    console.log('Dropdown reset:', selectElement.attr('id'));
}
