
import { BASE_URL } from './../../../../config.js';


export function formateoDatosIng(sku) {
    let ingrediente = ingrentesDatosForm();
    let precios = ingrediente.ing_atr_values ? calculatePrices(ingrediente) : [];

    const producto = {
        sku: sku,
        name: ingrediente.ing_name,
        type: 'variable',
        description: `Este ingrediente (${ingrediente.ing_name}) es una parte esencial de nuestras recetas.`,
        short_description: ingrediente.ing_short_description,
        localizacion: ingrediente.ing_warehouse,
        empaquetado: ingrediente.ing_packaging,
        alergeno: ingrediente.ing_alergeno_name,
        type_unit: ingrediente.ing_unit,
        cost_price: ingrediente.ing_cost_price,
        peso: ingrediente.ing_unit_weight,

        images: [
            {
               src: `${BASE_URL}/kitchen/img/ingredients/${document.getElementById('imagenIng').files[0]?.name || 'default.jpg'}`

            }
        ],
        categories: [
            { id: 22 }
        ],
        tags: [
            ingrediente.ing_food_classification
        ],
        attributes: [
            {
                name: ingrediente.ing_atr_name,
                options: ingrediente.ing_atr_values ? ingrediente.ing_atr_values.split(',').map(option => option.trim()) : [],
                variation: true
            }
        ],
        meta_data: [
            { key: 'cost_price', value: ingrediente.ing_cost_price || 0 },
            { key: 'type_unit', value: ingrediente.ing_unit || 'Unidad' },
            { key: 'localizacion', value: ingrediente.ing_warehouse || 'Desconocida' },
            { key: 'empaquetado', value: ingrediente.ing_packaging || 'Desconocido' },
            { key: 'alergeno', value: ingrediente.ing_alergeno_name || 'Desconocido' }

        ],
        manage_stock: false,
        variations: ingrediente.ing_atr_values ? ingrediente.ing_atr_values.split(',').map((valor, index) => ({
            regular_price: precios[index],
            stock_quantity: 0,
            attributes: [
                {
                    name: ingrediente.ing_atr_name,
                    option: valor.trim()
                }
            ]
        })) : []
    };


    return producto;
}


export function ingrentesDatosForm() {
    const ING_NAME = document.getElementById("nameIngredientNewIng").value;
    const ING_MERMA = document.getElementById("mermaNewIng").value;
    const ING_UNIT = document.getElementById("unitNewIng").value;
    const ING_COSTPRICE = document.getElementById("costPriceNewIng").value;
    const ING_SALEPRICE = document.getElementById("salePriceNewIng").value;
    const ING_ALERGENO = document.getElementById("alergenoNewIng").value;
    const ING_EXPIRE = document.getElementById("expireNewIng").value;
    const ING_PACKAGING = document.getElementById("packagingNewIng").value;
    const ING_WAREHOUSE = document.getElementById("warehouseNewIng").value;
    const selectElement = document.getElementById("alergenoNewIng");
    const ING_ATR_NAME = document.getElementById("attributeName").value;
    const ING_ATR_VALUES = document.getElementById("allValues").value;
    const ING_SHORT_DESCRIPTION = document.getElementById("descripcionCortaIng").value;
    const selectedOption = selectElement.options[selectElement.selectedIndex];
    const ING_ALERGENO_ID = selectedOption.dataset.id;
    let ING_UNIT_WEIGHT = 1;

    if (ING_UNIT == "Und" || ING_UNIT == "L") {

        ING_UNIT_WEIGHT = document.getElementById("unitWeight").value;

    }

    const ING_FOOD_CLASSIFICATION = document.getElementById("foodClassificationIng").value;

    const INGREDIENTE = {
        ing_name: ING_NAME,
        ing_merma: ING_MERMA,
        ing_unit: ING_UNIT,
        ing_unit_weight: ING_UNIT_WEIGHT,
        ing_cost_price: ING_COSTPRICE,
        ing_sale_price: ING_SALEPRICE,
        ing_expire: ING_EXPIRE,
        ing_packaging: ING_PACKAGING,
        ing_warehouse: ING_WAREHOUSE,
        ing_alergeno_id: ING_ALERGENO_ID,
        ing_alergeno_name: ING_ALERGENO,
        ing_atr_name: ING_ATR_NAME,
        ing_atr_values: ING_ATR_VALUES,
        ing_short_description: ING_SHORT_DESCRIPTION,
        ing_food_classification: ING_FOOD_CLASSIFICATION

    };

    return INGREDIENTE;
}


function calculatePrices(ingrediente) {
    if (!ingrediente.ing_atr_values) return [];

    return ingrediente.ing_atr_values.split(',')
        .map(valor => parseFloat(valor.trim()))
        .filter(cantidad => !isNaN(cantidad))
        .map(cantidad => ingrediente.ing_sale_price * cantidad);
}