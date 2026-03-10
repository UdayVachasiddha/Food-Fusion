USE foodfusion;

-- Update the Energy Guide to a real PDF
UPDATE resources 
SET file_path = 'https://www.energy.gov/sites/default/files/2017/10/f37/Energy_Saver_Guide-2017-en.pdf' 
WHERE title = 'Sustainable Kitchen Energy Guide';

-- Update the Spice Matrix to a real high-res Infographic
UPDATE resources 
SET file_path = 'https://upload.wikimedia.org/wikipedia/commons/thumb/1/15/Various_spices_and_herbs.jpg/1200px-Various_spices_and_herbs.jpg' 
WHERE title = 'The Ultimate Spice Flavor Matrix';