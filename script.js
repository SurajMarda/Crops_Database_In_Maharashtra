// JavaScript to load content from external HTML files
function loadContent(page) {
    const contentDiv = document.getElementById('content');
    
    // Fetch the HTML content from the external file
    fetch(page)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.text(); // Return the response as text
        })
        .then(data => {
            contentDiv.innerHTML = data; // Inject the fetched content into the contentDiv
        })
        .catch(error => {
            contentDiv.innerHTML = `<p>Sorry, an error occurred while loading the content.</p>`;
            console.error('There was an error fetching the content:', error);
        });
}
