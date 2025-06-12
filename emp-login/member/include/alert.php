<?php
if(isset($_SESSION['s']))
{
?>
<div class="alert alert-success" role="alert">
<button type="button" class="close" data-dismiss="alert" aria-label="Close">
<span aria-hidden="true">&times;</span>
</button>
<?php echo $_SESSION['s'];?>
</div><!--end of success box-->
<?php
unset($_SESSION['s']);
}
?>

<?php
if(isset($_SESSION['e']))
{
?>
<div class="alert alert-danger" role="alert">
<button type="button" class="close" data-dismiss="alert" aria-label="Close">
<span aria-hidden="true">&times;</span>
</button>
<?php echo $_SESSION['e'];?>
</div><!--end of error box-->
<?php
unset($_SESSION['e']);
}
?>


<?php
if(isset($_SESSION['i']))
{
?>
<div class="alert alert-info" role="alert">
<button type="button" class="close" data-dismiss="alert" aria-label="Close">
<span aria-hidden="true">&times;</span>
</button>
<?php echo $_SESSION['i'];?>
</div><!--end of info box-->
<?php
unset($_SESSION['i']);
}
?>


<?php
if(isset($_SESSION['w']))
{
?>
<div class="alert alert-warning" role="alert">
<button type="button" class="close" data-dismiss="alert" aria-label="Close">
<span aria-hidden="true">&times;</span>
</button>
<?php echo $_SESSION['w'];?>
</div><!--end of info box-->
<?php
unset($_SESSION['w']);
}
?>