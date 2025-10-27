jQuery(document).ready(function ($) {


      // Eliminar el botón "Vaciar" de todas las variaciones
      $('.reset_variations').remove();
    // Eliminar la opción con valor vacío de los selectores de variaciones
    $('.variations_form select').each(function () {
        $(this).find('option[value=""]').remove();
    });

    // Detectar cuando se actualizan las variaciones y volver a eliminar la opción si es necesario
    $('form.variations_form').on('woocommerce_update_variation_values', function () {
        $(this).find('select').each(function () {
            $(this).find('option[value=""]').remove();
        });
    });





    // Inicialización: obtener el stock máximo dependiendo de si el producto es simple o variable
    function initializeStock() {
        var variation_id = $('form.cart').find('input[name="variation_id"]').val();
        var product_id = customAddToCartParams.product_id;

        if (variation_id && customAddToCartParams.variation_stock[variation_id] !== undefined) {
            var max_stock = customAddToCartParams.variation_stock[variation_id];
        } else if (customAddToCartParams.variation_stock[product_id] !== undefined) {
            var max_stock = customAddToCartParams.variation_stock[product_id];
        } else {
            var max_stock = 0;
        }

        updateStock(max_stock);
    }

    // Actualiza el stock, deshabilita/activa elementos según sea necesario
    function updateStock(max_stock) {
        if (max_stock <= 0) {
            $('#customQuantity').val(0).attr('max', max_stock); // Deshabilitar input
            $('#customAddToCart').attr('disabled', true).addClass('disabled-button'); // Deshabilitar botón
        } else {
            $('#customQuantity').val(1).attr('max', max_stock).attr('disabled', false); // Habilitar input
            $('#customAddToCart').attr('disabled', false).removeClass('disabled-button'); // Habilitar botón
        }
    }

    // Inicializa stock al cargar la página
    initializeStock();

    // Detectar selección de una nueva variación usando found_variation
    $('form.variations_form').on('found_variation', function (event, variation) {
        if (variation && variation.variation_id) {
            var variation_id = variation.variation_id;

            if (customAddToCartParams.variation_stock[variation_id] !== undefined) {
                var max_stock = customAddToCartParams.variation_stock[variation_id];
                updateStock(max_stock);
            }
        }
    });

    // Incrementar cantidad (botón +)
    $('span.add').on('click', function () {
        var input = $(this).closest('.quantity-button').find('#customQuantity');
        var currentVal = parseInt(input.val());
        var max = parseInt(input.attr('max')); // Max es el stock de la variación o producto simple

        if (!input.is(':disabled') && currentVal < max) {
            input.val(currentVal + 1).trigger('change');
        }
    });

    // Decrementar cantidad (botón -)
    $('span.subtract').on('click', function () {
        var input = $(this).closest('.quantity-button').find('#customQuantity');
        var currentVal = parseInt(input.val());

        if (!input.is(':disabled') && currentVal > 1) {
            input.val(currentVal - 1).trigger('change');
        }
    });

    // Agregar al carrito
    $('#customAddToCart').on('click', function () {
        var quantity = $('#customQuantity').val();
        var product_id = customAddToCartParams.product_id;
        var variation_id = $('form.variations_form').find('input.variation_id').val();

        if (!product_id) {
            alert('No se ha definido el ID del producto.');
            return;
        }

        $.ajax({
            url: customAddToCartParams.ajax_url,
            method: 'POST',
            data: {
                action: 'custom_add_to_cart',
                product_id: product_id,
                quantity: quantity,
                variation_id: variation_id || null,
            },
            success: function (response) {
                if (response.success) {
                    $(document.body).trigger('added_to_cart');
                    window.location.href = customAddToCartParams.home_url;
                } else {
                    alert(response.data.message);
                }
            },
            error: function () {
                alert('Hubo un error al procesar la solicitud.');
            },
        });
    });
});
