function mostrarPackageAmount(productos) {
  let producto = document.getElementById("producto");
  let packageAmount = document.getElementById("tipo_cantidad");

  console.log(productos);

  producto.addEventListener("change", function () {
    productos.forEach((prod) => {
      if (prod.ID == producto.value) {
        // console.log(prod.atr_valores_tienda);

        let atr_valores = prod.atr_valores_tienda
          .split(",")
          .map((valor) => valor.trim());
        console.log(atr_valores);
        atr_valores.forEach((valor) => {
          let option = document.createElement("option");
          option.value = valor;
          option.text = valor;
          packageAmount.appendChild(option);
        });
      }
    });
  });
}
