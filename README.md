# Travel Recommendation Project
This project is a recommendation system for travel destinations, developed as a part of a thesis. It splits into two paths:<br>
1. User Preference Path: In this path, users provide ratings (1-5) for different categories of locations based on their preferences. The system then calculates the user's preference for each location and suggests destinations accordingly. Additionally, users can leave reviews and ratings for destinations they have visited to improve the recommendation accuracy.<br>
2. Similar User Path: In this path, the system identifies users with similar preferences and travel histories. It then suggests travel plans and destinations based on the preferences and experiences of similar users.<br>
The project is built using Laravel Blade for the frontend, SQL for database management, and CSS for styling.

**Description**<br>
This project aims to provide personalized travel recommendations to users based on their preferences and interests. By utilizing cosine similarity, the system compares user profiles with destination attributes to suggest the most suitable travel destinations. The core functions include recommending travel destinations and tourist attractions based on user preferences and interests, suggesting travel plans by comparing similar user profiles to provide tailored recommendations, assisting users in creating travel itineraries with step-by-step guidance on how to reach various destinations (non-realtime navigation), and recommending travel destinations within a 5km radius of a chosen location. Additionally, the recommendation system can be improved through user reviews of tourist attractions, allowing users to rate and provide feedback on their experiences.

**Features**
**User Preference Path:**
- User Authentication: Users can sign up, log in, and log out.
- User Profiles: Users can update their profiles with preferences.
- Location Ratings: Users can provide ratings (1-5) for different categories of locations (e.g., beaches, mountains, cities) based on their preferences.
- Destination Recommendations: Based on user ratings and reviews, the system suggests travel destinations using cosine similarity or collaborative filtering algorithms.
- Destination Details: Users can view detailed information about recommended destinations, including attractions, accommodations, and local cuisine.
- User Reviews: Users can leave reviews and ratings for destinations they have visited to improve the recommendation accuracy.
- Responsive Design: The application is designed to be responsive and accessible across different devices.
  
**Similar User Path:**
- User Profiles: Users can update their profiles with preferences, travel history, and ratings.
- User Similarity Calculation: The system identifies users with similar preferences and travel histories using collaborative filtering or clustering techniques.
- Travel Plan Recommendations: Based on similar user profiles, the system suggests travel plans and destinations that are likely to be of interest to the current user.
- Social Features: Users can connect with and follow other users with similar interests to discover new travel destinations and plans.
  
**Admin Role:**
- Location Management: Admins can manage travel locations, including adding, editing, and deleting locations.
- Preference Management: Admins can manage user preferences, view and update user ratings, and analyze user behavior.
- User Management: Admins have access to user management functionalities, including user registration, profile management, and user activity monitoring.
- Review Management: Admins can view and moderate user reviews, including approving, editing, or deleting reviews.
- Dashboard: Admins have access to a dashboard to view system analytics, user statistics, and other relevant data.
  
**Map Functionality:**
- Interactive Map: The system includes an interactive map using Longdo Map, allowing users to visualize recommended destinations and explore nearby attractions.
- Geolocation: Users can view their current location on the map and discover nearby travel destinations (near 5km.).

**How to Run**
1. Install Dependencies: Run the following command in the root directory to install project dependencies: `composer install`
2. Copy Environment File: Copy the .env.example file and rename it to .env: `cp .env.example .env`
3. Update Environment Variables: Update the .env file with your database connection details:<br>
`DB_CONNECTION=mysql`<br>
`DB_HOST=127.0.0.1`<br>
`DB_PORT=3306`<br>
`DB_DATABASE=travelproject`<br>
`DB_USERNAME=root`<br>
`DB_PASSWORD=my_password`<br>
4. Generate Application Key: Run the following command to generate a new application key: `php artisan serve`
5. Run the Application: Start the Laravel development server by running: `php artisan serve`
6. Access the Application: Open your web browser and navigate to http://localhost:8000 to access the application.
