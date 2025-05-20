const gradeSelect = document.getElementById('grade_level');
const strandSection = document.getElementById('strand-section');
const submitBtn = document.getElementById('submitBtn');
const registrationForm = document.getElementById('registrationForm');

// Toggle Strand Section based on Grade Level selection
gradeSelect.addEventListener('change', function () {
  if (this.value === 'Grade 11/12') {
    strandSection.style.display = 'block';  // Show strand section
  } else {
    strandSection.style.display = 'none';   // Hide strand section
  }
});

// Show SweetAlert when form is submitted
submitBtn.addEventListener('click', function (event) {
  event.preventDefault(); // Prevent the form from submitting immediately

  // Validate form (if needed, add more validation here)
  const fullName = document.getElementById('full_name').value;
  const email = document.getElementById('email').value;
  const phone = document.getElementById('phone').value;
  const dob = document.getElementById('dob').value;
  const gradeLevel = document.getElementById('grade_level').value;

  if (!fullName || !email || !phone || !dob || !gradeLevel) {
    Swal.fire({
      title: "Error!",
      text: "Please fill in all the fields.",
      icon: "error"
    });
  } else {
    // Show SweetAlert success and submit form after confirmation
    Swal.fire({
      title: "Good job!",
      text: "You clicked the button!",
      icon: "success"
    }).then((result) => {
      if (result.isConfirmed) {
        registrationForm.submit();  // Submit the form programmatically
      }
    });
  }
});
