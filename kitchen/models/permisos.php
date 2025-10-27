<?php

class Permiso {
    private $id;
    private $nombre;
    private $shopWeb;
    private $systemTag;
    private $dashboardProd;
    private $restaurant;
    private $dashboardGen;
    private $shopBackoffice;






// Constructor
public function __construct(
    int $id = 0,
    string $nombre = "",
    int $shopWeb = 0,
    int $systemTag = 0,
    int $dashboardProd = 0,
    int $restaurant = 0,
    int $dashboardGen = 0,
    int $shopBackoffice = 0
) {
    $this->id = $id;
    $this->nombre = $nombre;
    $this->shopWeb = $shopWeb;
    $this->systemTag = $systemTag;
    $this->dashboardProd = $dashboardProd;
    $this->restaurant = $restaurant;
    $this->dashboardGen = $dashboardGen;
    $this->shopBackoffice = $shopBackoffice;
}













    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    public function getShopWeb() {
        return $this->shopWeb;
    }

    public function setShopWeb($shopWeb) {
        $this->shopWeb = $shopWeb;
    }

    public function getSystemTag() {
        return $this->systemTag;
    }

    public function setSystemTag($systemTag) {
        $this->systemTag = $systemTag;
    }

    public function getDashboardProd() {
        return $this->dashboardProd;
    }

    public function setDashboardProd($dashboardProd) {
        $this->dashboardProd = $dashboardProd;
    }

    public function getRestaurant() {
        return $this->restaurant;
    }

    public function setRestaurant($restaurant) {
        $this->restaurant = $restaurant;
    }

    public function getDashboardGen() {
        return $this->dashboardGen;
    }

    public function setDashboardGen($dashboardGen) {
        $this->dashboardGen = $dashboardGen;
    }

    public function getShopBackoffice() {
        return $this->shopBackoffice;
    }

    public function setShopBackoffice($shopBackoffice) {
        $this->shopBackoffice = $shopBackoffice;
    }
}

?>
