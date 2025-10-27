# SeQueueR - Queue Management System

A modern queue management system for University of Cebu Student Affairs Office, built with Next.js and deployed on Vercel.

## Features

### 🎓 Student Interface
- **Queue Request System**: 3-step process for requesting queue numbers
- **QR Code Generation**: Automatic QR code generation for easy tracking
- **Real-time Updates**: Live queue status updates
- **Responsive Design**: Mobile-friendly interface

### 👨‍💼 Admin Interface
- **Dashboard**: Comprehensive statistics and analytics
- **Queue Management**: Full control over queue operations
- **User Management**: Add and manage staff accounts
- **Settings**: Configure system parameters
- **Reports**: Generate and download reports

### 👩‍💻 Working Personnel Interface
- **Real-time Queue Display**: See current serving queue
- **Queue Actions**: Complete or skip queue items
- **Student Information**: View detailed student data
- **Queue Statistics**: Monitor queue performance

## Technology Stack

- **Frontend**: Next.js 14, React 18, TypeScript
- **Styling**: Tailwind CSS, Font Awesome
- **Database**: Vercel Postgres
- **Authentication**: JWT with bcrypt
- **Deployment**: Vercel
- **Charts**: Chart.js with react-chartjs-2
- **QR Codes**: react-qr-code

## Project Structure

```
├── app/                          # Next.js App Router
│   ├── student/                  # Student interface
│   │   ├── queue-request/        # Queue request pages
│   │   └── page.tsx             # Student landing
│   ├── personnel/                # Personnel interface
│   │   ├── admin/               # Admin interface
│   │   ├── working/             # Working personnel interface
│   │   └── signin/              # Login page
│   ├── api/                     # API routes
│   │   ├── auth/                # Authentication endpoints
│   │   ├── queue/               # Queue management endpoints
│   │   └── admin/               # Admin endpoints
│   └── globals.css              # Global styles
├── components/                   # Reusable components
│   ├── AdminDashboard.tsx       # Admin dashboard component
│   └── WorkingQueue.tsx        # Working queue component
├── lib/                         # Utility libraries
│   ├── auth.ts                  # Authentication utilities
│   └── database.ts             # Database functions
├── database/                    # Database schemas
│   └── postgres-schema.sql     # PostgreSQL schema
└── public/                      # Static assets
    ├── sao-logo.jpg            # UC SAO logo
    └── sao-nobg.png            # UC SAO logo (no background)
```

## Getting Started

### Prerequisites

- Node.js 18+ 
- npm or yarn
- Vercel account
- Vercel Postgres database

### Installation

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd qms-nextjs
   ```

2. **Install dependencies**
   ```bash
   npm install
   ```

3. **Set up environment variables**
   ```bash
   cp .env.example .env.local
   ```
   
   Add your environment variables:
   ```
   POSTGRES_URL=your_postgres_url
   POSTGRES_PRISMA_URL=your_postgres_prisma_url
   POSTGRES_URL_NON_POOLING=your_postgres_url_non_pooling
   POSTGRES_USER=your_postgres_user
   POSTGRES_HOST=your_postgres_host
   POSTGRES_PASSWORD=your_postgres_password
   POSTGRES_DATABASE=your_postgres_database
   JWT_SECRET=your_jwt_secret
   ```

4. **Run database schema**
   - Connect to your Postgres database
   - Run the SQL commands from `database/postgres-schema.sql`

5. **Start development server**
   ```bash
   npm run dev
   ```

6. **Open your browser**
   Navigate to `http://localhost:3000`

## Deployment to Vercel

### Quick Deploy

1. **Install Vercel CLI**
   ```bash
   npm install -g vercel
   ```

2. **Login to Vercel**
   ```bash
   vercel login
   ```

3. **Deploy**
   ```bash
   vercel --prod
   ```

### Detailed Deployment

See [VERCEL_DEPLOYMENT.md](./VERCEL_DEPLOYMENT.md) for complete deployment instructions.

## User Roles & Access

### Students
- **Access**: `/student`
- **Features**: Queue request, QR code generation, queue tracking
- **Authentication**: Not required

### Admin Personnel
- **Access**: `/personnel/admin/dashboard`
- **Features**: Full system control, user management, reports
- **Authentication**: Required (admin role)

### Working Personnel  
- **Access**: `/personnel/working/queue`
- **Features**: Queue management, student service
- **Authentication**: Required (staff role)

## Default Login Credentials

After running the database schema, you can use:

- **Username**: `admin`
- **Password**: `admin123`
- **Role**: Administrator

## API Endpoints

### Authentication
- `POST /api/auth/login` - User login
- `POST /api/auth/logout` - User logout

### Queue Management
- `POST /api/queue/create` - Create queue request
- `GET /api/queue/active` - Get active queues
- `PUT /api/queue/active` - Update queue status

### Admin
- `GET /api/admin/dashboard` - Get dashboard data

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Test thoroughly
5. Submit a pull request

## License

This project is licensed under the MIT License.

## Support

For support or questions:
- Create an issue in the repository
- Contact the development team
- Check the deployment logs in Vercel dashboard

## Changelog

### Version 1.0.0
- Initial release
- Student queue request system
- Admin dashboard
- Working personnel interface
- JWT authentication
- Vercel Postgres integration
- QR code generation
- Responsive design