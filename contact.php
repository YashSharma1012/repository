<?php
$status = '';
$errors = [];
$v = ['name'=>'','email'=>'','phone'=>'','company'=>'','service'=>'','budget'=>'','message'=>''];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (!empty($_POST['website'])) { exit; }
  foreach ($v as $k=>$_) { $v[$k] = trim(strip_tags($_POST[$k] ?? '')); }
  if ($v['name']==='') $errors[]='Please enter your name.';
  if (!filter_var($v['email'], FILTER_VALIDATE_EMAIL)) $errors[]='Please enter a valid email.';
  if (strlen($v['message'])<10) $errors[]='Please tell us a little about your project.';
  if (!$errors) {
    $line = json_encode(['time'=>date('c'),'ip'=>$_SERVER['REMOTE_ADDR'] ?? '']+$v)."\n";
    $dir = __DIR__.'/../enquiries';
    if (!is_dir($dir)) { @mkdir($dir,0750,true); }
    @file_put_contents($dir.'/enquiries.jsonl',$line,FILE_APPEND|LOCK_EX);
    $to = 'hello@northbridgedigital.example';
    $body = "New enquiry\nName: {$v['name']}\nEmail: {$v['email']}\nPhone: {$v['phone']}\nCompany: {$v['company']}\nService: {$v['service']}\nBudget: {$v['budget']}\n\n{$v['message']}";
    @mail($to,'New website enquiry from '.$v['name'],$body,'From: no-reply@'.($_SERVER['HTTP_HOST'] ?? 'localhost')."\r\nReply-To: ".$v['email']);
    $status='ok';
    $v = array_map(fn($x)=>'',$v);
  } else { $status='err'; }
}
function e($s){return htmlspecialchars($s,ENT_QUOTES,'UTF-8');}
?>
<!DOCTYPE html>
<html lang="en-AU">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Contact Us | Melbourne Digital Marketing Agency | Northbridge Digital</title>
<meta name="description" content="Contact Northbridge Digital, a Melbourne digital marketing agency. Request a free SEO and Google Ads audit today.">
<link rel="canonical" href="https://lime-zebra-597027.hostingersite.com/contact.php">
<meta property="og:title" content="Contact Us | Melbourne Digital Marketing Agency | Northbridge Digital">
<meta property="og:description" content="Contact Northbridge Digital, a Melbourne digital marketing agency. Request a free SEO and Google Ads audit today.">
<meta property="og:type" content="website">
<meta property="og:locale" content="en_AU">
<link rel="stylesheet" href="style.css">
<script type="application/ld+json">
{"@context":"https://schema.org","@type":"ProfessionalService","name":"Northbridge Digital","description":"Melbourne digital marketing agency specialising in SEO, Google Ads, social media and web design.","areaServed":"Melbourne, Victoria, Australia","address":{"@type":"PostalAddress","streetAddress":"Collins Street","addressLocality":"Melbourne","addressRegion":"VIC","postalCode":"3000","addressCountry":"AU"},"telephone":"+61390000000"}
</script>
</head>
<body>
<header><div class="wrap hd">
<a class="logo" href="index.html">Northbridge<span>.</span>Digital</a>
<nav><a href="index.html">Home</a><a href="about.html">About</a><a href="projects.html">Projects</a><a href="faqs.html">FAQs</a><a class="cta" href="contact.php">Get a free quote</a></nav>
</div></header>
<section class="page-hero"><div class="wrap"><h1>Contact our Melbourne digital marketing team</h1>
<p class="lead">Tell us about your business and goals. We reply within one business day with a free, no-obligation plan.</p></div></section>
<section><div class="wrap" style="max-width:820px">
<?php if($status==='ok'): ?><div class="msg ok">Thank you! Your enquiry has been received and we will be in touch within one business day.</div><?php endif; ?>
<?php if($status==='err'): ?><div class="msg err"><?php foreach($errors as $er) echo e($er).'<br>'; ?></div><?php endif; ?>
<form method="post" action="contact.php">
<div class="two">
<div><label>Full name *<input name="name" required value="<?=e($v['name'])?>"></label></div>
<div><label>Email *<input type="email" name="email" required value="<?=e($v['email'])?>"></label></div>
</div>
<div class="two">
<div><label>Phone<input name="phone" value="<?=e($v['phone'])?>"></label></div>
<div><label>Company<input name="company" value="<?=e($v['company'])?>"></label></div>
</div>
<div class="two">
<div><label>Service needed
<select name="service"><option value="">Select a service</option><option>SEO</option><option>Google Ads</option><option>Social Media Marketing</option><option>Web Design &amp; Development</option><option>Content Marketing</option><option>Not sure yet</option></select></label></div>
<div><label>Monthly budget
<select name="budget"><option value="">Select a range</option><option>Under $1,500</option><option>$1,500 - $3,000</option><option>$3,000 - $6,000</option><option>$6,000+</option></select></label></div>
</div>
<div><label>How can we help? *<textarea name="message" rows="6" required><?=e($v['message'])?></textarea></label></div>
<input class="hp" type="text" name="website" tabindex="-1" autocomplete="off">
<button class="btn" type="submit">Send enquiry</button>
</form>
<p style="margin-top:24px;color:#475569">Prefer to talk? Call (03) 9000 0000 or email hello@northbridgedigital.example.</p>
</div></section>
<footer><div class="wrap">
<div class="cols">
<div><h4>Northbridge Digital</h4><p>Melbourne digital marketing agency helping local businesses grow with SEO, Google Ads, social media and conversion-focused web design.</p></div>
<div><h4>Services</h4><p>SEO Melbourne<br>Google Ads Management<br>Social Media Marketing<br>Web Design &amp; Development<br>Content Marketing</p></div>
<div><h4>Explore</h4><p><a href="about.html">About us</a><br><a href="projects.html">Projects</a><br><a href="faqs.html">FAQs</a><br><a href="contact.php">Contact</a></p></div>
<div><h4>Contact</h4><p>Collins Street, Melbourne VIC 3000<br>hello@northbridgedigital.example<br>(03) 9000 0000</p></div>
</div>
<div class="copy">&copy; 2026 Northbridge Digital. Demo site. All rights reserved.</div>
</div></footer>
</body></html>
