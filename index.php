<?php
session_start();
require_once("dbconnection.php");
if(!isset($_SESSION['username']))
{
header("location:http://onlineevents.techtatva.in");
}
$username=$_SESSION['username'];
$sql="select * from users where Username='$username'";
	$result=mysqli_query($con,$sql);
	$count=mysqli_num_rows($result);

	if($count==0)
	{
		$sql="insert into users(Username,QNo,CurrentX,CurrentY,CurrentState,Log,Freeze,LastAnswered,Timestamp) values('$username','1','903','652','-1','0','0','0','0')";
		$result=mysqli_query($con,$sql);
	}
$sql="select * from users where Username='$username'";
	$result=mysqli_query($con,$sql);
	$count=mysqli_num_rows($result);
	$row=mysqli_fetch_array($result,MYSQL_ASSOC);
	$qno=$row['QNo'];
	$currentx=$row['CurrentX'];
	$currenty=$row['CurrentY'];
	$freeze=$row['Freeze'];
	$timeout=$row['Timestamp'];
	$delay=4;
	if($timeout!="0")	
	{
		//TIMEOUT CHANGE HERE
		$check=(time()-$timeout)/60;
		if($check>=$delay)
			$freeze=0;
	}
	if($freeze==1)
	{
		$sql="select * from locations where Status='0' and QNo='$qno'";
		$result=mysqli_query($con,$sql);
		$row=mysqli_fetch_array($result,MYSQL_ASSOC);
		$story=$row['Action'];
		$image=$row['Image'];
		$timeout=$row['Timestamp'];
		$question="You're dead. Try again after..."."$delay minutes";
	}
	else
	{
	$sql1="select * from questions where Id='$qno'";
	$result1=mysqli_query($con,$sql1);
	$row1=mysqli_fetch_array($result1,MYSQL_ASSOC);
	$story=$row1['Story'];
	$question=$row1['Question'];
	$image=$row1['Image'];
	$title=$row1['Title'];
}
			$sql="select * from users where Username='$username'";
			$result=mysqli_query($con,$sql);
			$row=mysqli_fetch_array($result,MYSQL_ASSOC);
			$log=unserialize($row['Log']);
			$len=count($log);
			for($i=0;$i<$len;$i++)
			{
				$sql="select * from questions where Id='$log[$i]'";
				$result=mysqli_query($con,$sql);
				$row=mysqli_fetch_array($result,MYSQL_ASSOC);
				$s=$row['Story'];
				$x.=$s;
$x.="<br><br>";
			}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Hopeless Opus | TechTatva '15</title>
	<!--<link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Indie+Flower" />-->
	<link rel="stylesheet" type="text/css" href="headerfooter.css">
	<link rel="stylesheet" type="text/css" href="style.css">
		<script type="text/javascript" src="res/jquery.js"></script>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>	
<meta name="viewport" content="width=device-width, initial-scale=1">

	<meta charset="utf-8" /> 

</head>
<body>

<!-- Header and Footer -->
<div id="topbar_container">
	<div id="topbar">
		<span id="header_left" class="topmenu_element" style="float: left; margin-left: 10px;">
			<span style="color: #1098F7;">TechTatva '15</span> Hopeless Opus 
		</span>
		<span id="header_right" style="float: right; margin-right: 10px;">
			<span class="topmenu_element"><a href="javascript:toggle('rules')">Rules</a></span>
			<span class="topmenu_element"> | <a href="logout.php">Logout</a></span>
		</span>
		<span id="mobile_menu" class="mobile_menu" style="float: right; margin-right: 10px;">
			<a href="javascript:toggle('mobileMenu')">Show/Hide Menu</a>
		</span>
	</div>
</div>

<div id="footer_container">
	<div id="footer">
		
	</div>
</div>

<!--Mobile Menu-->
<div class="mobile_menu" id="mobileMenu">
	<a href="javascript:toggle('canvas_wrapper')">Show/Hide Map</a>
	<a href="javascript:toggle('leaderboard')">Show/Hide Leaderboard</a>
	<a href="javascript:toggle('prevStory')">Show/Hide Story</a>
	<a href="javascript:toggle('rules')">Show/Hide Rules</a>
	<a href="logout.php">Logout</a>
</div>

