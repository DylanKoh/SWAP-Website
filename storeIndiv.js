

//Obtain the modal

		var modal = document.getElementById("servModal");

        // Get the button that opens the modal
        var btn = document.getElementById("edit-serv-but");
        
        // Get the <span> element that closes the modal
        var span = document.getElementsByClassName("close")[0];
        
        // When the user clicks the button, open the modal 
        btn.onclick = function() {
          modal.style.display = "block";
        }
        
        // When the user clicks on <span> (x), close the modal
        span.onclick = function() {
          modal.style.display = "none";
        }
        
        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function(event) {
          if (event.target == modal) {
            modal.style.display = "none";
              }
        }
		
		
			//Obtain the modal
		var revmodal = document.getElementById("reviewModal");

        // Get the button that opens the modal
        //var btnrev = document.getElementsByClassName("myRevBtn");
        var btnrev = document.getElementById("myRevBtn");
        
        
        // Get the <span> element that closes the modal
        var spanrev = document.getElementsByClassName("closerev")[0];
       
        
        // When the user clicks on <span> (x), close the modal
        spanrev.onclick = function() {
          revmodal.style.display = "none";
        }
        
        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function(event) {
          if (event.target == revmodal) {
            revmodal.style.display = "none";
              }
        }
        
        function saveRevIds(revId) {
        var carddiv = document.getElementById("revcard"+revId);
        var modalComments = document.getElementById("comments");
      	modalComments.innerHTML = carddiv.childNodes[3].innerHTML;
      	
      	var reviewIds = document.getElementById("revIds");
      	reviewIds.value = carddiv.childNodes[5].innerHTML;
      	
      	var reviewRate = document.getElementById("revRates");
      	ratingNum = carddiv.childNodes[2].innerHTML;
      	reviewRate.value = parseInt(ratingNum, 10);
      	
        revmodal.style.display = "block";
        
        }
		
        
        
        