<?
include 'nliheader.php';
?>
 	<thead>
    <tr>
	<th>Welcome to Mobster Story</th>
  	</tr>
  	</thead>
	<tr>
    <td>
		<table class="inverted ui five unstackable column small compact table">
		<tr>
		<td style="font-size: 13px;"><p>Welcome to Mobster Story. GRPG is a free mafia-style browser based RPG, which means you don't have to download anything at all, you play it 		all in your web browser, and best of all you don't have to pay for anything. In GRPG, you choose your own path. Whether you want to train your stats and become the strongest 		player, or become the president and actually effect and change aspects of the game, it is entirely up to you.</p>
		
		
		</td>
		</tr>
		</table>
		</td></tr>


		<thead>
		<tr>
		<th>Screenshots</th>
		</tr>
		</thead>
		<tr>
		<td><style>
body {font-family: Arial, Helvetica, sans-serif;}

#myImg {
  border-radius: 5px;
  cursor: pointer;
  transition: 0.3s;
}

#myImg:hover {opacity: 0.7;}

/* The Modal (background) */
.modal {
  display: none; /* Hidden by default */
  position: fixed; /* Stay in place */
  z-index: 1; /* Sit on top */
  padding-top: 100px; /* Location of the box */
  left: 0;
  top: 0;
  width: 100%; /* Full width */
  height: 100%; /* Full height */
  overflow: auto; /* Enable scroll if needed */
  background-color: rgb(0,0,0); /* Fallback color */
  background-color: rgba(0,0,0,0.9); /* Black w/ opacity */
}

/* Modal Content (image) */
.modal-content {
  margin: auto;
  display: block;
  width: 80%;
  max-width: 700px;
}

/* Caption of Modal Image */
#caption {
  margin: auto;
  display: block;
  width: 80%;
  max-width: 700px;
  text-align: center;
  color: #ccc;
  padding: 10px 0;
  height: 150px;
}

/* Add Animation */
.modal-content, #caption {  
  -webkit-animation-name: zoom;
  -webkit-animation-duration: 0.6s;
  animation-name: zoom;
  animation-duration: 0.6s;
}

@-webkit-keyframes zoom {
  from {-webkit-transform:scale(0)} 
  to {-webkit-transform:scale(1)}
}

@keyframes zoom {
  from {transform:scale(0)} 
  to {transform:scale(1)}
}

/* The Close Button */
.close {
  position: absolute;
  top: 15px;
  right: 35px;
  color: #f1f1f1;
  font-size: 40px;
  font-weight: bold;
  transition: 0.3s;
}

.close:hover,
.close:focus {
  color: #bbb;
  text-decoration: none;
  cursor: pointer;
}

/* 100% Image Width on Smaller Screens */
@media only screen and (max-width: 700px){
  .modal-content {
    width: 100%;
  }
}
</style>
</head>
<body>

<img id="myImg" src="https://i.imgur.com/HT1SOHr.png" alt="Mobster Story Screenshot" style="width:150;max-width:175px">

<!-- The Modal -->
<div id="myModal" class="modal">
  <span class="close">&times;</span>
  <img class="modal-content" id="img01">
  <div id="caption"></div>
</div>

<script>
// Get the modal
var modal = document.getElementById('myModal');

// Get the image and insert it inside the modal - use its "alt" text as a caption
var img = document.getElementById('myImg');
var modalImg = document.getElementById("img01");
var captionText = document.getElementById("caption");
img.onclick = function(){
  modal.style.display = "block";
  modalImg.src = this.src;
  captionText.innerHTML = this.alt;
}

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];

// When the user clicks on <span> (x), close the modal
span.onclick = function() { 
  modal.style.display = "none";
}
</script></td>
		</tr>
		<thead>
		<tr>
		<th>
		<?php

$stats = new User_Stats("bang");

?>
		
			Total Mobsters: <font color='green'><?php echo $stats->playerstotal; ?></font></th>
		</tr>
		</thead>
		<thead>
		<tr>
		<th>Mobsters Online: <font color='green'><?php echo $stats->playersloggedin; ?></font></th>
		</tr>
		</thead>

	<?
	include 'nlifooter.php';
	?>
	