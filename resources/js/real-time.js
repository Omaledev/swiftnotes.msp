import { initializePusher } from './bootstrap.js';

// Notification system
export function showNotification(message, type = 'info') {
    const types = {
        info: { bg: 'bg-blue-500', icon: 'fa-info-circle' },
        success: { bg: 'bg-green-500', icon: 'fa-check-circle' },
        warning: { bg: 'bg-yellow-500', icon: 'fa-exclamation-triangle' },
        error: { bg: 'bg-red-500', icon: 'fa-times-circle' }
    };

    // Remove existing notifications
    document.querySelectorAll('.pusher-notification').forEach(el => el.remove());

    const notification = document.createElement('div');
    notification.className = `pusher-notification fixed bottom-4 right-4 ${types[type].bg} text-white px-4 py-3 rounded-md shadow-lg flex items-center`;
    notification.style.zIndex = '9999';
    notification.innerHTML = `
        <i class="fas ${types[type].icon} mr-2"></i>
        <span>${message}</span>
        <button class="ml-3 text-white hover:text-gray-200"
                onclick="this.parentElement.remove()">
            <i class="fas fa-times"></i>
        </button>
    `;

    document.body.appendChild(notification);
    setTimeout(() => notification.remove(), 5000);
}

// User status indicators
export function updateUserStatusIndicator(userId, isOnline) {
    document.querySelectorAll(`[data-user-id="${userId}"] .user-status`).forEach(el => {
        el.className = `user-status inline-block w-2 h-2 rounded-full mr-1 ${isOnline ? 'bg-green-500' : 'bg-gray-400'}`;
        el.title = isOnline ? 'Online' : 'Offline';
    });
}

// Editing indicators
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

export function removeEditingIndicator(userId) {
    const indicator = document.querySelector(`.editor-indicator[data-user-id="${userId}"]`);
    if (indicator) indicator.remove();
}

// Note content updates
export function updateNoteContent(note) {
    const contentElement = document.querySelector('.note-content');
    if (contentElement) {
        contentElement.innerHTML = note.content;
    }
}

export function refreshNotesList() {
    const notesContainer = document.querySelector('#notes-container');
    if (notesContainer) {
        fetch(window.location.href)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.text();
            })
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const newContent = doc.querySelector('#notes-container');

                if (newContent) {
                    notesContainer.innerHTML = newContent.innerHTML;
                }
            })
            .catch(error => {
                console.error('Failed to refresh notes list:', error);
                showNotification('Failed to refresh notes', 'error');
            });
    }
}

// Real-time features initialization
// export function initializeRealTimeFeatures(teamId, noteId = null) {
//     return new Promise((resolve, reject) => {
//         try {
//             // Initialize Pusher
//             const echo = initializePusher();

//             // connection state for debugging
//             echo.connector.pusher.connection.bind('state_change', (states) => {
//                 console.log('Pusher connection state changed:', states.current);
//             });

//             // Team channel events
//             const teamChannel = echo.private(`team.${teamId}`);

//             teamChannel
//                 .listen('.note.created', (data) => {
//                     console.log('New note created:', data.note.title);
//                     showNotification(`New note: ${data.note.title}`, 'success');
//                     if (shouldReloadNotesList()) {
//                         setTimeout(refreshNotesList, 300);
//                     }
//                 })
//                 .listen('.note.updated', (data) => {
//                     console.log('Note updated:', data.note.title);
//                     showNotification(`Note updated: ${data.note.title}`, 'info');
//                     if (isOnNotePage(data.note.id)) {
//                         updateNoteContent(data.note);
//                     } else if (shouldReloadNotesList()) {
//                         setTimeout(refreshNotesList, 300);
//                     }
//                 })
//                 .listen('.user.status.updated', (data) => {
//                     console.log('User status updated:', data.user.name, data.isOnline ? 'online' : 'offline');
//                     showNotification(
//                         `${data.user.name} is now ${data.isOnline ? 'online' : 'offline'}`,
//                         data.isOnline ? 'success' : 'warning'
//                     );
//                     updateUserStatusIndicator(data.user.id, data.isOnline);
//                 })
//                 .error((error) => {
//                     console.error('Team channel error:', error);
//                 });

//             // Note channel events (if noteId provided)
//             if (noteId) {
//                 const noteChannel = echo.private(`note.${noteId}`);

//                 noteChannel
//                     .listen('.user.editing', (data) => {
//                         if (data.user.id !== window.user?.id) {
//                             console.log(`${data.user.name} is editing note ${noteId}`);
//                             addEditingIndicator(data.user);
//                         }
//                     })
//                     .listen('.user.stopped.editing', (data) => {
//                         console.log(`${data.user.name} stopped editing note ${noteId}`);
//                         removeEditingIndicator(data.user.id);
//                     })
//                     .error((error) => {
//                         console.error('Note channel error:', error);
//                     });
//             }

//             // Track online status with retry logic
//             const updateOnlineStatus = async (isOnline) => {
//                 try {
//                     await axios.post('/api/user-online', {
//                         is_online: isOnline,
//                         team_id: teamId
//                     });
//                 } catch (error) {
//                     console.error('Failed to update online status:', error);
//                     // Retry after 5 seconds
//                     setTimeout(() => updateOnlineStatus(isOnline), 5000);
//                 }
//             };

