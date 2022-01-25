(function( $ ) {
	'use strict';

	/**
	 * All of the code for your public-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */

	document.addEventListener('DOMContentLoaded', function() {
		const postCode = thspScript.thspPostCode
		// const prodChecklist = thspScript.thspChecklist
		const postCodes = postCode.split(',')
		// const prodChecklists = checklist.split(/\r?\n/)
		const inputPostCode = document.querySelector('#post_code')
		const proceedBtn = document.querySelector('#proceed_btn')
		const multiSelect = document.querySelector('#multiselect')

		// console.log(postCodes)

		if (inputPostCode !== null && postCodes.length > 0 && proceedBtn !== null && multiSelect !== null) {

			const selectBox = multiSelect.querySelector('#select_box')
			const selectTitle = selectBox.querySelector('span')
			const checkbox = multiSelect.querySelector("#checkbox_group");
			let expanded = false;

			const handleInputPostCodeKeypress = (e) => {
        var charCode = (e.which) ? e.which : e.keyCode
        if (charCode > 31 && (charCode < 48 || charCode > 57)) {
					e.preventDefault()
          return false;
        } else {
          return true;
        }
      }
			
			const handleInputPostCodeChange = (e) => {
				e.preventDefault()
				const el = e.target

				if (postCodes.includes(el.value)) {
					alert('Success!')
					proceedBtn.disabled = false
				} else {
					alert(`The post code ${el.value} is not available!`)
					el.value = ''
					proceedBtn.disabled = true
				}
			}

			const handleSelectBoxClick = () => {
				if (!expanded) {
					selectTitle.classList.add('activ')
					checkbox.classList.add('activ')
					checkbox.style.display = "block";
					expanded = true;
				} else {
					selectTitle.classList.remove('activ')
					checkbox.classList.remove('activ')
					checkbox.style.display = "none";
					expanded = false;
				}
			}

			const handleBodyClick = (checkbox) => {
				return function(e) {
					if (e.target.closest('#multiselect')) {
						// do nothing
					} else {
						checkbox.classList.remove('activ')
						checkbox.style.display = "none";
						expanded = false;
					}
				}
			}

			inputPostCode.addEventListener('keypress', handleInputPostCodeKeypress)
			inputPostCode.addEventListener('change', handleInputPostCodeChange)
			selectBox.addEventListener('click', handleSelectBoxClick)
			document.body.addEventListener('click', handleBodyClick(checkbox))
			
		}
	})

})( jQuery );