<!--Question Area-->
<div id="container">
	<div class="container_width">
		<div id="story" class="hoverEffect">
			<h2 style="margin: 0px; padding: 5px; padding-top:10px;" id="storyTitle"><?php echo $title; ?></h2>
			<p id="story_text" class="para">
			
			<?php echo $story; ?>
							
			</p>	
			<script type="text/javascript">
			var str=$("#story_text").html();
			str = str.split("�").join("'");//str.replace("�", "'");
			$("#story_text").text(str);
			</script>
		</div>
		<br>
		<p id="question" class="para hoverEffect">
			<?php 
       echo($question);
     
       ?>
		</p>
		<br>
		<p id="timeout" class="para hoverEffect"></p>
		<div id="locationChoice_container">
			
			
			<div id="locationChoice">
					<div id="location1" class="hoverEffect loc">Choice 1</div>
					<div id="location2" class="hoverEffect loc">Choice 2</div>
					<div id="location3" class="hoverEffect loc">Choice 3</div>
			</div>	
		</div>
	</div>
	
</div>

<div id="choice" align="center">
		<form action="javascript:input()" id="form">
			<input type="text" autofocus id="textbox">
			<button type="submit" value="submit">Submit</button>
		</form>
</div>

<script type="text/javascript">
	// function getData(){;}
	function toggle(id)
	{
		$("#"+id).fadeToggle();
		if(id!="mobileMenu")
			$("#mobileMenu").fadeOut();
	}
</script>

<!--Map Canvas and Background Image-->
<div id="canvas_wrapper">	
	<canvas id="myCanvas">Your browser does not support the HTML5 canvas tag.</canvas>
	<img src="res/map.jpg" id="img" alt="hello">		
</div>

<div id="bg_img_wrapper">
	<div class="vig"></div>
	<img src="<?php echo $image;?>" id="bg_img">
</div>

<!--Side Menu-->
<div id="icons">
	<a href="#" class="maplink">Show/Hide</a>
	<a href="javascript:toggle('canvas_wrapper')" class="maplink">
		<img src="res/map22.png"><br>
		Map
	</a>
	<a href="javascript:toggle('leaderboard')" class="maplink"> 
		<img src="res/board.png"><br>
		Leaderboard
	</a>
	<a href="javascript:toggle('prevStory')" class="maplink"> 
		<img src="res/story.png"><br>
		Story
	</a>
</div>

<div id="leaderboard">
	Leaderboard
	<br>Players ahead:<span id="ahead"></span>
	<br>Players behind:<span id="behind"></span>
	<br>Players on par:<span id="same"></span>
</div>
<div id="prevStory" align="center">
		<p class="para">
			Here's what happened earlier:
			<span style="float:right"><a href="javascript:toggle('prevStory')">Close</a></span>
			<hr>
		</p>
		
		<p id="prevStory_text" class="para archive">
			You just started playing...
<?php echo $x; ?>
		</p>
</div>

<div id="rules">
	<p class="para archive">
	Instructions and Rules
	<hr>
		<ol style="color: black; font-size: 1.3em;">
		<li>Every location will need you to answer a question or solve a problem in order to proceed</li>
		<li>The correct answer will lead you to an option of three different locations or choices</li>
		<li>One choice is the most efficient and is on the route of the shortest distance in the map<br>
		One takes longer and has many detours<br>
		One choice leads to a dead-end. Choosing the dead end will set you back with a time out</li>
		<li>Duration of the time outs will increase as the player progresses in the game</li>
		<li>Answer to each question will either be subjective or a numerical value.<br>
		There will be no spaces or special characters. The answers are not case-sensitive.</li>
		</ol>
	</p>
	<p class="para">Bards will serenade our hero and songs will be sung around all the seven continents about the ZombieSlayer!
	</p>
</div>

