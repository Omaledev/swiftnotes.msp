// Make initializePusher available globally for blade templates
window.initializePusher = initializePusher;
import { initializePusher } from './bootstrap';

/**
 * Show a notification to the user
 * @param {string} message - The message to display
 * @param {string} type - The type of notification (info, success, warning, error)
 */
export function showNotification(message, type = 'info') {
    const types = {
        info: 'bg-blue-500',
        success: 'bg-green-500',
        warning: 'bg-yellow-500',
        error: 'bg-red-500'
    };

    // Remove any existing notifications
    document.querySelectorAll('.pusher-notification').forEach(el => el.remove());

    const notification = document.createElement('div');
    notification.className = `pusher-notification fixed bottom-4 right-4 ${types[type]} text-white px-4 py-2 rounded-md shadow-lg flex items-center`;
    notification.style.zIndex = '9999';
    notification.innerHTML = `
        <span>${message}</span>
        <button class="ml-2 text-white hover:text-gray-200" onclick="this.parentElement.remove()">
            <i class="fas fa-times"></i>
        </button>
    `;

    document.body.appendChild(notification);
    setTimeout(() => notification.remove(), 5000);
}

/**
 * Update user status indicator in the UI
 * @param {number} userId - The user ID
 * @param {boolean} isOnline - Whether the user is online
 */
export function updateUserStatusIndicator(userId, isOnline) {
    document.querySelectorAll(`[data-user-id="${userId}"] .user-status`).forEach(el => {
        el.className = `user-status inline-block w-2 h-2 rounded-full mr-1 ${isOnline ? 'bg-green-500' : 'bg-gray-400'}`;
        el.title = isOnline ? 'Online' : 'Offline';
    });
}

/**
 * Add an editing indicator for a user
 * @param {object} user - The user object
 */
export function addEditingIndicator(user) {
    const container = document.querySelector('.editing-indicators');
    if (!container) return;

    const existing = container.querySelector(`[data-user-id="${user.id}"]`);
    if (existing) return;

    const indicator = document.createElement('div');
    indicator.className = 'editor-indicator bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full mb-1 flex items-center';
    indicator.dataset.userId = user.id;
    indicator.innerHTML = `
        <i class="fas fa-pencil-alt mr-2"></i>
        ${user.name} is editing
    `;
    container.appendChild(indicator);
}

/**
 * Remove an editing indicator for a user
 * @param {number} userId - The user ID
 */
export function removeEditingIndicator(userId) {
    const indicator = document.querySelector(`.editor-indicator[data-user-id="${userId}"]`);
    if (indicator) indicator.remove();
}

/**
 * Update the note content in the UI
 * @param {object} note - The note object
 */
export function updateNoteContent(note) {
    const contentElement = document.querySelector('.note-content');
    if (contentElement) {
        contentElement.innerHTML = note.content;
    }
}

/**
 * Refresh the notes list
 */
export function refreshNotesList() {
    const notesContainer = document.querySelector('#notes-container');
    if (notesContainer) {
        fetch(window.location.href)
            .then(response => response.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const newContent = doc.querySelector('#notes-container').innerHTML;
                notesContainer.innerHTML = newContent;
            })
            .catch(console.error);
    }
}

/**
 * Track user online status
 * @param {boolean} isOnline - Whether the user is online
 * @param {number} teamId - The team ID
 */
export function updateOnlineStatus(isOnline, teamId) {
    axios.post('/api/user-online', {
        is_online: isOnline,
        team_id: teamId
    }).catch(error => {
        console.error('Online status update failed:', error);
    });
}

/**
 * Initialize real-time features for a team
 * @param {number} teamId - The team ID
 * @param {number|null} noteId - Optional note ID
 */
export function initializeRealTimeFeatures(teamId, noteId = null) {
    // Initialize Pusher connection
    const echo = initializePusher();

    // Store IDs for later use
    window.currentTeamId = teamId;
    if (noteId) window.currentNoteId = noteId;

    // Initialize team channel
    const teamChannel = echo.channel(`team.${teamId}`);

    teamChannel
        .listen('.note.created', (data) => {
            showNotification(`📝 New note: ${data.note.title}`, 'success');
            if (shouldReloadNotesList()) {
                setTimeout(refreshNotesList, 300); // Small delay to ensure data is ready
            }
        })
        .listen('.note.updated', (data) => {
            showNotification(`✏️ Note updated: ${data.note.title}`, 'info');
            if (isOnNotePage(data.note.id)) {
                updateNoteContent(data.note);
            } else if (shouldReloadNotesList()) {
                setTimeout(refreshNotesList, 300);
            }
        })
        .listen('.user.status.updated', (data) => {
            showNotification(
                `${data.user.name} is now ${data.isOnline ? '🟢 online' : '🔴 offline'}`,
                data.isOnline ? 'success' : 'warning'
            );
            updateUserStatusIndicator(data.user.id, data.isOnline);
        });

    // Initialize note channel if we have a note ID
    if (noteId) {
        const noteChannel = echo.channel(`note.${noteId}`);

        noteChannel
            .listen('.user.editing', (data) => {
                if (data.user.id !== window.user?.id) {
                    addEditingIndicator(data.user);
                }
            })
            .listen('.user.stopped.editing', (data) => {
                removeEditingIndicator(data.user.id);
            });
    }

    // Track user's online status
    window.addEventListener('load', () => updateOnlineStatus(true, teamId));
    window.addEventListener('beforeunload', () => updateOnlineStatus(false, teamId));
}

// Helper functions
function shouldReloadNotesList() {
    return window.location.pathname.includes('notes') &&
           !window.location.pathname.includes('notes/');
}

function isOnNotePage(noteId) {
    return window.location.pathname.includes(`notes/${noteId}`);
}

// Make functions available globally for blade templates
window.showNotification = showNotification;
window.initializeRealTimeFeatures = initializeRealTimeFeatures;