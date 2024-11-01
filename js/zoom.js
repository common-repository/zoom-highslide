
window.onload = function() { 
  
    for (var index = 0; index < document.images.length; index++) { 
        		document.images[index].style.display="";
        		var rate = document.images[index].width / document.images[index].height; 
        
       		  if (document.images[index].width > widthRestriction) { 
       		      document.images[index].width = widthRestriction; 
       		      document.images[index].height = widthRestriction / rate; 
       		  }else if (document.images[index].height > heightRestriction) { 
       		      document.images[index].height = heightRestriction; 
       		      document.images[index].width = heightRestriction * rate; 
       		  } 
    } 
}
