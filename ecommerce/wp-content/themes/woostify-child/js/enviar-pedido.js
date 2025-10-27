// assets/js/kitchentag.js
document.addEventListener('DOMContentLoaded', function() {
    // Crear cookie inmediatamente si existe en localStorage
    const restaurantId = localStorage.getItem('restaurant_id');
    if (restaurantId) {
        document.cookie = `restaurant_id=${restaurantId}; path=/; max-age=${60 * 60 * 24 * 7}; SameSite=Lax`; // 7 días de duración
        
        // Opcional: Limpiar localStorage si ya no es necesario
        // localStorage.removeItem('restaurant_id');
    }

    // Backup para el checkout (opcional)
    const placeOrderButton = document.querySelector('#place_order');
    if (placeOrderButton) {
        placeOrderButton.addEventListener('click', function() {
            const currentRestaurantId = localStorage.getItem('restaurant_id') || restaurantId;
            if (currentRestaurantId) {
                document.cookie = `restaurant_id=${currentRestaurantId}; path=/; max-age=300; SameSite=Lax`;
            }
        });
    }
});