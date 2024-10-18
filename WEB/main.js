// Get the current page's filename
const pageName = window.location.pathname.split("/").pop();

// Function to show the correct div in the header based on the page's name (1, 2 or 3)
function showCorrectDiv() {
  if (pageName.includes("1")) {
    document.getElementById("div1").classList.add("b1-indicator");
    document.getElementById("div1").classList.remove("hidden");
  } else if (pageName.includes("2")) {
    document.getElementById("div2").classList.add("b2-indicator");
    document.getElementById("div2").classList.remove("hidden");
  } else if (pageName.includes("3")) {
    document.getElementById("div3").classList.add("b3-indicator");
    document.getElementById("div3").classList.remove("hidden");
  }
}

window.onload = function() {
    // Makes it so the light blue part of the header is a button
    const homeButton = document.getElementById("homeButtonID");
    
    if (homeButton) {
        homeButton.addEventListener("click", function() {
            console.log('Div clicked');
            // window.location.href = "index.html"; // Home page, change link when we get it
        });
    }

 	if (w >= 1400) {
		document.getElementById("slogan").classList.add("sloganShow");
		document.getElementById("slogan").classList.remove("sloganHide");
    }
	
    if (w <= 1400) {
		document.getElementById("slogan").classList.add("sloganHide");
		document.getElementById("slogan").classList.remove("sloganShow");
    }

    // If you create functions, add them here
    showCorrectDiv();
};


// Takes care of showing or not the slogan in the header, depending on window size and dinamically getting updated
window.onresize = function(){
	var w = window.innerWidth;
	
	if (w >= 1400) {
		document.getElementById("slogan").classList.add("sloganShow");
		document.getElementById("slogan").classList.remove("sloganHide");
	}
	
	if (w <= 1400) {
		document.getElementById("slogan").classList.add("sloganHide");
		document.getElementById("slogan").classList.remove("sloganShow");
	}
}
