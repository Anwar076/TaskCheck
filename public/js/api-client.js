/**
 * API Client for Template and Task List Management
 * Handles all API communication with proper error handling and CSRF tokens
 */

class ApiClient {
    constructor() {
        this.baseUrl = '/api';
        this.csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        this.headers = {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        };

        if (this.csrfToken) {
            this.headers['X-CSRF-TOKEN'] = this.csrfToken;
        }
    }

    async request(endpoint, options = {}) {
        const url = `${this.baseUrl}${endpoint}`;
        const config = {
            headers: this.headers,
            // Ensure cookies (session / Sanctum) are sent with requests so auth:sanctum works
            credentials: 'same-origin',
            ...options
        };

        try {
            const response = await fetch(url, config);
            const data = await response.json();

            if (!response.ok) {
                throw new Error(data.message || `HTTP error! status: ${response.status}`);
            }

            return data;
        } catch (error) {
            console.error('API Request failed:', error);
            throw error;
        }
    }

    // Template API methods
    async getTemplates() {
        return this.request('/templates');
    }

    async createTemplate(templateData) {
        return this.request('/templates', {
            method: 'POST',
            body: JSON.stringify(templateData)
        });
    }

    async getTemplate(id) {
        return this.request(`/templates/${id}`);
    }

    async updateTemplate(id, templateData) {
        return this.request(`/templates/${id}`, {
            method: 'PUT',
            body: JSON.stringify(templateData)
        });
    }

    async deleteTemplate(id) {
        return this.request(`/templates/${id}`, {
            method: 'DELETE'
        });
    }

    // Task List API methods
    async getTaskLists(params = {}) {
        const queryString = new URLSearchParams(params).toString();
        const endpoint = queryString ? `/lists?${queryString}` : '/lists';
        return this.request(endpoint);
    }

    async createTaskList(listData) {
        return this.request('/lists', {
            method: 'POST',
            body: JSON.stringify(listData)
        });
    }

    async getTaskList(id) {
        return this.request(`/lists/${id}`);
    }

    async getAvailableTemplates() {
        return this.request('/lists/templates/available');
    }
}

// User API methods
window.UserAPI = {
    async loadUsers(params = {}) {
        try {
            const response = await apiClient.request('/users?' + new URLSearchParams(params));
            return response.data;
        } catch (error) {
            console.error('Failed to load users:', error);
            showNotification('Failed to load users: ' + error.message, 'error');
            return [];
        }
    },

    async createUser(formData) {
        try {
            const response = await apiClient.request('/users', {
                method: 'POST',
                body: JSON.stringify(formData)
            });
            showNotification('User created successfully!', 'success');
            return response.data;
        } catch (error) {
            console.error('Failed to create user:', error);
            showNotification('Failed to create user: ' + error.message, 'error');
            throw error;
        }
    },

    async updateUser(id, formData) {
        try {
            const response = await apiClient.request(`/users/${id}`, {
                method: 'PUT',
                body: JSON.stringify(formData)
            });
            showNotification('User updated successfully!', 'success');
            return response.data;
        } catch (error) {
            console.error('Failed to update user:', error);
            showNotification('Failed to update user: ' + error.message, 'error');
            throw error;
        }
    },

    async deleteUser(id) {
        try {
            await apiClient.request(`/users/${id}`, { method: 'DELETE' });
            showNotification('User deleted successfully!', 'success');
            return true;
        } catch (error) {
            console.error('Failed to delete user:', error);
            showNotification('Failed to delete user: ' + error.message, 'error');
            throw error;
        }
    },

    async getUserStats() {
        try {
            const response = await apiClient.request('/users/statistics');
            return response.data;
        } catch (error) {
            console.error('Failed to load user statistics:', error);
            showNotification('Failed to load user statistics: ' + error.message, 'error');
            return {};
        }
    }
};

