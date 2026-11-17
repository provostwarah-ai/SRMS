<?php
// Run once in browser, then DELETE this file.
$p=new PDO('mysql:host=localhost;charset=utf8mb4','root','',[PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION]);
$p->exec(file_get_contents(__DIR__.'/schema.sql'));
$p->exec('USE srms');
$s=$p->prepare('INSERT IGNORE INTO admins(username,password) VALUES(?,?)');
$s->execute(['admin',password_hash('admin123',PASSWORD_DEFAULT)]);
echo 'Done. Login: admin / admin123 (change it). Delete setup.php now.';
