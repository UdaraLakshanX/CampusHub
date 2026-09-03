# CampusHub

A comprehensive PHP-based platform designed to connect and manage campus life, enabling students, faculty, and staff to collaborate, communicate, and stay informed about campus events and resources.

## 🎯 Features

- **User Authentication & Profiles** - Secure login and personalized user profiles
- **Event Management** - Create, view, and manage campus events
- **Community Forums** - Discussion boards for campus communities
- **Resource Sharing** - Share documents, notes, and academic resources
- **Notifications** - Real-time updates on events and activities
- **Campus Directory** - Find and connect with other campus members
- **Calendar Integration** - Keep track of important dates and events

## 🚀 Getting Started

### Prerequisites

- PHP 7.4 or higher
- MySQL or PostgreSQL
- Composer
- Git
- Apache/Nginx web server

### Installation

1. Clone the repository:
```bash
git clone https://github.com/UdaraLakshanX/CampusHub.git
cd CampusHub
```

2. Install PHP dependencies:
```bash
composer install
```

3. Set up environment variables:
```bash
cp .env.example .env
# Edit .env with your database and application configuration
```

4. Create and seed the database:
```bash
php artisan migrate --seed
```

5. Start the development server:
```bash
php artisan serve
```

Access the application at `http://localhost:8000`

## 📁 Project Structure

```
CampusHub/
├── app/
│   ├── Http/
│   │   ├── Controllers/     # Request handlers
│   │   └── Middleware/      # Request middleware
│   ├── Models/              # Database models
│   └── Services/            # Business logic
├── resources/
│   ├── views/               # Blade templates
│   └── css/                 # Stylesheets
├── routes/
│   └── web.php              # Web routes
├── public/
│   ├── css/                 # Compiled CSS
│   ├── js/                  # JavaScript files
│   └── images/              # Static images
├── database/
│   ├── migrations/          # Database migrations
│   └── seeders/             # Database seeders
├── composer.json            # PHP dependencies
└── README.md               # This file
```

## 🛠️ Technology Stack

- **Backend:** PHP 7.4+, Laravel Framework
- **Frontend:** HTML5, CSS3, Blade Templating
- **Database:** MySQL/PostgreSQL
- **Authentication:** Laravel Auth
- **Package Manager:** Composer

## 📖 Usage

### Creating an Account

1. Navigate to the registration page
2. Enter your email and create a secure password
3. Verify your email address
4. Complete your profile setup

### Creating Events

1. Log in to your account
2. Go to the Events section
3. Click "Create New Event"
4. Fill in event details (title, date, location, description)
5. Set privacy settings and invite attendees
6. Publish the event

### Joining Communities

1. Browse available communities from the dashboard
2. Click "Join" on communities of interest
3. Participate in discussions and share content

## 🤝 Contributing

We welcome contributions from the community! Please follow these steps:

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Make your changes and commit with clear messages (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

### Contribution Guidelines

- Follow PSR-12 coding standards
- Write clear, descriptive commit messages
- Add tests for new features
- Update documentation as needed
- Ensure your code passes all tests

## 🐛 Bug Reports

Found a bug? Please create an issue with:
- A clear description of the problem
- Steps to reproduce
- Expected vs. actual behavior
- Your PHP version and environment details

## 📝 License

This project is licensed under the MIT License - see the LICENSE file for details.

## 👥 Authors

- **Udara Lakshan** - Initial work - [UdaraLakshanX](https://github.com/UdaraLakshanX)

## 💬 Support

For support and questions, please:
- Create an issue on GitHub
- Check existing documentation in the wiki
- Review the FAQ section

## 🙏 Acknowledgments

- Thanks to all contributors
- Campus community for feedback and testing
- Laravel framework and PHP community

## 📞 Contact

For inquiries, reach out through:
- GitHub Issues
- Email: lakshanudara817@gmail.com

---

**Happy coding and connecting! 🎓**
