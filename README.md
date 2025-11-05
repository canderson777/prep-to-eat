# PrepToEat - AI-Powered Recipe Management System

<p align="center">
  <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo">
</p>

<p align="center">
  <strong>Transform any recipe URL into a personalized cooking experience</strong>
</p>

## 🍳 About PrepToEat

PrepToEat is an intelligent recipe management system built with Laravel that leverages OpenAI's GPT models to extract, organize, and enhance recipes from any URL. Whether you're a home cook looking to organize your favorite recipes or a food enthusiast wanting to create meal plans, PrepToEat makes recipe management effortless and enjoyable.

## ✨ Key Features

### 🤖 AI-Powered Recipe Extraction
- **Smart URL Processing**: Paste any recipe URL and let AI extract all the essential information
- **Intelligent Parsing**: Automatically identifies title, ingredients, instructions, and cooking tips
- **OpenAI Integration**: Powered by GPT-3.5-turbo for accurate and reliable recipe extraction

### 📚 Recipe Management
- **Save & Organize**: Store your favorite recipes with custom categories
- **Duplicate Protection**: Prevents accidental duplicate saves with smart validation
- **Recipe Categories**: Organize by Breakfast, Lunch, Dinner, Dessert, Snacks, and more
- **Edit & Delete**: Full CRUD operations for your saved recipes

### 🔗 Recipe Sharing
- **Secure Sharing**: Generate shareable links for your recipes
- **Token-Based Access**: Secure sharing with expiration dates
- **Public Recipe Access**: Share your culinary creations with friends and family

### 📅 Meal Planning Calendar
- **Visual Calendar**: Plan your meals with an intuitive calendar interface
- **Recipe Integration**: Link your saved recipes to specific dates and meal types
- **Flexible Scheduling**: Plan breakfast, lunch, dinner, and snacks
- **Notes & Customization**: Add personal notes to your meal plans

### 💬 AI Recipe Assistant
- **Interactive Chat**: Ask questions about your recipes and get AI-powered answers
- **Cooking Tips**: Get suggestions for substitutions, cooking methods, and variations
- **Contextual Help**: AI understands your saved recipes and provides relevant advice

### 👤 User Authentication & Profiles
- **Secure Login**: Laravel Breeze authentication system
- **User Profiles**: Manage your account and preferences
- **Guest Mode**: Try the app without registration using local storage

### 📱 Mobile Responsive Design
- **Cross-Device Compatibility**: Optimized for desktop, tablet, and mobile
- **Touch-Friendly Interface**: Easy navigation on all screen sizes
- **Progressive Web App Ready**: Fast loading and offline capabilities

## 🚀 Getting Started

### Prerequisites
- PHP 8.1 or higher
- Composer
- Node.js & NPM
- MySQL/SQLite database
- OpenAI API Key

### Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/your-username/preptoeat.git
   cd preptoeat
   ```

2. **Install dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Configure your .env file**
   ```env
   DB_CONNECTION=sqlite
   DB_DATABASE=database/database.sqlite
   OPENAI_API_KEY=your_openai_api_key_here
   ```

5. **Run migrations**
   ```bash
   php artisan migrate
   ```

6. **Build assets**
   ```bash
   npm run dev
   ```

7. **Start the server**
   ```bash
   php artisan serve
   ```

8. **Access the application**
   Open your browser and navigate to `http://localhost:8000`

## 🗄️ Database Schema

### Core Tables
- **users**: User authentication and profile information
- **saved_recipes**: User's saved recipes with categories and metadata
- **meal_plan_entries**: Calendar-based meal planning system
- **recipe_shares**: Secure recipe sharing with token-based access

### Key Relationships
- Users can have multiple saved recipes
- Recipes can be linked to multiple meal plan entries
- Recipes can be shared with expiration dates
- Full cascade deletion for data integrity

## 🛠️ Technology Stack

- **Backend**: Laravel 12.x (PHP 8.3)
- **Frontend**: Blade templates with Tailwind CSS
- **JavaScript**: Vanilla JS with Alpine.js components
- **Database**: SQLite (development) / MySQL (production)
- **AI Integration**: OpenAI GPT-3.5-turbo API
- **Authentication**: Laravel Breeze
- **Build Tools**: Vite for asset compilation

## 📋 API Endpoints

### Recipe Management
- `POST /recipes/save` - Save a new recipe
- `GET /my-recipes` - View saved recipes
- `PUT /recipes/{id}` - Update a recipe
- `DELETE /recipes/{id}` - Delete a recipe

### Recipe Sharing
- `POST /recipes/{recipe}/share` - Create a shareable link
- `DELETE /shares/{share}` - Revoke a shared recipe
- `GET /shares/{token}` - Access a shared recipe

