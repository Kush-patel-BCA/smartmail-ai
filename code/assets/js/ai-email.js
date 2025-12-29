// SmartMail AI - AI Email Generation

document.addEventListener('DOMContentLoaded', function() {
    const generateBtn = document.getElementById('generateBtn');
    const aiCommand = document.getElementById('aiCommand');
    const emailSubject = document.getElementById('emailSubject');
    const emailBody = document.getElementById('emailBody');
    const toneSelect = document.getElementById('toneSelect');
    const sendBtn = document.getElementById('sendBtn');
    const saveDraftBtn = document.getElementById('saveDraftBtn');
    
    if (!generateBtn) return;
    
    // Generate Email with AI
    generateBtn.addEventListener('click', async function() {
        const command = aiCommand.value.trim();
        if (!command) {
            showAlert('warning', 'Please enter a command for the AI assistant');
            return;
        }
        
        // Show loading state
        const originalText = this.innerHTML;
        this.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Generating...';
        this.disabled = true;
        
        try {
            const tone = toneSelect.value;
            const result = await generateEmail(command, tone);
            
            if (result.success) {
                emailSubject.value = result.subject;
                emailBody.value = result.body;
                
                // Animate the typing effect
                animateTyping(emailBody);
                
                showAlert('success', 'Email generated successfully!');
            } else {
                showAlert('danger', result.message || 'Failed to generate email');
            }
        } catch (error) {
            showAlert('danger', 'Error generating email. Please try again.');
        } finally {
            this.innerHTML = originalText;
            this.disabled = false;
        }
    });
    
    // Send Email
    if (sendBtn) {
        sendBtn.addEventListener('click', async function() {
            const to = document.getElementById('emailTo').value;
            const subject = emailSubject.value;
            const body = emailBody.value;
            const scheduleTime = document.getElementById('scheduleTime').value;
            const draftId = this.dataset.draftId || null;
            
            if (!to || !subject || !body) {
                showAlert('warning', 'Please fill in all required fields');
                return;
            }
            
            // Show loading
            this.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Sending...';
            this.disabled = true;
            
            try {
                const sendNow = !scheduleTime;
                const result = await sendEmail(to, subject, body, sendNow, scheduleTime, draftId);
                
                if (result.success) {
                    showAlert('success', scheduleTime ? 'Email scheduled successfully!' : 'Email sent successfully!');
                    
                    // Reset form
                    document.getElementById('emailTo').value = '';
                    aiCommand.value = '';
                    emailSubject.value = '';
                    emailBody.value = '';
                    document.getElementById('scheduleTime').value = '';
                    
                    // Reset draft ID
                    delete this.dataset.draftId;
                    const saveDraftBtn = document.getElementById('saveDraftBtn');
                    if (saveDraftBtn) {
                        delete saveDraftBtn.dataset.draftId;
                        saveDraftBtn.innerHTML = '<i class="fas fa-save me-1"></i> Save Draft';
                    }
                    
                    // Close modal
                    const modal = bootstrap.Modal.getInstance(document.getElementById('composeModal'));
                    modal.hide();
                    
                    // Switch to Sent section
                    const sentBtn = document.querySelector('.menu-item[data-filter="sent"]');
                    if (sentBtn) {
                        sentBtn.click();
                    } else if (typeof loadEmails === 'function') {
                        loadEmails('sent');
                    }
                } else {
                    showAlert('danger', result.message || 'Failed to send email');
                }
            } catch (error) {
                showAlert('danger', 'Error sending email. Please try again.');
            } finally {
                this.innerHTML = '<i class="fas fa-paper-plane me-2"></i>Send';
                this.disabled = false;
            }
        });
    }
    
    // Save Draft
    if (saveDraftBtn) {
        saveDraftBtn.addEventListener('click', async function() {
            const to = document.getElementById('emailTo').value || '';
            const subject = document.getElementById('emailSubject').value || '';
            const body = document.getElementById('emailBody').value || '';
            const draftId = this.dataset.draftId || null;
            
            // Allow saving draft with minimal content (at least subject or body)
            if (!subject && !body) {
                showAlert('warning', 'Please add some content to save as draft');
                return;
            }
            
            this.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Saving...';
            this.disabled = true;
            
            try {
                const result = await saveDraft(to, subject, body, draftId);
                
                if (result.success) {
                    if (typeof showAlert === 'function') {
                        showAlert('success', 'Draft saved successfully!');
                    } else {
                        alert('Draft saved successfully!');
                    }
                    
                    // Store draft ID for future updates
                    if (result.draft_id) {
                        this.dataset.draftId = result.draft_id;
                        this.innerHTML = '<i class="fas fa-save me-1"></i> Update Draft';
                    }
                    
                    // Reload emails to show updated draft
                    if (typeof loadEmails === 'function') {
                        loadEmails('draft');
                    }
                } else {
                    if (typeof showAlert === 'function') {
                        showAlert('danger', result.message || 'Failed to save draft');
                    } else {
                        alert('Error: ' + (result.message || 'Failed to save draft'));
                    }
                }
            } catch (error) {
                console.error('Error saving draft:', error);
                if (typeof showAlert === 'function') {
                    showAlert('danger', 'Error saving draft. Please try again.');
                } else {
                    alert('Error saving draft. Please check console for details.');
                }
            } finally {
                if (!this.dataset.draftId) {
                    this.innerHTML = '<i class="fas fa-save me-1"></i> Save Draft';
                }
                this.disabled = false;
            }
        });
    }
});

