jQuery(function($) {
    // Detectar el envío del formulario de añadir al carrito
    $('form.cart').on('submit', function(e) {
        e.preventDefault(); // Prevenir el comportamiento por defecto del formulario

        var quantity = $('input.qty').val(); // Obtener la cantidad seleccionada
        var product_id = $('input[name="add-to-cart"]').val(); // Obtener el ID del producto

        // Enviar los datos a través de AJAX
        $.ajax({
            url: wc_add_to_cart_params.ajax_url,
            type: 'POST',
            data: {
                action: 'add_to_cart',
                product_id: product_id,
                quantity: quantity
            },
            success: function(response) {
                alert(response); // Mostrar un mensaje con el resultado
            }
        });
    });
});