// Task API methods
window.TaskAPI = {
    async loadTasks(listId, params = {}) {
        try {
            const response = await apiClient.request(`/lists/${listId}/tasks?` + new URLSearchParams(params));
            return response.data;
        } catch (error) {
            console.error('Failed to load tasks:', error);
            showNotification('Failed to load tasks: ' + error.message, 'error');
            return [];
        }
    },

    async createTask(listId, formData) {
        try {
            const response = await apiClient.request(`/lists/${listId}/tasks`, {
                method: 'POST',
                body: JSON.stringify(formData)
            });
            showNotification('Task created successfully!', 'success');
            return response.data;
        } catch (error) {
            console.error('Failed to create task:', error);
            showNotification('Failed to create task: ' + error.message, 'error');
            throw error;
        }
    },

    async updateTask(listId, taskId, formData) {
        try {
            const response = await apiClient.request(`/lists/${listId}/tasks/${taskId}`, {
                method: 'PUT',
                body: JSON.stringify(formData)
            });
            showNotification('Task updated successfully!', 'success');
            return response.data;
        } catch (error) {
            console.error('Failed to update task:', error);
            showNotification('Failed to update task: ' + error.message, 'error');
            throw error;
        }
    },

    async deleteTask(listId, taskId) {
        try {
            await apiClient.request(`/lists/${listId}/tasks/${taskId}`, { method: 'DELETE' });
            showNotification('Task deleted successfully!', 'success');
            return true;
        } catch (error) {
            console.error('Failed to delete task:', error);
            showNotification('Failed to delete task: ' + error.message, 'error');
            throw error;
        }
    },

    async reorderTasks(listId, tasks) {
        try {
            await apiClient.request(`/lists/${listId}/tasks/reorder`, {
                method: 'POST',
                body: JSON.stringify({ tasks })
            });
            return true;
        } catch (error) {
            console.error('Failed to reorder tasks:', error);
            showNotification('Failed to reorder tasks: ' + error.message, 'error');
            throw error;
        }
    }
};

// Submission API methods
window.SubmissionAPI = {
    async loadSubmissions(params = '') {
        try {
            const response = await apiClient.request('/submissions?' + params);
            return response.data;
        } catch (error) {
            console.error('Failed to load submissions:', error);
            showNotification('Failed to load submissions: ' + error.message, 'error');
            return { data: [], total: 0 };
        }
    },

    async getSubmission(id) {
        try {
            const response = await apiClient.request(`/submissions/${id}`);
            return response.data;
        } catch (error) {
            console.error('Failed to load submission:', error);
            showNotification('Failed to load submission: ' + error.message, 'error');
            return null;
        }
    },

    async updateSubmission(id, formData) {
        try {
            const response = await apiClient.request(`/submissions/${id}`, {
                method: 'PUT',
                body: JSON.stringify(formData)
            });
            showNotification('Submission updated successfully!', 'success');
            return response.data;
        } catch (error) {
            console.error('Failed to update submission:', error);
            showNotification('Failed to update submission: ' + error.message, 'error');
            throw error;
        }
    },

    async completeSubmission(id) {
        try {
            const response = await apiClient.request(`/submissions/${id}/complete`, {
                method: 'POST'
            });
            showNotification('Submission completed successfully!', 'success');
            return response.data;
        } catch (error) {
            console.error('Failed to complete submission:', error);
            showNotification('Failed to complete submission: ' + error.message, 'error');
            throw error;
        }
    },

    async completeTask(submissionId, taskId) {
        try {
            const response = await apiClient.request(`/submissions/${submissionId}/tasks/${taskId}`, {
                method: 'POST'
            });
            showNotification('Task completed successfully!', 'success');
            return response.data;
        } catch (error) {
            console.error('Failed to complete task:', error);
            showNotification('Failed to complete task: ' + error.message, 'error');
            throw error;
        }
    }
};