// Generate Email with AI
async function generateEmail(command, tone = 'Professional') {
    try {
        const response = await fetch('backend/ai/generate-email.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ command, tone })
        });
        return await response.json();
    } catch (error) {
        return { success: false, message: 'Network error. Please try again.' };
    }
}

// Animate Typing Effect
function animateTyping(element) {
    const text = element.value;
    element.value = '';
    element.style.opacity = '0.5';
    
    let i = 0;
    const typingInterval = setInterval(() => {
        if (i < text.length) {
            element.value += text.charAt(i);
            i++;
            element.scrollTop = element.scrollHeight;
        } else {
            clearInterval(typingInterval);
            element.style.opacity = '1';
        }
    }, 10);
}

// Save Draft Function
async function saveDraft(to, subject, body, draftId = null) {
    try {
        const data = { to, subject, body, draftId };
        
        const response = await fetch('backend/email/save-draft.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        });
        
        return await response.json();
    } catch (error) {
        return { success: false, message: 'Network error. Please try again.' };
    }
}

// Load Draft for Editing (Global function)
window.loadDraftForEditing = function(email) {
    // Open compose modal
    const composeModal = new bootstrap.Modal(document.getElementById('composeModal'));
    composeModal.show();
    
    // Fill in the form
    document.getElementById('emailTo').value = email.receiver || '';
    document.getElementById('emailSubject').value = email.subject || '';
    document.getElementById('emailBody').value = email.body || '';
    document.getElementById('aiCommand').value = '';
    
    // Set draft ID on save button
    const saveDraftBtn = document.getElementById('saveDraftBtn');
    if (saveDraftBtn) {
        saveDraftBtn.dataset.draftId = email.id;
        saveDraftBtn.innerHTML = '<i class="fas fa-save me-1"></i> Update Draft';
    }
    
    // Update send button to indicate it's a draft
    const sendBtn = document.getElementById('sendBtn');
    if (sendBtn) {
        sendBtn.dataset.draftId = email.id;
    }
}

// Show Alert (if not defined in main.js)
if (typeof showAlert === 'undefined') {
    function showAlert(type, message) {
        const alertContainer = document.getElementById('alert-container') || document.body;
        const alert = document.createElement('div');
        alert.className = `alert alert-${type} alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x mt-3`;
        alert.style.zIndex = '9999';
        alert.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        alertContainer.appendChild(alert);
        setTimeout(() => alert.remove(), 5000);
    }
}

