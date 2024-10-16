// Get the current page's filename
const pageName = window.location.pathname.split("/").pop();

// Function to show the correct div based on the page name
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
    const homeButton = document.getElementById("homeButtonID");
    
    if (homeButton) {
        homeButton.addEventListener("click", function() {
            console.log('Div clicked');
            // window.location.href = "index.html";
        });
    }
    
    showCorrectDiv();
};

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
