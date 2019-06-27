<?php if($user_class->gang != 0) { 
$gang_class = new Gang($user_class->gang);
?>
<table class="inverted ui five unstackable column small compact table">
<thead>
				<tr>
					<th>Gang Actions</th>
					<th></th>
					<th></th>
				</tr>
			</thead>
			<tr>
			<td width='25%' align='center'><a href='gangdetails.php'>Gang Details</a></td>
            <td width='25%' align='center'><a href='attlog.php'>Attack Log</a></td>
			<td width='25%' align='center'><a href='deflog.php'>Defense Log</a></td>
			<td width='25%' align='center'><a href='vlog.php'>Vault Log</a></td>
            
		</tr>
		<tr>
			<td width='25%' align='center'><a href='gcrimelog.php'>Gang Crime Log</a></td>
            <td width='25%' align='center'><a href='gangvault.php'>Vault</a></td>
			<td width='25%' align='center'><a href='gangmembers.php'>Members</a></td>
			<td width='25%' align='center'><a href='viewwar.php'>View Gang Wars</a></td>
		</tr>
        <tr>
			<td width='25%' align='center'><a href='gangevents.php'>Gang Events</a></td>
            <td width='25%' align='center'><a href='gangforum.php'>Gang Forum</a></td>
            <td width='25%' align='center'><?php echo ($gang_class->leader != $user_class->id) ? "<a href='leavegang.php'>Leave Gang</a></td>" : "</td>"; ?>
			<td width='25%' align='center'></td>
		</tr>
	</table>

		<table class="inverted ui five unstackable column small compact table">
			<thead>
				<tr>
					<th>Gang Heist</th>
					<th></th>
					<th></th>
				</tr>
			</thead>
			<tr>
				<td width='33%' align='center'><a href='heists.php'>Heists</a>
					<td width='33%' align='center'><a href='plan.php?action=planningpage'>Planning Page</a>
						<td width='33%' align='center'><a href='plan.php?action=invitedmembers'>Invited Players</a>
						</tr>
					</table>

<?php
$user_rank = new GangRank($user_class->grank);
if ($user_rank->members == 1 || $user_rank->crime == 1 || $user_rank->vault == 1 || $user_rank->massmail == 1 || $user_rank->applications == 1 || $user_rank->appearance == 1 || $user_rank->ranks == 1 || $user_rank->invite == 1 || $user_rank->upgrade == 1 || $user_class->admin == 1){
?>
<table class="inverted ui five unstackable column small compact table">
<thead>
				<tr>
					<th>Gang Management</th>
					<th></th>
					<th></th>
				</tr>
			</thead>
			<tr>
			<?php
		if($user_class->id == $gang_class->leader || $user_class->username == $gang_class->leader ){
			
			
  $user_rank->id = 1;
  $user_rank->gang = 1;
  $user_rank->title = 1;
  $user_rank->members = 1;
  $user_rank->crime = 1;
  $user_rank->vault = 1;
  $user_rank->ranks = 1;
  $user_rank->massmail = 1;
  $user_rank->applications = 1;
  $user_rank->appearance = 1;
  $user_rank->invite = 1;
  $user_rank->houses = 1;
  $user_rank->upgrade = 1;
  $user_rank->gforum = 1;
  $user_rank->polls = 1; 
		}
  ?>
			<?php echo ($user_rank->invite == 1) ? "<td width='25%' align='center'><a href='invite.php'>Invite Mobster</a></td>" : "<td width='25%' align='center'></td>";
			 echo ($user_rank->applications == 1) ? "<td width='25%' align='center'><a href='manageapps.php'>Gang Applications</a></td>" : "<td width='25%' align='center'></td>";
			 echo ($user_rank->appearance == 1) ? "<td width='25%' align='center'><a href='editgang.php'>Edit Gang</a></td>" : "<td width='25%' align='center'></td>";
             echo ($user_rank->members == 1) ? "<td width='25%' align='center'><a href='managegang.php'>Manage Members</a></td>" : "<td width='25%' align='center'></td>";
			?>
		</tr>

         <tr>
            <?php
			echo ($user_rank->massmail == 1) ? "<td width='25%' align='center'><a href='newgmail.php'>New Gang Mail</a></td>" : "<td width='25%' align='center'></td>";
			echo ($user_rank->crime == 1) ? "<td width='25%' align='center'><a href='gangcrime.php'>Manage Gang Crime</a></td>" : "<td width='25%' align='center'></td>";
			echo ($user_rank->ranks == 1) ? "<td width='25%' align='center'><a href='manageranks.php'>Rank Management</a></td>" : "<td width='25%' align='center'></td>";
            echo ($user_rank->vault == 1) ? "<td width='25%' align='center'><a href='managegangvault.php'>Manage Vault</a></td>" : "<td width='25%' align='center'></td>";
            ?>
		</tr>
        
        <tr>
        	<?php
            echo ($user_class->id == $gang_class->leader) ? "<td width='25%' align='center'><a href='gangwar.php'>Manage Gang Wars</a></td>" : "<td width='25%' align='center'></td>";
			
			echo ($user_rank->houses == 1) ? "<td width='25%' align='center'><a href='ganghouse.php'>Gang Housing</a></td>" : "<td width='25%' align='center'></td>";
			
            echo ($user_rank->houses == 1) ? "<td width='25%' align='center'><a href='gangupgrade.php'>Upgrade</a></td>" : "<td width='25%' align='center'></td>";
            
            echo ($user_class->id == $gang_class->leader) ? "<td width='25%' align='center'><a href='changeleader.php'>Change Leader</a></td>" : "<td width='25%' align='center'></td>";
?>
        </tr>
        
<table class="inverted ui five unstackable column small compact table">

        <tr>
        	<?php
			echo ($gang_class->leader == $user_class->id || $user_class->admin == 1) ? "<td width='25%' align='center'><a href='disband.php'>Delete Gang</a></td>" : "<td width='25%' align='center'></td>";
			?>
            <td width='25%' align='center'></td>
            <td width='25%' align='center'></td>
            <td width='25%' align='center'></td>
        </tr>
	</table>
<?
}
}

?>