// Dashboard API methods
window.DashboardAPI = {
    async getAdminStats() {
        try {
            const response = await apiClient.request('/dashboard/admin/stats');
            return response.data;
        } catch (error) {
            console.error('Failed to load admin stats:', error);
            showNotification('Failed to load dashboard stats: ' + error.message, 'error');
            return {};
        }
    },

    async getEmployeeData() {
        try {
            const response = await apiClient.request('/dashboard/employee/data');
            return response.data;
        } catch (error) {
            console.error('Failed to load employee data:', error);
            showNotification('Failed to load employee data: ' + error.message, 'error');
            return {};
        }
    },

    async getWeeklyOverview(params = {}) {
        try {
            const response = await apiClient.request('/dashboard/weekly-overview?' + new URLSearchParams(params));
            return response.data;
        } catch (error) {
            console.error('Failed to load weekly overview:', error);
            showNotification('Failed to load weekly overview: ' + error.message, 'error');
            return {};
        }
    },

    async getRecentActivity() {
        try {
            const response = await apiClient.request('/dashboard/recent-activity');
            return response.data;
        } catch (error) {
            console.error('Failed to load recent activity:', error);
            showNotification('Failed to load recent activity: ' + error.message, 'error');
            return [];
        }
    }
};

// Create global instance
window.apiClient = new ApiClient();

// Helper functions for common operations
window.TemplateAPI = {
    async loadTemplates() {
        try {
            const response = await apiClient.getTemplates();
            return response.data;
        } catch (error) {
            console.error('Failed to load templates:', error);
            showNotification('Failed to load templates: ' + error.message, 'error');
            return [];
        }
    },

    async createTemplate(formData) {
        try {
            const response = await apiClient.createTemplate(formData);
            showNotification('Template created successfully!', 'success');
            return response.data;
        } catch (error) {
            console.error('Failed to create template:', error);
            showNotification('Failed to create template: ' + error.message, 'error');
            throw error;
        }
    },

    async updateTemplate(id, formData) {
        try {
            const response = await apiClient.updateTemplate(id, formData);
            showNotification('Template updated successfully!', 'success');
            return response.data;
        } catch (error) {
            console.error('Failed to update template:', error);
            showNotification('Failed to update template: ' + error.message, 'error');
            throw error;
        }
    },

    async deleteTemplate(id) {
        try {
            await apiClient.deleteTemplate(id);
            showNotification('Template deleted successfully!', 'success');
            return true;
        } catch (error) {
            console.error('Failed to delete template:', error);
            showNotification('Failed to delete template: ' + error.message, 'error');
            throw error;
        }
    }
};

window.ListAPI = {
    async loadLists(params = {}) {
        try {
            const response = await apiClient.getTaskLists(params);
            return response.data;
        } catch (error) {
            console.error('Failed to load lists:', error);
            showNotification('Failed to load lists: ' + error.message, 'error');
            return [];
        }
    },

    async createList(formData) {
        try {
            const response = await apiClient.createTaskList(formData);
            showNotification(response.message, 'success');
            return response.data;
        } catch (error) {
            console.error('Failed to create list:', error);
            showNotification('Failed to create list: ' + error.message, 'error');
            throw error;
        }
    },

    async getAvailableTemplates() {
        try {
            const response = await apiClient.getAvailableTemplates();
            return response.data;
        } catch (error) {
            console.error('Failed to load available templates:', error);
            showNotification('Failed to load templates: ' + error.message, 'error');
            return [];
        }
    }
};

// Notification helper
function showNotification(message, type = 'info') {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg max-w-sm transition-all duration-300 ${
        type === 'success' ? 'bg-green-500 text-white' :
        type === 'error' ? 'bg-red-500 text-white' :
        type === 'warning' ? 'bg-yellow-500 text-black' :
        'bg-blue-500 text-white'
    }`;
    
    notification.innerHTML = `
        <div class="flex items-center">
            <div class="flex-shrink-0">
                ${type === 'success' ? '✓' : type === 'error' ? '✗' : type === 'warning' ? '⚠' : 'ℹ'}
            </div>
            <div class="ml-3">
                <p class="text-sm font-medium">${message}</p>
            </div>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        notification.style.opacity = '0';
        notification.style.transform = 'translateX(100%)';
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 300);
    }, 5000);
}

// Export for use in modules
if (typeof module !== 'undefined' && module.exports) {
    module.exports = { ApiClient, TemplateAPI, ListAPI };
}
