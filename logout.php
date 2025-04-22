<?php
/**
 * logout.php
 * Este script é responsável por terminar a sessão do utilizador e redirecioná-lo para a página inicial.
 */

// Iniciar a sessão para poder aceder às variáveis de sessão
session_start();

// Destruir todas as variáveis de sessão
session_unset();

// Destruir a sessão
session_destroy();

// Redirecionar o utilizador para a página inicial
header("Location: ./index.php");
exit();
?>