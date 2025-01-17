document.addEventListener('DOMContentLoaded', () => {
	// Get the page name of the parent folder of the current page
	const pageName = window.location.pathname;
	const indicators = {
		div1: "offres",
		div2: "factures",
		div3: "compte"
	};

	Object.keys(indicators).forEach(divId => {
		if (pageName.includes(indicators[divId])) {
			document.getElementById(divId).style.backgroundColor = "red";
		}
	});
});

window.onload = function() {
	// Makes it so the light blue part of the header is a button
	const homeButton = document.getElementById("homeButtonID");

	if (homeButton) {
		homeButton.addEventListener("click", function() {
			console.log('Div clicked');
			// window.location.href = "index.html"; // Home page, change link when we get it
		});
	}
};

function detect_category(tagre) {
	let elem = document.getElementById("categorie");
	let value = elem.value;
	let div = document.getElementById("depends_select");
	clear_node(div);

	if (value == "activite") {
		let input_age = document.createElement("input");
		input_age.setAttribute("type", "text");
		input_age.setAttribute("placeholder", "Age requis (tout public par défaut)");
		input_age.setAttribute("name", "age");
		div.appendChild(input_age);

		let duree = document.createElement("input");
		duree.setAttribute("type", "text");
		duree.setAttribute("placeholder", "Durée de l'activité *");
		duree.setAttribute("name", "duree");
		duree.setAttribute("required", "");
		div.appendChild(duree);
	} else if (value == "restauration") {
		let gammeprix = document.createElement("input");
		gammeprix.setAttribute("type", "text");
		gammeprix.setAttribute("placeholder", "Gamme de prix * (€ ou €€ ou €€€)");
		gammeprix.setAttribute("name", "gammeprix");
		gammeprix.setAttribute("required", "");
		gammeprix.setAttribute("maxlength", "3");
		div.appendChild(gammeprix);

		let tag_select = document.createElement("select");
		tag_select.setAttribute("name", "tagre");
		tag_select.setAttribute("id", "tagre");
		tag_select.setAttribute("required", "");

		let no_tag = document.createElement("option");
		no_tag.setAttribute("value", "");
		no_tag.setAttribute("disabled", "");
		no_tag.setAttribute("selected", "");
		no_tag.setAttribute("hidden", "");
		no_tag.innerHTML = "Type de cuisine *"
		tag_select.appendChild(no_tag);

		for (i = 0; i < tagre.length; i++) {
			let tag = document.createElement("option");
			tag.setAttribute("value", tagre[i]);
			tag.innerHTML = tagre[i];
			tag_select.appendChild(tag);
		}

		let other_tag = document.createElement("option");
		other_tag.setAttribute("value", "autre");
		other_tag.innerHTML = "Autre"
		tag_select.appendChild(other_tag);
		div.appendChild(tag_select);

		let image_carte = document.createElement("img");
		image_carte.setAttribute("alt", "");
		image_carte.setAttribute("src", "");
		image_carte.setAttribute("id", "carte_preview");
		div.appendChild(image_carte);

		let carte_button = document.createElement("label");
		carte_button.setAttribute("for", "carte");
		carte_button.setAttribute("class", "smallButton");
		carte_button.innerHTML = "Votre carte"
		div.appendChild(carte_button);

		let carte = document.createElement("input");
		carte.setAttribute("type", "file");
		carte.setAttribute("name", "carte");
		carte.setAttribute("id", "carte");
		carte.setAttribute("accept", "image/*");
		carte.setAttribute("required", "");
		carte.setAttribute("onchange", "preview(carte_preview)");
		div.appendChild(carte);
	} else if (value == "visite") {
		let radio_group = document.createElement("fieldset");
		radio_group.setAttribute("id", "guidee");
		let legend = document.createElement("legend");
		let legend_text = document.createTextNode("La visite est elle guidée");
		legend.appendChild(legend_text);
		radio_group.appendChild(legend);

		let oui = document.createElement("input");
		oui.setAttribute("type", "radio");
		oui.setAttribute("name", "guidee");
		oui.setAttribute("value", "oui");
		radio_group.appendChild(oui);
		let label_oui = document.createTextNode("Oui");
		radio_group.appendChild(document.createElement("label").appendChild(label_oui));

		radio_group.appendChild(document.createElement("br"));

		let non = document.createElement("input");
		non.setAttribute("type", "radio");
		non.setAttribute("name", "guidee");
		non.setAttribute("value", "non");
		non.setAttribute("selected", "");
		radio_group.appendChild(non);
		let label_non = document.createTextNode("Non");
		radio_group.appendChild(document.createElement("label").appendChild(label_non));
		div.appendChild(radio_group);


		let duree = document.createElement("input");
		duree.setAttribute("type", "text");
		duree.setAttribute("placeholder", "Durée de la visite *");
		duree.setAttribute("name", "duree");
		duree.setAttribute("required", "");
		div.appendChild(duree);
	} else if (value == "parc_attractions") {
		let input_age = document.createElement("input");
		input_age.setAttribute("type", "text");
		input_age.setAttribute("placeholder", "Age requis (tout public par défaut)");
		input_age.setAttribute("name", "age");
		div.appendChild(input_age);

		let nb_attrac = document.createElement("input");
		nb_attrac.setAttribute("type", "text");
		nb_attrac.setAttribute("placeholder", "Nombre d'attractions *");
		nb_attrac.setAttribute("name", "nb_attrac");
		div.appendChild(nb_attrac);


		let image_plan = document.createElement("img");
		image_plan.setAttribute("alt", "");
		image_plan.setAttribute("src", "");
		image_plan.setAttribute("id", "plan_preview");
		div.appendChild(image_plan);

		let plan_button = document.createElement("label");
		plan_button.setAttribute("for", "plan");
		plan_button.setAttribute("class", "smallButton");
		plan_button.innerHTML = "Plan du parc"
		div.appendChild(plan_button);

		let plan = document.createElement("input");
		plan.setAttribute("type", "file");
		plan.setAttribute("name", "plan");
		plan.setAttribute("id", "plan");
		plan.setAttribute("accept", "image/*");
		plan.setAttribute("required", "");
		plan.setAttribute("onchange", "preview(plan_preview)");
		div.appendChild(plan);
	} else if (value == "spectacle") {
		let nb_places = document.createElement("input");
		nb_places.setAttribute("type", "text");
		nb_places.setAttribute("placeholder", "Nombre de places *");
		nb_places.setAttribute("name", "nb_places");
		nb_places.setAttribute("required", "");
		div.appendChild(nb_places);

		let duree = document.createElement("input");
		duree.setAttribute("type", "text");
		duree.setAttribute("placeholder", "Durée du spectacle *");
		duree.setAttribute("name", "duree");
		duree.setAttribute("required", "");
		div.appendChild(duree);
	}
}

