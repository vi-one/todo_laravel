# Laravel Todo Application

## Setup and Installation

### Prerequisites
- Docker and Docker Compose installed on your system

### Installation Steps

1. Copy the example environment file and configure it:
```bash
cp app/.env.example app/.env
```

2. Start the Docker containers:
```bash
docker-compose up -d
```

3. Generate application key:
```bash
docker-compose exec app php artisan key:generate
```

4. Run database migrations:
```bash
docker-compose exec app php artisan migrate
```

5. Access the application at: http://localhost:8000

## Configuration

After copying the `.env.example` file to `.env`, you may need to adjust some settings based on your environment and requirements.

### Environment Configuration
The following settings should be reviewed and updated in your `.env` file:

```
APP_NAME=Laravel                # Your application name
APP_KEY=                        # Will be generated with artisan key:generate
APP_URL=http://localhost:8000   # Your application URL
```

### Database Configuration
The database is automatically configured with the following default settings:
- **Host:** db
- **Port:** 3306
- **Database:** laravel
- **Username:** laravel
- **Password:** root

You can modify these settings in the `.env` file before starting the containers:

```
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=laravel
DB_PASSWORD=root
```

### Email Configuration
The application uses Mailhog for email testing, which is included in the Docker setup:
1. Ensure the Docker containers are running
2. Access the Mailhog web interface at: http://localhost:8025
3. Email settings are pre-configured in the `.env` file:
```
MAIL_MAILER=smtp
MAIL_HOST=mailhog
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"
```

You may want to update the `MAIL_FROM_ADDRESS` and `MAIL_FROM_NAME` to match your application's identity.

### Google Calendar Integration
To enable Google Calendar integration:

1. Create a Google Cloud project and enable the Google Calendar API
2. Create service account credentials and download the JSON key file
3. Create a directory for the credentials and place the JSON key file:
4. Update the `.env` file with your Google Calendar settings:
```
GOOGLE_CALENDAR_ID=your-calendar-id@gmail.com
GOOGLE_CALENDAR_AUTH_PROFILE=service_account
GOOGLE_CALENDAR_CREDENTIALS_JSON=/app/app/google-calendar/service-account-credentials.json
```
5. Share your Google Calendar with the service account email address found in the credentials JSON file

Note: The path in `GOOGLE_CALENDAR_CREDENTIALS_JSON` uses `/app` as the root because the application runs inside a Docker container where the project is mounted at `/app`.

## Running the Application

### Starting and Stopping
```bash
# Start the application
docker-compose up -d

# Stop the application
docker-compose down
```

### Running Artisan Commands
```bash
docker-compose exec app php artisan <command>
```

### Viewing Logs
```bash
# View application logs
docker-compose exec app tail -f storage/logs/laravel.log
```
