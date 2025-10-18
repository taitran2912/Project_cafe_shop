
// Payment method toggle
const paymentOptions = document.querySelectorAll('input[name="payment"]');
const cardDetails = document.getElementById('cardDetails');

paymentOptions.forEach(option => {
    option.addEventListener('change', function() {
        if (this.value === 'card') {
            cardDetails.style.display = 'block';
        } else {
            cardDetails.style.display = 'none';
        }
    });
});

// Form validation
const form = document.getElementById('checkoutForm');

form.addEventListener('submit', function(e) {
    e.preventDefault();

    // Reset error states
    document.querySelectorAll('.form-group').forEach(group => {
        group.classList.remove('error');
    });

    let isValid = true;

    // Validate required fields
    const requiredFields = form.querySelectorAll('[required]');
    requiredFields.forEach(field => {
        if (!field.value.trim()) {
            field.parentElement.classList.add('error');
            isValid = false;
        }
    });

    // Validate email
    const email = document.getElementById('email');
    if (email.value && !email.value.match(/^[^\s@]+@[^\s@]+\.[^\s@]+$/)) {
        email.parentElement.classList.add('error');
        isValid = false;
    }

    // Validate phone
    const phone = document.getElementById('phone');
    if (phone.value && !phone.value.match(/^[0-9]{10,11}$/)) {
        phone.parentElement.classList.add('error');
        isValid = false;
    }

    // Validate card details if card payment is selected
    if (document.getElementById('card').checked) {
        const cardNumber = document.getElementById('cardNumber');
        if (cardNumber.value && !cardNumber.value.replace(/\s/g, '').match(/^[0-9]{16}$/)) {
            cardNumber.parentElement.classList.add('error');
            isValid = false;
        }

        const expiry = document.getElementById('expiry');
        if (expiry.value && !expiry.value.match(/^(0[1-9]|1[0-2])\/\d{2}$/)) {
            expiry.parentElement.classList.add('error');
            isValid = false;
        }

        const cvv = document.getElementById('cvv');
        if (cvv.value && !cvv.value.match(/^[0-9]{3}$/)) {
            cvv.parentElement.classList.add('error');
            isValid = false;
        }
    }

    if (isValid) {
        // Show success message
        document.getElementById('successMessage').style.display = 'block';
        form.reset();
        cardDetails.style.display = 'none';

        // Scroll to top
        window.scrollTo({ top: 0, behavior: 'smooth' });

        // Hide success message after 5 seconds
        setTimeout(() => {
            document.getElementById('successMessage').style.display = 'none';
        }, 5000);
    }
});

// Format card number with spaces
document.getElementById('cardNumber').addEventListener('input', function(e) {
    let value = e.target.value.replace(/\s/g, '');
    let formattedValue = value.replace(/(\d{4})/g, '$1 ').trim();
    e.target.value = formattedValue;
});

// Format expiry date
document.getElementById('expiry').addEventListener('input', function(e) {
    let value = e.target.value.replace(/\D/g, '');
    if (value.length >= 2) {
        value = value.slice(0, 2) + '/' + value.slice(2, 4);
    }
    e.target.value = value;
});