### Meal Planning
- `GET /meal-plan` - View meal planning calendar
- `POST /meal-plan` - Add meal to calendar
- `PUT /meal-plan/{id}` - Update meal plan entry
- `DELETE /meal-plan/{id}` - Remove from meal plan

### AI Features
- `POST /recipe` - Extract recipe from URL
- `POST /chat/ask` - Ask AI assistant questions

## 🔧 Configuration

### OpenAI API Setup
1. Get your API key from [OpenAI Platform](https://platform.openai.com)
2. Add it to your `.env` file: `OPENAI_API_KEY=your_key_here`
3. The app uses GPT-3.5-turbo for recipe extraction and chat assistance

### Database Configuration
- **Development**: Uses SQLite (`database/database.sqlite`)
- **Production**: Configure MySQL in `.env` file
- **Migrations**: Run `php artisan migrate` to set up tables

## 🧪 Testing

```bash
# Run PHP tests
php artisan test

# Run with coverage
php artisan test --coverage
```

## 📱 Mobile Testing

### Browser Dev Tools
1. Open Chrome DevTools (F12)
2. Click device toggle icon (📱)
3. Select device presets or custom dimensions

### Real Device Testing
1. Find your computer's IP: `ipconfig`
2. Start server with network access: `php artisan serve --host=0.0.0.0`
3. Access from phone: `http://YOUR_IP:8000`

## 🤝 Contributing

We welcome contributions! Please feel free to submit issues and pull requests.

### Development Workflow
1. Fork the repository
2. Create a feature branch: `git checkout -b feature/amazing-feature`
3. Commit your changes: `git commit -m 'Add amazing feature'`
4. Push to the branch: `git push origin feature/amazing-feature`
5. Open a Pull Request

## 📄 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).



## 🔄 Recent Updates & Changes

### Latest Features Added (v2.0)
- ✅ **Meal Planning Calendar**: Visual calendar interface for meal planning
- ✅ **Recipe Sharing System**: Secure token-based recipe sharing with expiration dates
- ✅ **AI Chat Assistant**: Interactive AI helper for cooking questions and tips
- ✅ **Enhanced UI/UX**: Modern responsive design with improved mobile experience
- ✅ **Local Storage Integration**: Guest mode with local recipe storage
- ✅ **Recipe Import System**: Import recipes from guest/local storage to user account
- ✅ **Database Migrations**: New tables for meal planning and recipe sharing

### Development Commands

```bash
# Save your work
git add .
git commit -m "Your commit message here"
git push

# Start development server
php artisan serve

# For mobile testing (network access)
php artisan serve --host=0.0.0.0

# Run migrations
php artisan migrate

# Build frontend assets
npm run dev
```

## 🧪 Test Recipe URLs

Here are some example recipe URLs you can test with the AI extraction feature:

- [Grilled Spring Salad](https://drhyman.com/blogs/content/grilled-spring-salad)
- [Chicken Tikka Masala](https://www.recipetineats.com/chicken-tikka-masala/#recipe)
- [Crispy Buffalo Wings](https://www.recipetineats.com/truly-crispy-oven-baked-buffalo-wings-my-wings-cookbook/)
- [Quick Chicken Breast with Romesco](https://drhyman.com/blogs/content/quick-chicken-breast-with-spanish-romesco-sauce)
- [Pico de Gallo](https://natashaskitchen.com/pico-de-gallo/)
- [Garlic Herb Compound Butter](https://cjeatsrecipes.com/garlic-herb-compound-butter/)
- [Jerk Marinade](https://www.chilipeppermadness.com/recipes/jerk-marinade/)
- [Chicken Al Pastor](https://www.thehealthymaven.com/chicken-al-pastor/)
- [Jamaican Cabbage](https://www.myforkinglife.com/jamaican-cabbage/)
- [Turkey Meatballs](https://www.culinaryhill.com/turkey-meatballs/)
- [Beef and Turkey Meatball Subs](https://emilybites.com/2021/05/beef-and-turkey-meatball-subs.html)
- [Sauteed Garlic Asparagus](https://www.allrecipes.com/recipe/92845/sauteed-garlic-asparagus/)
- [Blackened Salmon Fillets](https://www.allrecipes.com/recipe/36487/blackened-salmon-fillets/)
- [Party Popper Potato Bites](https://www.tastefulselections.com/recipe/party-popper-potato-bites/)
- [Chicken Francese](https://www.recipetineats.com/chicken-francese/)

## 📊 Project Status

- **Current Version**: 2.0 (Meal Planning & Sharing Update)
- **Active Branch**: `codex/integrate-meal-planning-calendar-ui`
- **Last Updated**: September 2025
- **Status**: Testing
- **Mobile Responsive**:  Testing
- **Database**: ✅ All Migrations Applied

May have to run Xampp and start apache and mysql