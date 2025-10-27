var adminMenuWrap = document.getElementById('adminmenuwrap');
    
if (adminMenuWrap) {
    var imageElement = document.createElement('img');

    
    imageElement.src = '/ecommerce/wp-content/themes/woostify-child/src/cookcode-Logo2.png';
    imageElement.id = "logoCookCode"

    imageElement.style.width = "101%";
    imageElement.style.height = "auto";
    imageElement.style.border = "solid #005524 3px";
    imageElement.style.marginRight = "2%";
    imageElement.style.backgroundColor = "white";
    imageElement.style.padding = "10px 10px 10px 10px";
    imageElement.style.boxSizing = "border-box";
    adminMenuWrap.insertBefore(imageElement, adminMenuWrap.firstChild);
}
