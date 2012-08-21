<script language="php">
$email = $HTTP_POST_VARS[email];
$mailto = "d.meyer.w@gmail.com";
$mailsubj = "Form submission";
$mailhead = "From: $email\n";
reset ($HTTP_POST_VARS);
$mailbody = "Values submitted from web site form:\n";
while (list ($key, $val) = each ($HTTP_POST_VARS)) { $mailbody .= "$key : $val\n"; }
if (!eregi("\n",$HTTP_POST_VARS[email])) { mail($mailto, $mailsubj, $mailbody, $mailhead); }
</script>
<?php header("Location:{$_SERVER['HTTP_REFERER']}");exit; ?>