function clear_node(node) {
	let child = node.lastElementChild;
	while (child) {
		node.removeChild(child);
		child = node.lastElementChild;
	}
}

// Références aux éléments HTML
const popup = document.getElementById("popup");
const openPopupBtn = document.getElementById("open-popup");
const bnVal = document.getElementById("bnVal");
const bnAnn = document.getElementById("bnAnn");
const checkbox = document.getElementById("checkbox");

// Ouvrir la popup
openPopupBtn.addEventListener("click", () => {
	popup.style.display = "flex";
});


/* Fermer la popup si on clique à l'extérieur du contenu
window.addEventListener("click", (event) => {
    if (event.target === popup) {
	popup.style.display = "none";
    }
});
*/

bnVal.addEventListener("click", () => {
	if (checkbox) { //checkbox == false || true
		//requete sql
	}
}
);

bnVal.disabled = true;
checkbox.checked = false;

checkbox.onchange = function() {
	bnVal.disabled = !this.checked;
};

bnVal.addEventListener("click", () => {
	popup.style.display = 'none';
});

bnAnn.addEventListener("click", () => {
	popup.style.display = 'none';
});

function loadCreaOffre() {
	location.href = "form_offre-1.php";
}

function loadSesOffresPro() {
	location.href = "liste_offres_pro-1.php"; // temporaire
}
