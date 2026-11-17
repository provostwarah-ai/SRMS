<?php require 'config.php';need('admin');$msg='';
if($_SERVER['REQUEST_METHOD']==='POST'){chk();$a=$_POST['a'];
 try{
 if($a==='stu'){$pdo->prepare('INSERT INTO students(reg_no,name,class,password) VALUES(?,?,?,?)')->execute([trim($_POST['reg']),trim($_POST['name']),trim($_POST['class']),password_hash(trim($_POST['reg']),PASSWORD_DEFAULT)]);$msg='Student added (password = reg. no.).';}
 if($a==='sub'){$pdo->prepare('INSERT INTO subjects(name) VALUES(?)')->execute([trim($_POST['name'])]);$msg='Subject added.';}
 if($a==='res'){$ca=min(40,max(0,(int)$_POST['ca']));$ex=min(60,max(0,(int)$_POST['exam']));
  $pdo->prepare('REPLACE INTO results(student_id,subject_id,session,term,ca,exam) VALUES(?,?,?,?,?,?)')->execute([(int)$_POST['sid'],(int)$_POST['sub'],trim($_POST['session']),(int)$_POST['term'],$ca,$ex]);$msg='Result saved.';}
 if($a==='del'){$pdo->prepare('DELETE FROM students WHERE id=?')->execute([(int)$_POST['id']]);$msg='Student deleted.';}
 }catch(PDOException $x){$msg='Error: duplicate or invalid data.';}
}
$st=$pdo->query('SELECT * FROM students ORDER BY class,name')->fetchAll();$sb=$pdo->query('SELECT * FROM subjects ORDER BY name')->fetchAll();
$rs=$pdo->query('SELECT r.*,s.name sn,s.reg_no,b.name bn FROM results r JOIN students s ON s.id=r.student_id JOIN subjects b ON b.id=r.subject_id ORDER BY r.id DESC LIMIT 20')->fetchAll();?>
<!doctype html><html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Admin</title><link rel="stylesheet" href="style.css"></head><body>
<nav><b>SRMS Admin</b><a href="logout.php">Logout</a></nav><main class="wrap">
<?php if($msg)echo '<p class="ok">'.e($msg).'</p>'?>
<div class="grid">
<section class="card"><h2>Add Student</h2><form method="post"><input type="hidden" name="t" value="<?=token()?>"><input type="hidden" name="a" value="stu">
<input name="reg" placeholder="Reg. No." required><input name="name" placeholder="Full name" required><input name="class" placeholder="Class e.g. JSS1" required><button>Add</button></form></section>
<section class="card"><h2>Add Subject</h2><form method="post"><input type="hidden" name="t" value="<?=token()?>"><input type="hidden" name="a" value="sub">
<input name="name" placeholder="Subject" required><button>Add</button></form></section>
<section class="card"><h2>Enter Result</h2><form method="post"><input type="hidden" name="t" value="<?=token()?>"><input type="hidden" name="a" value="res">
<select name="sid" required><?php foreach($st as $s)echo '<option value="'.$s['id'].'">'.e($s['name']).' ('.e($s['reg_no']).')</option>'?></select>
<select name="sub" required><?php foreach($sb as $s)echo '<option value="'.$s['id'].'">'.e($s['name']).'</option>'?></select>
<input name="session" placeholder="Session 2025/2026" required><select name="term"><option>1</option><option>2</option><option>3</option></select>
<input type="number" name="ca" placeholder="CA (max 40)" min="0" max="40" required><input type="number" name="exam" placeholder="Exam (max 60)" min="0" max="60" required><button>Save</button></form></section></div>
<section class="card"><h2>Students (<?=count($st)?>)</h2><div class="scroll"><table><tr><th>Reg</th><th>Name</th><th>Class</th><th></th></tr>
<?php foreach($st as $s)echo '<tr><td>'.e($s['reg_no']).'</td><td>'.e($s['name']).'</td><td>'.e($s['class']).'</td><td><form method="post" onsubmit="return confirm(\'Delete?\')"><input type="hidden" name="t" value="'.token().'"><input type="hidden" name="a" value="del"><input type="hidden" name="id" value="'.$s['id'].'"><button class="x">Delete</button></form></td></tr>'?></table></div></section>
<section class="card"><h2>Recent Results</h2><div class="scroll"><table><tr><th>Student</th><th>Subject</th><th>Sess/Term</th><th>CA</th><th>Exam</th><th>Total</th><th>Gr</th></tr>
<?php foreach($rs as $r){$t=$r['ca']+$r['exam'];echo '<tr><td>'.e($r['sn']).'</td><td>'.e($r['bn']).'</td><td>'.e($r['session']).' T'.$r['term'].'</td><td>'.$r['ca'].'</td><td>'.$r['exam'].'</td><td>'.$t.'</td><td>'.grade($t).'</td></tr>';}?></table></div></section></main></body></html>
