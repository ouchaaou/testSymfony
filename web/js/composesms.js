jQuery(document).ready(function()
{
	countwords();
	countwordcontact();
	
	
});

function countwords()
{
	var words= document.getElementById('smscontent').value;
	//var reg ="[^\x00-\x80]+";
	//if(words.match(reg)) 
	//{
	//document.getElementById('smscontent').value="";
   //alert( "Input Text Characters Only");
   
   //return false;
	//}
	var totalwords= words.length;
	document.getElementById('wordsremain').innerHTML=totalwords;
	divid=document.getElementById('msgtype').value;
	if(divid=='unicodemsg')
	{
		dvdby=70;
	}
	else
	{
		dvdby=160;
	}
	if(totalwords > 0)
	{
		var ff= Math.ceil(totalwords / dvdby);
		
		document.getElementById('countsms').innerHTML=parseInt(ff);
		document.getElementById('countsmshidden').value=parseInt(ff);
		
	}
	if(totalwords == 0)
	{
	document.getElementById('countsms').innerHTML=0;
	document.getElementById('countsmshidden').value=0;	
	}
	
}