//             // Only add event listeners if they haven't been added already
//             if (!window._onlineStatusListenersAdded) {
//                 window.addEventListener('load', () => {
//                     updateOnlineStatus(true);
//                 });

//                 window.addEventListener('beforeunload', () => {
//                     if (navigator.sendBeacon) {
//                         const data = new Blob(
//                             [JSON.stringify({ is_online: false, team_id: teamId })],
//                             { type: 'application/json' }
//                         );
//                         navigator.sendBeacon('/api/user-online', data);
//                     } else {
//                         // Use fetch with keepalive for better reliability
//                         fetch('/api/user-online', {
//                             method: 'POST',
//                             headers: {
//                                 'Content-Type': 'application/json',
//                             },
//                             body: JSON.stringify({ is_online: false, team_id: teamId }),
//                             keepalive: true
//                         }).catch(() => {});
//                     }
//                 });

//                 window._onlineStatusListenersAdded = true;
//             }

//             // Add disconnect handler
//             echo.connector.pusher.connection.bind('disconnected', () => {
//                 console.warn('Pusher disconnected - attempting to reconnect...');
//             });

//             console.log('Real-time features initialized successfully');
//             resolve(); // Resolve the promise when initialization is complete

//         } catch (error) {
//             console.error('Failed to initialize real-time features:', error);
//             reject(error);
//         }
//     });
// }





// Real-time features initialization
export function initializeRealTimeFeatures(teamId, noteId = null) {
    return new Promise((resolve, reject) => {
        try {
            const echo = initializePusher();

            // Team channel events with better error handling
            echo.private(`team.${teamId}`)
                .listen('.note.created', (data) => {
                    console.log('New note created:', data.note.title);
                    showNotification(`New note: ${data.note.title}`, 'success');
                    if (shouldReloadNotesList()) {
                        setTimeout(refreshNotesList, 300);
                    }
                })
                .listen('.note.updated', (data) => {
                    console.log('Note updated:', data.note.title);
                    showNotification(`Note updated: ${data.note.title}`, 'info');
                    if (isOnNotePage(data.note.id)) {
                        updateNoteContent(data.note);
                    } else if (shouldReloadNotesList()) {
                        setTimeout(refreshNotesList, 300);
                    }
                })
                .listen('.user.status.updated', (data) => {
                    console.log('User status updated:', data.user.name, data.isOnline ? 'online' : 'offline');
                    showNotification(
                        `${data.user.name} is now ${data.isOnline ? 'online' : 'offline'}`,
                        data.isOnline ? 'success' : 'warning'
                    );
                    updateUserStatusIndicator(data.user.id, data.isOnline);
                })
                .error((error) => {
                    console.error('Team channel subscription error:', error);
                    if (error.status === 403 || error.status === 404) {
                        showNotification('Authentication failed for real-time updates', 'error');
                    }
                });

            // Note channel events
            if (noteId) {
                echo.private(`note.${noteId}`)
                    .listen('.user.editing', (data) => {
                        if (data.user.id !== window.user?.id) {
                            console.log(`${data.user.name} is editing note ${noteId}`);
                            addEditingIndicator(data.user);
                        }
                    })
                    .listen('.user.stopped.editing', (data) => {
                        console.log(`${data.user.name} stopped editing note ${noteId}`);
                        removeEditingIndicator(data.user.id);
                    })
                    .error((error) => {
                        console.error('Note channel subscription error:', error);
                    });
            }

            console.log('Real-time features initialized successfully');
            resolve();

        } catch (error) {
            console.error('Failed to initialize real-time features:', error);
            reject(error);
        }
    });
}

// Helper functions
function shouldReloadNotesList() {
    return window.location.pathname.includes('notes') &&
           !window.location.pathname.includes('notes/');
}

function isOnNotePage(noteId) {
    return window.location.pathname.includes(`notes/${noteId}`);
}

// Global initialization system
(function() {
    // Store the real function
    const realInitializeRealTimeFeatures = initializeRealTimeFeatures;

    // Queue for initialization calls that come before the module is loaded
    window._realTimeInitQueue = window._realTimeInitQueue || [];

    // Global function that handles both immediate and queued calls
    window.initializeRealTimeFeatures = function(teamId, noteId = null) {
        if (typeof realInitializeRealTimeFeatures === 'function') {
            return realInitializeRealTimeFeatures(teamId, noteId);
        } else {
            // Queue the call for when the function is available
            return new Promise((resolve, reject) => {
                window._realTimeInitQueue.push({ teamId, noteId, resolve, reject });
            });
        }
    };

    // Process any queued calls
    window.processRealTimeQueue = function() {
        while (window._realTimeInitQueue.length > 0) {
            const { teamId, noteId, resolve, reject } = window._realTimeInitQueue.shift();
            realInitializeRealTimeFeatures(teamId, noteId).then(resolve).catch(reject);
        }
    };

    // Process any existing queue items
    if (window._realTimeInitQueue.length > 0) {
        window.processRealTimeQueue();
    }
})();

// Global exports
window.showNotification = showNotification;
window.updateUserStatusIndicator = updateUserStatusIndicator;
window.addEditingIndicator = addEditingIndicator;
window.removeEditingIndicator = removeEditingIndicator;


