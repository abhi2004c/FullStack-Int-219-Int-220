-- Create Database
CREATE DATABASE eventhub;
USE eventhub;

-- Users Table
CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50),
    last_name VARCHAR(50),
    email VARCHAR(100) UNIQUE,
    password VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	otp VARCHAR(6),
	otp_expiry DATETIME
);
-- otp check krne ke liye
-- Categories Table
CREATE TABLE categories (
    category_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50),
    icon VARCHAR(50)
);

-- Events Table
CREATE TABLE events (
    event_id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255),
    description TEXT,
    date DATE,
    location VARCHAR(100),
    price DECIMAL(10, 2),
    category_id INT,
    image_url VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(category_id)
);

-- Bookings Table
CREATE TABLE bookings (
    booking_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    event_id INT,
    booking_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('pending', 'confirmed', 'cancelled') DEFAULT 'pending',
    FOREIGN KEY (user_id) REFERENCES users(user_id),
    FOREIGN KEY (event_id) REFERENCES events(event_id)
);

-- Admin
ALTER TABLE users ADD is_admin TINYINT(1) DEFAULT 0;


INSERT INTO categories (name, icon) VALUES
('Music', 'fas fa-music'),
('Technology', 'fas fa-laptop-code'),
('Food & Drink', 'fas fa-utensils'),
('Arts & Culture', 'fas fa-palette'),
('Sports', 'fas fa-running'),
('Education', 'fas fa-graduation-cap');

INSERT INTO events (category_id, title, description, date, location, price, image_url) VALUES
-- Concerts & Music Festivals (category_id = 1)
(1, 'Arijit Singh Live Tour 2025 – The Soul Sessions', 'Experience the magic of Arijit live!', '2025-02-10', 'Multiple Cities', 175.00, 'https://wallpaperaccess.com/full/1280976.jpg'),
(1, 'Lollapalooza India', 'India\'s biggest music festival!', '2025-04-05', 'Mumbai', 350.00, 'https://cdn.filestackcontent.com/NVo2OqaSt2K9V7qdij6v'),
(1, 'Indie Vibes Night', 'Unwind with soulful indie tunes!', '2025-05-12', 'Delhi', 120.00, 'https://theindianmusicdiaries.com/wp-content/smush-webp/2024/12/Supersonic.jpg.webp'),
-- Sports & Gaming (category_id = 2)
(2, 'IPL Fan Experience', 'Meet your favorite players!', '2025-03-02', 'Bangalore', 87.50, 'https://img.etimg.com/thumb/width-420,height-315,imgsize-3759166,resizemode-75,msid-119335906/news/sports/bcci-expands-ipl-fan-parks-to-50-cities-across-india/jiostar-ipl-2025-india-unites.jpg'),
(2, 'Valorant x BGMI LAN Showdown', 'Epic battle between two gaming giants!', '2025-04-18', 'Hyderabad', 30.00, 'https://staticg.sportskeeda.com/editor/2022/07/14ede-16583823880759-1920.jpg'),
(2, 'FIFA eWorld Cup Watch Party', 'Cheer for the champions!', '2025-05-08', 'Kolkata', 10.00, 'https://frontofficesports.com/wp-content/uploads/2022/10/FOS-PM-22-10.11-World-Cup-Fan-Festival.jpg'),
(2, 'Cricket Under the Stars', 'Friendly matches under the night sky!', '2025-06-22', 'Chennai', 0.00, 'https://easy-peasy.ai/cdn-cgi/image/quality=80,format=auto,width=700/https://media.easy-peasy.ai/568ad81e-3bd8-4746-96b5-7f2ffcc8d7d6/7fcad7fd-4722-4401-a879-52779a61584a.png'),
-- Pop Culture & Fandom (category_id = 3)
(3, 'Marvel Trivia & Cosplay Night', 'Assemble for a night of Marvel fun!', '2025-03-15', 'Mumbai', 15.00, 'https://api.triviacreator.com/v1/imgs/quiz/marvel-trivia-6f3cac00-10ce-4702-9892-f772a566ccfc.jpeg'),
(3, 'Taylor Swift Era Party', 'Celebrate all things Taylor!', '2025-04-25', 'Delhi', 20.00, 'https://cdn2.fatsoma.com/media/W1siZiIsInB1YmxpYy8yMDIzLzgvMTEvMTYvMjIvMjUvNDM0L1RTX0ZhdHNvbWEuanBnIl1d'),
(3, 'AnimeCon Mini', 'A mini celebration of anime culture!', '2025-05-18', 'Bangalore', 10.00, 'https://animematsuri.com/wp-content/uploads/2024/09/AM24Cosplay_Quy_190-scaled.jpg'),
-- Lifestyle & Experience (category_id = 4)
(4, 'Sunset Rooftop Yoga + Acoustic Jams', 'Find your zen with a view!', '2025-03-08', 'Mumbai', 20.00, 'https://img.freepik.com/premium-photo/rooftop-yoga-class-sunset-city-group-diverse-people-are-practicing-yoga-rooftop-overlooking-city_14117-495192.jpg'),
(4, 'Silent Disco Party', 'Dance to your own beat!', '2025-04-12', 'Delhi', 25.00, 'https://miro.medium.com/v2/resize:fit:2000/1*l2qqCC4r6v3n_X-Y4JgxCw.jpeg'),
(4, 'Open Mic & Chai Night', 'Share your voice and enjoy chai!', '2025-05-05', 'Bangalore', 10.00, 'https://res.cloudinary.com/dhz6meh4y/image/upload/q_10/v1566471317/613A3152.jpg');




