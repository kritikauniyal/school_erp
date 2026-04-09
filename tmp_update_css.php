<?php
$uiPath = 'uihtml/registration-manager.html';
$bladePath = 'resources/views/pages/enquiry/registration.blade.php';

$uiContent = file_get_contents($uiPath);
$bladeContent = file_get_contents($bladePath);

// Extract CSS from uihtml/registration-manager.html for modals 
// (From /* ===== MODAL STYLES (reused) ===== */ to /* mobile adjustments */)
preg_match('/\/\* ===== MODAL STYLES.*?((?=\/\* mobile adjustments)|(?=<\/style>))/s', $uiContent, $cssMatches);
if (isset($cssMatches[0])) {
    $uiCss = $cssMatches[0];
    
    // Replace CSS in blade (from /* Modal Structure */ to <style>)
    $bladeContent = preg_replace('/\/\* Modal Structure \*\/.*?(?=<\/style>)/s', $uiCss, $bladeContent);
    echo "CSS updated successfully.\n";
} else {
    echo "CSS extraction failed!\n";
}

// Ensure .open class exists alongside .show just in case
$bladeContent = str_replace('.modal-overlay.show', '.modal-overlay.open, .modal-overlay.show', $bladeContent);
$bladeContent = str_replace('.submodal-overlay.show', '.submodal-overlay.open, .submodal-overlay.show', $bladeContent);

file_put_contents($bladePath, $bladeContent);
echo "Blade updated!\n";
