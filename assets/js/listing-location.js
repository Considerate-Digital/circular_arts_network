setTimeout(() => {
	let postcode = document.getElementById("listing_zipcode");
	let location_search = document.querySelector(".leaflet-control-geocoder-form input");
	console.log(location_search);
			postcode.addEventListener("blur", () => {
		console.log("setting location search");
		console.log(postcode.value);
		location_search.value = postcode.value;	
		location_search.dispatchEvent(new KeyboardEvent("keydown", {
			code: 'Enter',
				key: 'Enter',
				charCode: 13,
				keyCode: 13,
				view: window,
				bubbles: true
		}));
		});
}, 3000);


