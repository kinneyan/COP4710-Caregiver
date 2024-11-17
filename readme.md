### Contributing
Work on a separate branch (based off dev). When finished with changes, open a PR from your branch into dev. 

Once significant work is completed or when needed, we can open a PR from dev into main. 

Upon push to main, the server will automatically pull the most recent changes and update the website. 

### File structure
In order for the application to work as intended, the file structure for the website should follow the model below. 

HTML files should exist simply within the root directory. CSS, images, Javascript, and PHP API files should exist within their respective subdirectories. 
```
COP4710-Caregiver
├── index.html
├── home.html
├── whatever.html
├── css
│   ├── styles.css
│   └── styles2.css
├── images
│   ├── image.png
│   ├── image2.jpg
│   └── image3.png
├── js
│   ├── script1.js
│   └── script2.js
└── api
    ├── script1.php
    └── script2.php
```