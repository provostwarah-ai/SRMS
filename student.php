<?php require 'config.php';need('student');
$q=$pdo->prepare('SELECT * FROM students WHERE id=?');$q->execute([$_SESSION['id']]);$me=$q->fetch();
$ss=$pdo->prepare('SELECT DISTINCT session,term FROM results WHERE student_id=? ORDER BY session DESC,term DESC');$ss->execute([$me['id']]);$opts=$ss->fetchAll();
$sess=$_GET['session']??($opts[0]['session']??'');$term=(int)($_GET['term']??($opts[0]['term']??1));
$q=$pdo->prepare('SELECT b.name,r.ca,r.exam FROM results r JOIN subjects b ON b.id=r.subject_id WHERE r.student_id=? AND r.session=? AND r.term=? ORDER BY b.name');$q->execute([$me['id'],$sess,$term]);$rows=$q->fetchAll();
$q=$pdo->prepare('SELECT r.student_id,SUM(r.ca+r.exam) tot FROM results r JOIN students s ON s.id=r.student_id WHERE s.class=? AND r.session=? AND r.term=? GROUP BY r.student_id ORDER BY tot DESC');$q->execute([$me['class'],$sess,$term]);$all=$q->fetchAll();
$pos=0;$n=count($all);foreach($all as $i=>$a)if($a['student_id']==$me['id'])$pos=$i+1;
$total=array_sum(array_map(fn($r)=>$r['ca']+$r['exam'],$rows));$avg=$rows?round($total/count($rows),1):0;?>
<!doctype html><html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>My Result</title><link rel="stylesheet" href="style.css"></head><body>
<nav class="noprint"><b>SRMS</b><a href="logout.php">Logout</a></nav><main class="wrap">
<form class="card noprint" method="get"><label>Select term</label><select onchange="var v=this.value.split('|');location='?session='+v[0]+'&term='+v[1]">
<?php foreach($opts as $o){$v=$o['session'].'|'.$o['term'];echo '<option value="'.e($v).'"'.($o['session']==$sess&&$o['term']==$term?' selected':'').'>'.e($o['session']).' - Term '.$o['term'].'</option>';}?></select></form>
<section class="card"><h2>Result Slip</h2><p><b><?=e($me['name'])?></b><br>Reg. No: <?=e($me['reg_no'])?> | Class: <?=e($me['class'])?><br>Session: <?=e($sess)?> | Term <?=$term?></p>
<?php if(!$rows)echo '<p>No result available yet.</p>';else{?><div class="scroll"><table><tr><th>Subject</th><th>CA (40)</th><th>Exam (60)</th><th>Total</th><th>Grade</th></tr>
<?php foreach($rows as $r){$t=$r['ca']+$r['exam'];echo '<tr><td>'.e($r['name']).'</td><td>'.$r['ca'].'</td><td>'.$r['exam'].'</td><td>'.$t.'</td><td>'.grade($t).'</td></tr>';}?></table></div>
<p><b>Total:</b> <?=$total?> &nbsp; <b>Average:</b> <?=$avg?>% &nbsp; <b>Position:</b> <?=$pos?> of <?=$n?></p>
<button class="noprint" onclick="print()">Print</button><?php }?></section></main></body></html>