<script type="text/javascript">
var c = document.getElementById("myCanvas");
c.width=document.getElementById("img").naturalWidth;
c.height=document.getElementById("img").naturalHeight;
var ctx = c.getContext("2d");
var img = new Image();
var textbox=document.getElementById("textbox");
var oldX=<?php echo $currentx; ?>;
var oldY=<?php echo $currenty; ?>;
var freeze="<?php echo $freeze; ?>";
if(freeze==1)
var deadendChecker=setInterval(refresh,10000);
$("#timeout").hide();
$("#img").load(setupMap(oldX, oldY));
$.ajax({
url:'leaderboard.php',
dataType:'json',
success:function(data)
{
$("#ahead").text(data.ahead);
$("#behind").text(data.behind);
$("#same").text(data.same);
},
});
$(".loc").on("click",function() 
{
	var id=$(this).attr('id');
	var text=$(this).text();
	$.ajax({
		url:'getlocation.php',
		type:'get',
		data:'id='+id+'&text='+text,
		dataType:'json',
		success:function(data)
		{
			if(data.status=="000")
			{
$("#choice").hide();
				$("#timeout").text(data.time+" minutes");
				$("#timeout").fadeIn();
				$("#timeout").addClass("hoverEffect");
				//TIMEOUT CHANGE
				setTimeout(refresh,240000);
			}
		
			if(data.status=="911") //Resubmit of data
			{
				return;				
			}
			
			if(data.status=="111")  //Direct Route
				$("#locationChoice_text").text("Woohoo. Direct route taken");
			
			if(data.status=="222")  //Detour route
				$("#locationChoice_text").text("Take a de tour");
				//TO-DO: Handle 1 empty div

			moveOnMap(data.x, data.y);
			toggleChoice();
			changeLevel(data.title, data.story, data.q,data.image);
		},
	});
});
function refresh()
{
$.ajax({
		 url:'deadend.php',
		 dataType:'json',
		 success:function(data)
		 {
			if(data.status==="1")
			{
				clearInterval(deadendChecker);
$("#choice").fadeIn();
$("#timeout").hide();
changeLevel(data.title, data.story, data.q,data.image);				
			}
		},
	});
}
function setupMap(x,y)
{

	ctx.beginPath();
	ctx.strokeStyle = '#4EFF00';
	ctx.lineWidth = 2;
    img.onload = function()
    {  ctx.drawImage(img,x-15,y-47);  }
   	img.src = 'res/pin.png';
}
function toggleChoice()
{
	//$("#story").fadeToggle();
	$("#question").fadeToggle();
	$("#choice").fadeToggle();
	$("#locationChoice_container").fadeToggle();
}

function moveOnMap(newX, newY)
{
	ctx.clearRect(oldX-15,oldY-47,30,47);
	ctx.moveTo(oldX, oldY);
	ctx.lineTo(newX, newY);
	
	oldX=newX; oldY=newY;
	
	$("#canvas_wrapper").fadeIn();
	setTimeout(function(){ctx.stroke(); ctx.drawImage(img,newX-15,newY-47);},500);
	setTimeout(function()
	{
		$("#canvas_wrapper").fadeOut();//hides the map;
	}, 2000);
}
function input()
{
	var input=textbox.value.toLowerCase();
	var input=textbox.value.toLowerCase();
	$.ajax({
		url:'checkanswer.php',
		type:'get',
		data:'answer='+input,
		dataType:'json',
beforeSend:function()
{
$("#choice").fadeOut();
},
		success:function(data) {
$("#choice").fadeIn();
if(data.status=="900")
{
	alert('sdfsd');
	moveOnMap(data.x, data.y);
			
			changeLevel(data.title, data.story, data.q,data.image);
}
			if(data.status==1)
			{
				
				toggleChoice();
				$(".loc").css("display","inline-block");
				$("#location1").text(data.location1);
				$("#location2").text(data.location2);
				$("#location3").text(data.location3);
				if(data.location1==="") $("#location1").fadeOut();
				if(data.location2==="") $("#location2").fadeOut();
				if(data.location3==="") $("#location3").fadeOut();
			}
			else
			{
				$( "#choice" ).effect( "shake" );
			}
		},
	});
	document.getElementById("form").reset();
}

function changeLevel(t,s,q,i)
{
	
	s = s.split("\u0092").join("'");
	q = q.split("\u0092").join("'");
	
	
	$("#story_text").text(s);
	$("#question").text(q);
	$("#bg_img").attr('src',i);

	t = t.split("\u0092").join("'");
	$("#storyTitle").text(t);
        
	var currentStory="<br><br>"+document.getElementById("story_text").innerHTML;
	var prev=document.getElementById("prevStory_text").innerHTML;
	document.getElementById("prevStory_text").innerHTML=prev+currentStory;
}
</script>

</body>
</html>