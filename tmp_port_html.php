<?php
$uiPath = 'uihtml/registration-manager.html';
$bladePath = 'resources/views/pages/enquiry/registration.blade.php';

$uiContent = file_get_contents($uiPath);
$bladeContent = file_get_contents($bladePath);

// Extract the 'enquiry-container' and all modals from UI HTML
preg_match('/<div class="enquiry-container">.*<!-- ADMISSION CONFIRMATION MODAL \(unchanged\) -->.*?<\/div>\s*<\/div>/s', $uiContent, $matches);
if (!isset($matches[0])) {
    preg_match('/<div class="enquiry-container">.*<!-- ADMISSION CONFIRMATION MODAL.*?<\/div>\s*<\/div>/s', $uiContent, $matches);
}
if (!isset($matches[0])) {
   // Try simpler regex
   $start = strpos($uiContent, '<div class="enquiry-container">');
   $end = strpos($uiContent, '<script>', $start);
   $htmlBlock = substr($uiContent, $start, $end - $start);
} else {
   $htmlBlock = $matches[0];
}

// Now we have the FULL EXACT HTML block. We must re-add the forms and inputs.
// 1. ADD FORM AND CSRF TO MAIN MODAL
$htmlBlock = str_replace(
    '<div class="modal-tabs">', 
    '<form id="registrationForm" onsubmit="return false;">' . "\n" . '            @csrf' . "\n" . '            <input type="hidden" id="regId" name="regId">' . "\n" . '        <div class="modal-tabs">', 
    $htmlBlock
);
$htmlBlock = str_replace(
    '<div class="form-group">
                        <label>Registration Date</label>
                        <input type="date" id="regDate" value="2026-03-01">
                    </div>', 
    '<div class="form-group">
                        <label>Registration Date</label>
                        <input type="date" id="regDate" name="reg_date" value="{{ date(\'Y-m-d\') }}">
                    </div>', 
    $htmlBlock
);
$htmlBlock = str_replace(
    '<select id="regStatus">',
    '<select id="regStatus" name="status">',
    $htmlBlock
);
$htmlBlock = str_replace(
    '<input type="text" id="fatherName" placeholder="Enter father\'s name">',
    '<input type="text" id="fatherName" name="father_name" placeholder="Enter father\'s name" required>',
    $htmlBlock
);
$htmlBlock = str_replace(
    '<input type="text" id="fatherMobile" placeholder="Mobile number">',
    '<input type="text" id="fatherMobile" name="father_mobile" placeholder="Mobile number" required>',
    $htmlBlock
);
$htmlBlock = str_replace(
    '<input type="text" id="motherName" placeholder="Enter mother\'s name">',
    '<input type="text" id="motherName" name="mother_name" placeholder="Enter mother\'s name">',
    $htmlBlock
);
$htmlBlock = str_replace(
    '<input type="text" id="motherMobile" placeholder="Mobile number">',
    '<input type="text" id="motherMobile" name="mother_mobile" placeholder="Mobile number">',
    $htmlBlock
);
$htmlBlock = str_replace(
    '<input type="email" id="email" placeholder="Email address">',
    '<input type="email" id="email" name="email" placeholder="Email address">',
    $htmlBlock
);
$htmlBlock = str_replace(
    '<input type="text" id="addr1" placeholder="Address">',
    '<input type="text" id="addr1" name="address1" placeholder="Address">',
    $htmlBlock
);
$htmlBlock = str_replace(
    '<input type="text" id="addr2" placeholder="Address (optional)">',
    '<input type="text" id="addr2" name="address2" placeholder="Address (optional)">',
    $htmlBlock
);
$htmlBlock = str_replace(
    '<input type="text" id="city" placeholder="City">',
    '<input type="text" id="city" name="city" placeholder="City">',
    $htmlBlock
);
$htmlBlock = str_replace(
    '<textarea id="remarks" placeholder="Any remarks..."></textarea>',
    '<textarea id="remarks" name="remarks" placeholder="Any remarks..."></textarea>',
    $htmlBlock
);
// End form block inside main modal
$htmlBlock = str_replace(
    '<!-- SUB MODAL',
    '</form>' . "\n" . '<!-- SUB MODAL',
    $htmlBlock
);

// Add ADMISSION FORM logic
$htmlBlock = str_replace(
    '<div class="modal-actions">
            <button class="btn" id="cancelAdmission">Cancel</button>
            <button class="btn btn-primary" id="confirmAdmission">Confirm</button>
        </div>',
    '<form action="" id="admissionForm" method="POST">
            @csrf
            <div class="action-buttons">
                <button type="button" class="btn" id="cancelAdmission">Cancel</button>
                <button type="submit" class="btn btn-primary">Confirm</button>
            </div>
        </form>',
    $htmlBlock
);

