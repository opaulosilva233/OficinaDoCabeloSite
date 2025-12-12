<?php
$files = [
    'https://raw.githubusercontent.com/PHPMailer/PHPMailer/master/src/PHPMailer.php' => 'includes/phpmailer/PHPMailer.php',
    'https://raw.githubusercontent.com/PHPMailer/PHPMailer/master/src/SMTP.php' => 'includes/phpmailer/SMTP.php',
    'https://raw.githubusercontent.com/PHPMailer/PHPMailer/master/src/Exception.php' => 'includes/phpmailer/Exception.php'
];

if (!is_dir('includes/phpmailer')) {
    mkdir('includes/phpmailer', 0777, true);
}

foreach ($files as $url => $path) {
    echo "Downloading $url to $path...\n";
    $content = file_get_contents($url);
    if ($content === false) {
        echo "Failed to download $url\n";
        exit(1);
    }
    file_put_contents($path, $content);
    echo "Success!\n";
}

echo "All files downloaded successfully.\n";
?>
