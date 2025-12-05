<script>
console.log("City loader script loaded!");

// Pass the saved city value from PHP to JavaScript
@php
    $savedCity = isset($entry) && $entry->person ? $entry->person->city : null;
@endphp
var phpSavedCity = {!! json_encode($savedCity) !!};
console.log("City from PHP/Database:", phpSavedCity);

(function() {
    function initCityLoader() {
        if (typeof jQuery === 'undefined') {
            console.log("jQuery not ready yet, retrying...");
            setTimeout(initCityLoader, 100);
            return;
        }
        
        console.log("jQuery is available, initializing...");
        
        jQuery(document).ready(function($) {
            console.log("DOM ready - looking for fields");
            
            setTimeout(function() {
                var countryField = $("select[name='person[country]']");
                var cityField = $("select[name='person[city]']");
                
                console.log("Country field:", countryField.length, countryField);
                console.log("City field:", cityField.length, cityField);
                
                if (countryField.length === 0) {
                    console.error("Country field not found!");
                    return;
                }
                
                if (cityField.length === 0) {
                    console.error("City field not found!");
                    return;
                }
                
                // Use the PHP value as the saved city
                var savedCity = phpSavedCity;
                
                console.log("Saved city from database:", savedCity);
                console.log("City field initial HTML:", cityField.html());
                
                function loadCities(countryCode, selectedCity) {
                    console.log("Loading cities for country:", countryCode, "Selected city to restore:", selectedCity);
                    
                    if (!countryCode || countryCode === "separator") {
                        cityField.empty().append('<option value="">Select a city</option>');
                        return;
                    }
                    
                    cityField.prop('disabled', true);
                    cityField.empty().append('<option value="">Loading cities...</option>');
                    
                    $.ajax({
                        url: "/admin/user/cities/" + countryCode,
                        method: "GET",
                        dataType: "json",
                        success: function(cities) {
                            console.log("Cities loaded successfully:", Object.keys(cities).length, "cities");
                            console.log("Will select city:", selectedCity);
                            
                            cityField.empty();
                            cityField.append('<option value="">Select a city</option>');
                            
                            var cityFound = false;
                            $.each(cities, function(key, value) {
                                var isSelected = selectedCity && (key === selectedCity || value === selectedCity);
                                if (isSelected) {
                                    console.log("Found matching city - key:", key, "value:", value);
                                    cityFound = true;
                                }
                                var option = $('<option></option>')
                                    .attr('value', key)
                                    .text(value);
                                if (isSelected) {
                                    option.prop('selected', true);
                                }
                                cityField.append(option);
                            });
                            
                            cityField.prop('disabled', false);
                            
                            // Force set the value after a small delay
                            if (selectedCity && cityFound) {
                                setTimeout(function() {
                                    cityField.val(selectedCity);
                                    console.log("Final city value:", cityField.val());
                                    console.log("Selected option text:", cityField.find('option:selected').text());
                                }, 100);
                            } else if (selectedCity) {
                                console.warn("City not found in list:", selectedCity);
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error("Failed to load cities:", {
                                status: status,
                                error: error,
                                response: xhr.responseText
                            });
                            cityField.empty().append('<option value="">Error loading cities</option>');
                            cityField.prop('disabled', false);
                        }
                    });
                }
                
                countryField.on("change", function() {
                    var selectedCountry = $(this).val();
                    console.log("Country selection changed to:", selectedCountry);
                    loadCities(selectedCountry, null);
                });
                
                // Check initial value on page load (edit mode)
                var initialCountry = countryField.val();
                console.log("Initial country value on load:", initialCountry);
                
                if (initialCountry && initialCountry !== "" && initialCountry !== "separator" && savedCity && savedCity !== "" && savedCity !== null) {
                    console.log("Edit mode detected - loading cities with saved city:", savedCity);
                    loadCities(initialCountry, savedCity);
                }
            }, 1000);
        });
    }
    
    initCityLoader();
})();
</script>
