function getOfferId() {
    let offerIds = new Set();

    // Check if the PHP variable 'idoffre' is available in the global JS scope
    if (typeof idoffre !== 'undefined' && idoffre.match(/^Of-\d{4}$/)) {
        offerIds.add(idoffre);
		console.log("idoffre found in global scope:" + idoffre);
    }
    
    // Extract the ID from the URL parameters
    const urlParams = new URLSearchParams(window.location.search);
    const urlId = urlParams.get('idoffre');
    if (urlId && urlId.match(/^Of-\d{4}$/)) {
        offerIds.add(urlId);
		console.log("urlId found in URL parameters:" + urlId);
    }
    
    // Retrieve stored IDs from local storage
    let storedIds = JSON.parse(localStorage.getItem('offerIds')) || [];
    
    // Remove existing occurrences of new IDs
    storedIds = storedIds.filter(id => !offerIds.has(id));
    
    // Add new IDs to the beginning
    storedIds = [...offerIds, ...storedIds];
    
    // Store updated list in local storage
    localStorage.setItem('offerIds', JSON.stringify(storedIds));
    
    return offerIds.size > 0 ? Array.from(offerIds)[0] : null;
}

function getStoredOfferIds() {
    return JSON.parse(localStorage.getItem('offerIds')) || [];
}

function displayOffers() {
    const offerList = document.getElementById("offer-list");
    offerList.innerHTML = "";
    const offers = getStoredOfferIds();
    
    offers.forEach(id => {
        let listItem = document.createElement("li");
        listItem.textContent = id;
        offerList.appendChild(listItem);
    });
}
