// SmartMail AI - AJAX Functions

const API_BASE = '';

// Login User
async function loginUser(email, password) {
    try {
        const response = await fetch(`${API_BASE}backend/auth/login.php`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ email, password })
        });
        return await response.json();
    } catch (error) {
        return { success: false, message: 'Network error. Please try again.' };
    }
}

// Register User
async function registerUser(name, email, password) {
    try {
        const response = await fetch(`${API_BASE}backend/auth/register.php`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ name, email, password })
        });
        return await response.json();
    } catch (error) {
        return { success: false, message: 'Network error. Please try again.' };
    }
}

// Send Email
async function sendEmail(to, subject, body, sendNow = true, scheduleTime = null, draftId = null) {
    try {
        const data = { to, subject, body, sendNow };
        if (draftId) {
            data.draftId = draftId;
        }
        
        const response = await fetch(`${API_BASE}backend/email/send-email.php`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        });
        
        const result = await response.json();
        
        // If email was saved and needs scheduling
        if (result.success && !sendNow && scheduleTime && result.email_id) {
            const scheduleResult = await scheduleEmail(result.email_id, scheduleTime);
            return scheduleResult;
        }
        
        return result;
    } catch (error) {
        return { success: false, message: 'Network error. Please try again.' };
    }
}

// Schedule Email
async function scheduleEmail(emailId, sendTime) {
    try {
        const response = await fetch(`${API_BASE}backend/email/schedule-email.php`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ email_id: emailId, send_time: sendTime })
        });
        return await response.json();
    } catch (error) {
        return { success: false, message: 'Network error. Please try again.' };
    }
}

// Delete Email
async function deleteEmail(emailId, permanent = false) {
    try {
        const response = await fetch(`${API_BASE}backend/email/delete-email.php`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ email_id: emailId, permanent })
        });
        return await response.json();
    } catch (error) {
        return { success: false, message: 'Network error. Please try again.' };
    }
}

