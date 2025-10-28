// Test script to verify the API endpoint for task lists
(async function testApi() {
    const endpoint = '/api/lists';
    try {
        const response = await fetch(endpoint, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            credentials: 'same-origin'
        });

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const data = await response.json();
        console.log('API Response:', data);
    } catch (error) {
        console.error('Failed to fetch API:', error);
    }
})();