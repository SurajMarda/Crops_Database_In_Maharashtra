// Import required packages
const express = require('express'); // For creating the server
const xlsx = require('xlsx'); // For reading Excel files
const cors = require('cors'); // For enabling CORS

const app = express();
const port = 3000;

// Use CORS to allow requests from your frontend
app.use(cors());

// Read the XLSX file
const workbook = xlsx.readFile('Crops.xlsx'); // Replace with your XLSX file path
const sheetName = workbook.SheetNames[0]; // Get the first sheet
const data = xlsx.utils.sheet_to_json(workbook.Sheets[sheetName]); // Convert to JSON

// Create a route to get crops data based on district
app.get('/crops/:district', (req, res) => {
    const district = req.params.district;
    const cropsData = data.filter(row => row.District.toLowerCase() === district.toLowerCase()); // Adjust based on your column names

    if (cropsData.length > 0) {
        res.json(cropsData);
    } else {
        res.status(404).json({ message: 'District not found' });
    }
});

// Start the server
app.listen(port, () => {
    console.log(`Server running at http://localhost:${port}`);
});
