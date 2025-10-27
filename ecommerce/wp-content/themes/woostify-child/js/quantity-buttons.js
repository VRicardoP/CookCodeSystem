jQuery(function ($) {
    // Obtener el stock máximo desde PHP
    var maxStock = parseInt(productData.maxStock, 10) || 0;

    // Incrementar cantidad
    $('span.add').on('click', function () {
        var input = $(this).closest('.quantity-button').find('input.input-button');
        var currentVal = parseInt(input.val(), 10); // Convertir a entero
        if (!isNaN(currentVal) && currentVal < maxStock) {
            input.val(currentVal + 1).change(); // Incrementar en 1
        }
    });

    // Decrementar cantidad
    $('span.subtract').on('click', function () {
        var input = $(this).closest('.quantity-button').find('input.input-button');
        var currentVal = parseInt(input.val(), 10); // Convertir a entero
        if (!isNaN(currentVal) && currentVal > 0) {
            input.val(Math.max(0, currentVal - 1)).change(); // Decrementar en 1
        }
    });

    // Limitar manualmente el valor máximo en el campo
    $('input.input-button').on('input', function () {
        var currentVal = parseInt($(this).val(), 10);
        if (currentVal > maxStock) {
            $(this).val(maxStock); // Ajustar al máximo permitido
        } else if (currentVal < 0 || isNaN(currentVal)) {
            $(this).val(0); // Ajustar al mínimo permitido
        }
    });
});


    
