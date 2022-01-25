(function( $ ) {
	'use strict';

	/**
	 * All of the code for your admin-facing JavaScript source
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
		const tabLinks = [...document.querySelectorAll('.tab_link')]
		const tabContents = [...document.querySelectorAll('.tab_content')]

		if (tabLinks.length > 0 && tabContents.length > 0) {

			for (let i=0; i<tabLinks.length; i++) {
				
				tabLinks[i].addEventListener('click', e => {
					e.preventDefault()
					const dataLink = tabLinks[i].getAttribute('data-link')
					const tabId = tabContents[i].id

					if (tabId.indexOf(dataLink) > -1) {
						tabLinks.forEach(tab => tab.classList.remove('activ'))
						tabContents.forEach(tab => tab.style.display = 'none')
						tabLinks[i].classList.add('activ')
						tabContents[i].style.display = 'block'
					}
				})
			}
		}
	})

})( jQuery );
