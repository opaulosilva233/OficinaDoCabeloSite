<?php
class PageController {
    public function home() {
        require_once __DIR__ . '/../../views/home.php';
    }

    public function about() {
        require_once __DIR__ . '/../../views/sobre.php';
    }

    public function contact() {
        require_once __DIR__ . '/../../views/contacto.php';
    }

    public function rgpd() {
        require_once __DIR__ . '/../../views/rgpd.php';
    }
    
    public function booking() {
        require_once __DIR__ . '/../../views/marcacoes.php';
    }
}
?>
