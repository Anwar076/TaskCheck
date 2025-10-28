<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <title>API Documentatie - {{ config('app.name', 'TaskCheck') }}</title>
    @include('components.head')
</head>
<body class="bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100 min-h-screen font-sans text-gray-900">

    @include('components.header')
                    colors: {
                        primary: {
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        .code-block {
            background-color: #1f2937;
            color: #f9fafb;
            font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
            border-radius: 0.5rem;
            padding: 1rem;
            overflow-x: auto;
        }
        .method-get { color: #10b981; }
        .method-post { color: #3b82f6; }
        .method-put { color: #f59e0b; }
        .method-delete { color: #ef4444; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen font-sans">
    <!-- Navbar -->
    <nav class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-6 flex justify-between items-center h-16">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                    </svg>
                </div>
                <span class="text-xl font-bold">TaskCheck API</span>
            </div>
            <a href="{{ route('welcome') }}" class="text-gray-600 hover:text-blue-600">← Back to TaskCheck</a>
        </div>
    </nav>

    <div class="max-w-6xl mx-auto px-6 py-12">
        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">API Documentation</h1>
            <p class="text-lg text-gray-600">Build powerful integrations with TaskCheck's REST API</p>
        </div>

        <!-- Quick Start -->
        <div class="bg-white rounded-lg shadow-sm border p-8 mb-8">
            <h2 class="text-2xl font-semibold text-gray-900 mb-6">Quick Start</h2>
            <div class="space-y-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Base URL</h3>
                    <div class="code-block">
                        https://api.taskcheck.com/v1
                    </div>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Authentication</h3>
                    <p class="text-gray-600 mb-3">All API requests require authentication using an API key in the header:</p>
                    <div class="code-block">
                        Authorization: Bearer YOUR_API_KEY
                    </div>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Example Request</h3>
                    <div class="code-block">
curl -X GET "https://api.taskcheck.com/v1/tasks" \
  -H "Authorization: Bearer YOUR_API_KEY" \
  -H "Content-Type: application/json"
                    </div>
                </div>
            </div>
        </div>

        <!-- API Endpoints -->
        <div class="space-y-8">
            <!-- Tasks -->
            <div class="bg-white rounded-lg shadow-sm border p-8">
                <h2 class="text-2xl font-semibold text-gray-900 mb-6">Tasks</h2>
                
                <!-- Get Tasks -->
                <div class="mb-8">
                    <div class="flex items-center mb-3">
                        <span class="method-get font-semibold mr-3">GET</span>
                        <code class="bg-gray-100 px-3 py-1 rounded">/tasks</code>
                    </div>
                    <p class="text-gray-600 mb-4">Retrieve a list of tasks</p>
                    <div class="code-block mb-4">
{
  "data": [
    {
      "id": 1,
      "title": "Complete project proposal",
      "description": "Draft and review the Q1 project proposal",
      "status": "in_progress",
      "priority": "high",
      "due_date": "2024-03-15",
      "created_at": "2024-03-01T10:00:00Z",
      "updated_at": "2024-03-05T14:30:00Z"
    }
  ],
  "meta": {
    "total": 1,
    "per_page": 15,
    "current_page": 1
  }
}
                    </div>
                </div>

                <!-- Create Task -->
                <div class="mb-8">
                    <div class="flex items-center mb-3">
                        <span class="method-post font-semibold mr-3">POST</span>
                        <code class="bg-gray-100 px-3 py-1 rounded">/tasks</code>
                    </div>
                    <p class="text-gray-600 mb-4">Create a new task</p>
                    <div class="code-block mb-4">
{
  "title": "New task title",
  "description": "Task description",
  "priority": "medium",
  "due_date": "2024-03-20",
  "assignee_id": 123
}
                    </div>
                </div>

                <!-- Update Task -->
                <div class="mb-8">
                    <div class="flex items-center mb-3">
                        <span class="method-put font-semibold mr-3">PUT</span>
                        <code class="bg-gray-100 px-3 py-1 rounded">/tasks/{id}</code>
                    </div>
                    <p class="text-gray-600 mb-4">Update an existing task</p>
                    <div class="code-block mb-4">
{
  "title": "Updated task title",
  "status": "completed",
  "completed_at": "2024-03-10T16:00:00Z"
}
                    </div>
                </div>

                <!-- Delete Task -->
                <div>
                    <div class="flex items-center mb-3">
                        <span class="method-delete font-semibold mr-3">DELETE</span>
                        <code class="bg-gray-100 px-3 py-1 rounded">/tasks/{id}</code>
                    </div>
                    <p class="text-gray-600">Delete a task</p>
                </div>
            </div>

            <!-- Users -->
            <div class="bg-white rounded-lg shadow-sm border p-8">
                <h2 class="text-2xl font-semibold text-gray-900 mb-6">Users</h2>
                
                <!-- Get Users -->
                <div class="mb-8">
                    <div class="flex items-center mb-3">
                        <span class="method-get font-semibold mr-3">GET</span>
                        <code class="bg-gray-100 px-3 py-1 rounded">/users</code>
                    </div>
                    <p class="text-gray-600 mb-4">Retrieve a list of users</p>
                    <div class="code-block mb-4">
{
  "data": [
    {
      "id": 123,
      "name": "John Doe",
      "email": "john@example.com",
      "role": "employee",
      "created_at": "2024-01-15T09:00:00Z"
    }
  ]
}
                    </div>
                </div>

                <!-- Get User -->
                <div>
                    <div class="flex items-center mb-3">
                        <span class="method-get font-semibold mr-3">GET</span>
                        <code class="bg-gray-100 px-3 py-1 rounded">/users/{id}</code>
                    </div>
                    <p class="text-gray-600">Retrieve a specific user</p>
                </div>
            </div>

            <!-- Teams -->
            <div class="bg-white rounded-lg shadow-sm border p-8">
                <h2 class="text-2xl font-semibold text-gray-900 mb-6">Teams</h2>
                
                <!-- Get Teams -->
                <div class="mb-8">
                    <div class="flex items-center mb-3">
                        <span class="method-get font-semibold mr-3">GET</span>
                        <code class="bg-gray-100 px-3 py-1 rounded">/teams</code>
                    </div>
                    <p class="text-gray-600 mb-4">Retrieve a list of teams</p>
                    <div class="code-block mb-4">
{
  "data": [
    {
      "id": 1,
      "name": "Development Team",
      "description": "Software development team",
      "member_count": 8,
      "created_at": "2024-01-01T00:00:00Z"
    }
  ]
}
                    </div>
                </div>
            </div>
        </div>

        <!-- Error Handling -->
        <div class="bg-white rounded-lg shadow-sm border p-8 mb-8">
            <h2 class="text-2xl font-semibold text-gray-900 mb-6">Error Handling</h2>
            <p class="text-gray-600 mb-4">The API uses standard HTTP status codes and returns error details in JSON format:</p>
            <div class="code-block">
{
  "error": {
    "code": "VALIDATION_ERROR",
    "message": "The given data was invalid.",
    "details": {
      "title": ["The title field is required."],
      "due_date": ["The due date must be a date after today."]
    }
  }
}
            </div>
        </div>

        <!-- Rate Limiting -->
        <div class="bg-white rounded-lg shadow-sm border p-8 mb-8">
            <h2 class="text-2xl font-semibold text-gray-900 mb-6">Rate Limiting</h2>
            <div class="space-y-4">
                <p class="text-gray-600">API requests are rate limited to ensure fair usage:</p>
                <ul class="list-disc list-inside text-gray-600 space-y-2">
                    <li><strong>Free Plan:</strong> 100 requests per hour</li>
                    <li><strong>Professional Plan:</strong> 1,000 requests per hour</li>
                    <li><strong>Enterprise Plan:</strong> 10,000 requests per hour</li>
                </ul>
                <p class="text-gray-600">Rate limit information is included in response headers:</p>
                <div class="code-block">
X-RateLimit-Limit: 1000
X-RateLimit-Remaining: 999
X-RateLimit-Reset: 1640995200
                </div>
            </div>
        </div>

        <!-- SDKs -->
        <div class="bg-white rounded-lg shadow-sm border p-8 mb-8">
            <h2 class="text-2xl font-semibold text-gray-900 mb-6">SDKs & Libraries</h2>
            <div class="grid md:grid-cols-2 gap-6">
                <div class="border rounded-lg p-4">
                    <h3 class="font-semibold text-gray-900 mb-2">JavaScript/Node.js</h3>
                    <div class="code-block text-sm">
npm install taskcheck-api
                    </div>
                </div>
                <div class="border rounded-lg p-4">
                    <h3 class="font-semibold text-gray-900 mb-2">Python</h3>
                    <div class="code-block text-sm">
pip install taskcheck-api
                    </div>
                </div>
                <div class="border rounded-lg p-4">
                    <h3 class="font-semibold text-gray-900 mb-2">PHP</h3>
                    <div class="code-block text-sm">
composer require taskcheck/api
                    </div>
                </div>
                <div class="border rounded-lg p-4">
                    <h3 class="font-semibold text-gray-900 mb-2">Ruby</h3>
                    <div class="code-block text-sm">
gem install taskcheck-api
                    </div>
                </div>
            </div>
        </div>

        <!-- Support -->
        <div class="bg-blue-50 rounded-lg border border-blue-200 p-8 text-center">
            <h2 class="text-2xl font-semibold text-gray-900 mb-4">Need Help?</h2>
            <p class="text-gray-600 mb-6">Our developer support team is here to help you integrate with our API.</p>
            <div class="space-y-4">
                <a href="{{ route('contact') }}" class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                    Contact Support
                </a>
                <p class="text-sm text-gray-600">Average response time: 2 hours</p>
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center mt-12 text-gray-600">
            <p>API Version: v1.0 | Last updated: {{ date('F j, Y') }}</p>
            <p class="mt-2">View our <a href="{{ route('status') }}" class="text-blue-600 hover:underline">API status page</a> for real-time monitoring</p>
        </div>
    </div>
</body>
</html>