// Add BLADE table foreach logic
$tableReplacement = '<tbody id="tableBody">
                @forelse($registrations as $reg)
                    <tr>
                        <td>{{ $reg->reg_no ?: (\'REG\' . str_pad($reg->id, 3, \'0\', STR_PAD_LEFT)) }}</td>
                        <td>{{ $reg->student_name }}</td>
                        <td>
                            @if($reg->class)
                                {{ $reg->class }}@if($reg->section) - {{ $reg->section }}@endif
                            @else
                                <span style="color:#aaa;">N/A</span>
                            @endif
                        </td>
                        <td>{{ $reg->father_mobile }}</td>
                        <td>{{ $reg->email ?? \'N/A\' }}</td>
                        <td>{{ \Carbon\Carbon::parse($reg->reg_date)->format(\'d-M-Y\') }}</td>
                        <td>
                            <div class="action-icons">
                                <i class="fas fa-eye view-icon" title="View" onclick="viewRegistration({{ $reg->id }})"></i>
                                <i class="fas fa-edit edit-icon" title="Edit" onclick="editRegistration({{ $reg->id }})"></i>
                                <form action="{{ route(\'admin.registration.destroy\', $reg->id) }}" method="POST" style="display:inline;" onsubmit="return confirm(\'Delete this registration?\');">
                                    @csrf
                                    @method(\'DELETE\')
                                    <button type="submit" style="background:none; border:none; padding:0; cursor:pointer;">
                                        <i class="fas fa-trash delete-icon" title="Delete"></i>
                                    </button>
                                </form>
                                @if($reg->status == \'Register\')
                                    <span class="status-badge" onclick="confirmAdmission({{ $reg->id }})">{{ $reg->status }}</span>
                                @else
                                    <span class="status-badge confirmed">Admission Confirm</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align:center;">No registrations found.</td>
                    </tr>
                @endforelse
            </tbody>';

$htmlBlock = preg_replace('/<tbody id="tableBody">.*?<\/tbody>/s', $tableReplacement, $htmlBlock);

$paginationReplacement = '<div class="pagination-bar" style="margin-top: 15px;">
        {{ $registrations->links(\'pagination::bootstrap-4\') }}
    </div>';
$htmlBlock = preg_replace('/<div class="pagination-bar">.*?<\/div>\s*<\/div>/s', $paginationReplacement, $htmlBlock);

// Replace submodal logic 
$htmlBlock = str_replace(
    '<select id="studentClass">
                        <option>Nursery</option><option>KG</option><option>I</option><option>II</option><option>III</option>
                        <option>IV</option><option>V</option><option>VI</option><option>VII</option><option>VIII</option>
                    </select>',
    '<select id="studentClass">
                        <option value="">Select Class</option>
                        @foreach($globalClasses as $class)
                            <option value="{{ $class->name }}">{{ $class->name }}</option>
                        @endforeach
                    </select>',
    $htmlBlock
);

// Insert into Blade
$start = strpos($bladeContent, '<div class="enquiry-container">');
$end = strpos($bladeContent, '<script>', $start);
$newBladeContent = substr_replace($bladeContent, $htmlBlock . "\n\n", $start, $end - $start);

// Also make sure .modal-overlay.show is .open
$newBladeContent = str_replace('.modal-overlay.show', '.modal-overlay.open, .modal-overlay.show', $newBladeContent);

// Fix button types inside modals to prevent form auto-submit!
$newBladeContent = str_replace('<button class="btn"', '<button type="button" class="btn"', $newBladeContent);
$newBladeContent = str_replace('<button class="add-student-btn"', '<button type="button" class="add-student-btn"', $newBladeContent);

// Ensure the ID of AddStudent is 'saveStudent' and uses type="button"
$newBladeContent = str_replace('<button type="button" class="btn btn-primary" id="saveStudent">', '<button type="button" class="btn btn-primary" id="saveStudent">', $newBladeContent);
$newBladeContent = str_replace('<button type="button" class="btn btn-primary" id="saveFamilyContinue">', '<button type="button" class="btn btn-primary" id="saveFamilyContinue">', $newBladeContent);
$newBladeContent = str_replace('<button type="button" class="btn btn-primary" id="saveRegistrationBtn">', '<button type="button" class="btn btn-primary" id="saveRegistrationBtn">', $newBladeContent);
$newBladeContent = str_replace('<button type="submit" class="btn btn-primary" id="confirmAdmission">', '<button type="submit" class="btn btn-primary" id="confirmAdmission">', $newBladeContent);


file_put_contents($bladePath, $newBladeContent);
echo "Blade HTML ported successfully!\n";
