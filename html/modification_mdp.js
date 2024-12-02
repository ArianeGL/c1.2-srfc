document.querySelectorAll('selection_mdp input')
    .forEach(input =>
            input.addEventListener('input',(e) => {
                if (input.dataset.next && e.target.value != ''){
                    document.getElementById(input.dataset.next).focus();
                }
            }));