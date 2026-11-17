<?php require 'config.php';
$err='';
if($_SERVER['REQUEST_METHOD']==='POST'){chk();
 $u=trim($_POST['u']);$p=$_POST['p'];
 if($_POST['role']==='admin'){$q=$pdo->prepare('SELECT * FROM admins WHERE username=?');$q->execute([$u]);$r=$q->fetch();
  if($r&&password_verify($p,$r['password'])){session_regenerate_id(true);$_SESSION['role']='admin';$_SESSION['id']=$r['id'];header('Location: admin.php');exit;}}
 else{$q=$pdo->prepare('SELECT * FROM students WHERE reg_no=?');$q->execute([$u]);$r=$q->fetch();
  if($r&&password_verify($p,$r['password'])){session_regenerate_id(true);$_SESSION['role']='student';$_SESSION['id']=$r['id'];header('Location: student.php');exit;}}
 $err='Invalid login details.';
}?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Computerized Student Result Management System</title><link rel="stylesheet" href="style.css"></head><body>
<header class="hero"><h1>Design and Implementation of a Computerized Student Result Management System</h1>
<p>A web-based system that replaces manual result processing with fast, accurate, secure and instantly accessible results.</p></header>
<main class="wrap grid">
<section class="card"><h2>About the Project</h2>
<p>Manual result compilation is slow and error-prone. This system lets staff record scores once; totals, grades, averages and class positions are computed automatically, and students view or print their results online.</p>
<h3>Objectives</h3><ul><li>Eliminate calculation errors in result processing</li><li>Store student records and scores securely in a database</li><li>Give students instant, private access to results</li><li>Generate printable result slips</li></ul>
<h3>Technology</h3><p><span class="tag">HTML/CSS</span><span class="tag">PHP</span><span class="tag">MySQL</span><span class="tag">PDO</span><span class="tag">Sessions</span></p>
<h3>Modules</h3><ul><li><b>Admin:</b> add students and subjects, enter/update scores</li><li><b>Student:</b> login with reg. number, view and print result by session/term</li></ul></section>
<section class="card"><h2>Login</h2><?php if($err)echo '<p class="err">'.e($err).'</p>'?>
<form method="post"><input type="hidden" name="t" value="<?=token()?>">
<label>Login as</label><select name="role"><option value="student">Student</option><option value="admin">Admin</option></select>
<label>Username / Reg. No.</label><input name="u" required><label>Password</label><input type="password" name="p" required>
<button>Login</button></form></section></main>
<footer>&copy; <?=date('Y')?> Student Result Management System</footer></body></html>
