# Smart FAQ Chatbot

A web-based chatbot system for university students that provides instant answers to common questions and maintains an admin panel for FAQ management.

## Features

### User Side
- Interactive chat interface
- Instant responses based on FAQ database
- Auto-scrolling messages with timestamps
- Fallback to AI-powered responses when needed

### Admin Side
- Secure login system
- Dynamic FAQ management (Add/Edit/Delete)
- Chat log viewer
- Analytics dashboard

## Technology Stack
- Frontend: HTML, CSS, JavaScript
- Backend: PHP
- Database: MySQL
- Optional AI Integration: OpenAI GPT API

## Setup Instructions

1. Clone the repository
2. Import the database schema from `database/schema.sql`
3. Configure database connection in `config/database.php`
4. Set up your OpenAI API key in `config/config.php` (optional)
5. Start your local server and navigate to the project directory

## Directory Structure
```
├── assets/
│   ├── css/
│   ├── js/
│   └── images/
├── config/
├── database/
├── includes/
├── admin/
└── index.php
```

## Security
- All admin routes are protected
- SQL injection prevention
- XSS protection
- CSRF protection

## License
MIT License 