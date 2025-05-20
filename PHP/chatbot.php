<?php
header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);
$prompt = strtolower(trim($input['prompt'] ?? ''));

// Simple AI-like response logic
function getResponse($prompt) {
    if (strpos($prompt, 'hello') !== false || strpos($prompt, 'hi') !== false) {
        return "Hi there! I'm your assistant from Lakeview Integrated School. How can I help you?";
    }

    if (strpos($prompt, 'admission') !== false) {
        return "Admission details can be found in the 'Admission' section. Would you like help with the process or requirements?";
    }

    if (strpos($prompt, 'scholarship') !== false) {
        return "Lakeview offers various scholarships based on merit and need. Want to know the qualifications?";
    }

    if (strpos($prompt, 'strand') !== false || strpos($prompt, 'course') !== false) {
        return "We offer strands like STEM, ICT, HUMSS, GAS, and Home Economics. Let me know which one you want info about.";
    }

    if (strpos($prompt, 'location') !== false || strpos($prompt, 'where') !== false) {
        return "Lakeview Integrated School is located in [Your School Address]. We're open weekdays from 8 AM to 5 PM.";
    }

    if (strpos($prompt, 'help') !== false) {
        return "Sure! I can assist with admissions, enrollment, scholarships, or portal access.";
    }

    if (strpos($prompt, 'thank') !== false) {
        return "You're welcome! Let me know if there's anything else I can assist with.";
    }

    // Copilot-style fallback
    return "I'm here to help! Try asking about enrollment, courses, requirements, or our services.";
}

echo json_encode(["output_text" => getResponse($prompt)]);
?>
