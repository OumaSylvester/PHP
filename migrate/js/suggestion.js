function suggest(str, field)
{
    if(str.length == 0)
    {
        document.getElementById("suggestion").innerHTML ="";
        return;
    }else
    {
        var xmlhttp = new XMLHttpRequest(); //create a XMLHttpRequest Object
        xmlhttp.onreadystatechange = function(){
            if(this.readyState == 4 && this.status == 200){
                document.getElementById("suggestion").innerHTML = this.responseText; 
            }
        };
        xmlhttp.open("GET", "suggestion.php?q=" + str + "&field=" + field, true );
        xmlhttp.send();
    }
}