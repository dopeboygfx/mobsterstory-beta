<?
include 'dbcon.php';
include 'classes.php';

$user_voted = new User($_GET['id']);
$points = $user_voted->points + 10;

$result = $GLOBALS['pdo']->prepare('UPDATE `grpgusers` SET `points` = ? WHERE `id` = ?');
$result->execute(array($points, $user_voted->id));
?>
