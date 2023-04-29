var activeSettingsPage = null;
var activeSettingsTab = null;

jQuery(document).ready(function($) {
    
    // Add click and focus listeners to navigation tabs.
    var navTabs = document.querySelectorAll('.nav-tab');
    for (i = 0; i < navTabs.length; i++) {
        navTabs[i].addEventListener('click', function click(e) {
            e.preventDefault();
            e.currentTarget.focus();
        });

        navTabs[i].addEventListener('focus', function focus(e) {
            var baseLocation = location.href.replace(/#.*/, "");   // Remove everything after '#' hash.
            //console.log(`Focusin on tab:\nBase location: ${baseLocation} Hash: ${e.currentTarget.hash}`);
            history.pushState({}, "", baseLocation + e.currentTarget.hash);   // Set new location
            goToSettingsTabFromHash();
        });
    }
})


function goToSettingsTabFromHash() {
    var locationHash = location.hash.slice(1);
    locationHash && this.goToSettingsTab(locationHash);   // If e exists - the right hand is eveluated (and thus executed).
}


function goToSettingsTab(locationHash) {
    // console.log(`Location hash: ${locationHash}`);
    
    // Hide previous nav tab and section.
    activeSettingsTab = document.querySelector('.nav-tab-active');
    activeSettingsPage = document.querySelector('.sslabels-active');
    
    activeSettingsTab.classList.remove('nav-tab-active');
    activeSettingsPage.classList.remove('sslabels-active');
    
    // Show the new nav tab and section.
    activeSettingsTab = document.querySelector("#sslabels-settings-" + locationHash);
    activeSettingsPage = document.querySelector("#" + locationHash);
    
    activeSettingsTab.classList.add("nav-tab-active");
    activeSettingsPage.classList.add("sslabels-active");
}

// Hide element by toggling one of its class names.
function toggleDisplayClass(element) {
    element.classList.toggle("hide-if-js");
}