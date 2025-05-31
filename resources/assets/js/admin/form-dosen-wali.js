$(document).ready(function () {
  console.log('Dosen wali form script loaded');

  // Wait for form-layouts.js to initialize Select2
  setTimeout(function () {
    initializeDynamicWaliSelection();
  }, 1500);
});

function initializeDynamicWaliSelection() {
  console.log('Initializing dynamic wali class selection...');

  // Remove any existing event handlers to prevent duplicates
  $('#is_wali').off('change.dynamicWali select2:select.dynamicWali select2:clear.dynamicWali');

  // Bind events with namespace to prevent conflicts
  $('#is_wali').on('change.dynamicWali select2:select.dynamicWali select2:clear.dynamicWali', function (e) {
    console.log('Is wali changed:', $(this).val());
    handleWaliChange($(this).val());
  });

  // For edit form: trigger change if is_wali is already selected
  if (typeof dosenIsWali !== 'undefined' && dosenIsWali) {
    handleWaliChange(dosenIsWali);
  } else {
    // Check current selected value on page load
    const currentWaliValue = $('#is_wali').val();
    if (currentWaliValue) {
      handleWaliChange(currentWaliValue);
    }
  }

  console.log('Dynamic wali class selection setup complete!');
}

function handleWaliChange(isWali) {
  const kelasSelect = $('#kelas_id');
  const kelasContainer = $('#kelas-container');

  if (isWali == '1') {
    // Show kelas container and enable select
    kelasContainer.show();
    kelasSelect.prop('disabled', false);

    // Reinitialize Select2 after enabling
    if (kelasSelect.hasClass('select2-hidden-accessible')) {
      kelasSelect.select2('destroy');
    }
    kelasSelect.select2({
      allowClear: true,
      placeholder: 'Pilih Kelas'
    });

    console.log('Kelas selection enabled');
  } else {
    // Hide kelas container and disable select
    kelasContainer.hide();
    kelasSelect.prop('disabled', true);
    kelasSelect.val('').trigger('change');

    console.log('Kelas selection disabled');
  }
}
