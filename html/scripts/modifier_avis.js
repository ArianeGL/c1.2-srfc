function modifier_avis(button, idavis, idoffre) {
	let avis = button.parentNode;
	let form = create_update_form(avis, idavis, idoffre);

	avis.removeChild(avis.querySelector(".avis-header"));
	avis.removeChild(avis.querySelector(".commentaire"));
	avis.removeChild(avis.querySelector("#pouceHaut"));
	avis.removeChild(avis.querySelector("#pouceBas"));
	avis.appendChild(form);
	button.style.display = "none";
}

function create_update_form(avis, idavis, idoffre) {
	let oldNote = avis.querySelector(".note_avis").innerHTML[1];
	let oldTitle = avis.querySelector(".titre_avis").innerHTML;
	let oldComment = avis.querySelector(".commentaire").innerHTML;

	let form = document.createElement("form");
	form.setAttribute("method", "post");
	form.setAttribute("enctype", "multipart/form-date");
	form.setAttribute("action", "../includes/modifier_avis.inc.php");

	append_new_note(form, oldNote);
	append_new_title(form, oldTitle);
	append_new_context(form, avis);
	append_new_comment(form, oldComment);

	let idAvisPost = document.createElement("input");
	idAvisPost.setAttribute("type", "text");
	idAvisPost.setAttribute("name", "idavis");
	idAvisPost.innerHTML = idavis;
	idAvisPost.setAttribute("value", idavis);
	idAvisPost.style.display = "none";
	form.appendChild(idAvisPost);

	let idOffrePost = document.createElement("input");
	idOffrePost.setAttribute("type", "text");
	idOffrePost.setAttribute("name", "idoffre");
	idOffrePost.innerHTML = idoffre;
	idOffrePost.setAttribute("value", idoffre);
	idOffrePost.style.display = "none";
	form.appendChild(idOffrePost);

	let submit = document.createElement("input");
	submit.setAttribute("type", "submit");
	submit.setAttribute("value", "Valider");
	form.appendChild(submit);

	return form;
}

function append_new_note(form, oldNote) {
	let noteInput = document.createElement("input");
	noteInput.setAttribute("type", "number");
	noteInput.setAttribute("name", "note");
	noteInput.setAttribute("min", "1");
	noteInput.setAttribute("max", "5");
	noteInput.setAttribute("value", oldNote);
	noteInput.setAttribute("placeholder", "Note");
	noteInput.setAttribute("required", "");
	form.appendChild(noteInput);
}

function append_new_title(form, oldTitle) {
	let titreInput = document.createElement("input");
	titreInput.setAttribute("type", "text");
	titreInput.setAttribute("name", "titre");
	titreInput.setAttribute("placeholder", "Titre");
	titreInput.setAttribute("value", oldTitle);
	titreInput.setAttribute("required", "");
	form.appendChild(titreInput);
}

function append_new_context(form, avis) {
	let contextSelect = document.createElement("select");
	contextSelect.setAttribute("required", "");
	contextSelect.setAttribute("name", "contexte");

	let optSeul = document.createElement("option");
	optSeul.setAttribute("value", "Seul");
	optSeul.innerHTML = "Seul";

	let optAmis = document.createElement("option");
	optAmis.setAttribute("value", "Entre amis");
	optAmis.innerHTML = "Entre amis";

	let optAmoureux = document.createElement("option");
	optAmoureux.setAttribute("value", "En amoureux");
	optAmoureux.innerHTML = "En amoureux";

	let optFamille = document.createElement("option");
	optFamille.setAttribute("value", "En famille");
	optFamille.innerHTML = "En famille";

	let oldCont = avis.querySelector(".contexte").innerHTML;
	if (oldCont == " En famille ") {
		optFamille.setAttribute("selected", "");
	} else if (oldCont == " Entre amis ") {
		optAmis.setAttribute("selected", "");
	} else if (oldCont == " En amoureux ") {
		optAmoureux.setAttribute("selected", "");
	} else {
		optSeul.setAttribute("selected", "");
	}

	contextSelect.appendChild(optSeul);
	contextSelect.appendChild(optFamille);
	contextSelect.appendChild(optAmis);
	contextSelect.appendChild(optAmoureux);

	form.appendChild(contextSelect);
}

function append_new_comment(form, oldComment) {
	let comment = document.createElement("textarea");
	comment.setAttribute("required", "");
	comment.setAttribute("name", "commentaire");
	comment.setAttribute("placeholder", "Renseigner un commentaire");
	comment.innerHTML = oldComment;
	form.appendChild(comment);
}
