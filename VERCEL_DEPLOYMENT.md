# Vercel Deployment Configuration for SeQueueR

## Environment Variables Required

Add these environment variables in your Vercel dashboard:

### Database (Vercel Postgres)
```
POSTGRES_URL=postgres://username:password@host:port/database
POSTGRES_PRISMA_URL=postgres://username:password@host:port/database?pgbouncer=true&connect_timeout=15
POSTGRES_URL_NON_POOLING=postgres://username:password@host:port/database
POSTGRES_USER=username
POSTGRES_HOST=host
POSTGRES_PASSWORD=password
POSTGRES_DATABASE=database
```

### Authentication
```
JWT_SECRET=your-super-secret-jwt-key-here-make-it-long-and-random
```

### Optional
```
NODE_ENV=production
```

## Deployment Steps

1. **Install Vercel CLI**
   ```bash
   npm install -g vercel
   ```

2. **Login to Vercel**
   ```bash
   vercel login
   ```

3. **Initialize Project**
   ```bash
   vercel
   ```

4. **Add Vercel Postgres Database**
   - Go to your Vercel dashboard
   - Navigate to your project
   - Go to Storage tab
   - Add Postgres database
   - Copy the connection strings to environment variables

5. **Set Environment Variables**
   - Go to Settings > Environment Variables
   - Add all required variables listed above

6. **Deploy**
   ```bash
   vercel --prod
   ```

## Database Setup

After deployment, you need to run the database schema:

1. **Connect to your Postgres database**
2. **Run the SQL schema** from `database/postgres-schema.sql`

Or use Vercel's database dashboard to run the SQL commands.

## Domain Configuration

1. **Custom Domain** (Optional)
   - Add your custom domain in Vercel dashboard
   - Update DNS records as instructed

2. **Default Vercel Domain**
   - Your app will be available at: `https://your-project-name.vercel.app`

## Features Included

✅ **Student Interface**
- Queue request form (3-step process)
- QR code generation
- Queue number display

✅ **Admin Interface**
- Dashboard with statistics
- Queue management
- User management
- Settings

✅ **Working Personnel Interface**
- Real-time queue management
- Current serving display
- Queue actions (complete/skip)

✅ **Authentication System**
- JWT-based authentication
- Role-based access control
- Secure password hashing

✅ **Database Integration**
- Vercel Postgres compatibility
- Queue management
- User management
- Statistics and reporting

## Post-Deployment Checklist

- [ ] Database schema created
- [ ] Environment variables set
- [ ] Test student queue request
- [ ] Test admin login
- [ ] Test staff login
- [ ] Verify role-based routing
- [ ] Test queue management
- [ ] Verify QR code generation

## Support

For issues or questions:
1. Check Vercel deployment logs
2. Verify environment variables
3. Check database connectivity
4. Review API route responses
