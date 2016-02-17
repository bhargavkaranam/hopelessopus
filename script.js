var c = document.getElementById("myCanvas");
c.width=document.getElementById("img").naturalWidth;
c.height=document.getElementById("img").naturalHeight;
var ctx = c.getContext("2d");
var img = new Image();

var oldX=<?php echo $currentx; ?>,oldY=<?php echo $currenty; ?>;

$("#img").load(setupMap());

function input()
	{
		var input=document.getElementById("textbox").value.toLowerCase();
		$.ajax({
			url:'checkanswer.php',
			type:'get',
			data:'answer='+input,
			dataType:'json',
			success:function(data)
			{
				if(data.status==1)
				{
					alert("Correct answer!");
					toggleChoice();

					//TO-DO: Check for 1 empty div in case of detour route
					$("#location1").text(data.location1);
					$("#location2").text(data.location2);
					$("#location3").text(data.location3);
				}
				else
				{
					alert("Wrong answer");
				}
			},
		});	
	}
	function toggle(id)
	{
		$("#"+id).fadeToggle();
		if(id!="mobileMenu")
			$("#mobileMenu").fadeOut();
	}

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
			if(data.status=="000") //Dead End
			{
				$.ajax({
					url:'getaction.php',
					type:'post',
					success:function(data)
					{
						$("#locationChoice_container").text(data);
					},
				});	
				return;	
			}
			
			if(data.status=="111")  //Direct Route
				$("#locationChoice_text").text("Woohoo. Direct route taken");
			
			if(data.status=="222")  //Detour route
				$("#locationChoice_text").text("Take a de tour");
				//TO-DO: Handle 1 empty div

			moveOnMap(data.x, data.y);
			toggleChoice();
			changeLevel(data.story, data.q);
		},
	});
});

function changeLevel(s,q)
{
	$("#story_text").text(s);
	$("#question").text(q);
}

function setupMap(x,y)
{
	ctx.beginPath();
	ctx.strokeStyle = '#4EFF00';
	ctx.lineWidth = 2;
  
    img.onload = function()
    {  ctx.drawImage(img,oldX-15,oldY-47);  }
   	img.src = '../res/pin.png';
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

function toggleChoice()
{
	$("#story").fadeToggle();
	$("#question").fadeToggle();
	$("#choice").fadeToggle();
	$("#locationChoice_container").fadeToggle();
}